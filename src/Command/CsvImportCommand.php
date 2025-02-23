<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:csv-import',
    description: 'Importer un fichier csv',
)]
class CsvImportCommand extends Command
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private Connection $connection
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Choisir un fichier .csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        // Verifier que le fichier existe
        if (!file_exists($file)) {
            $io->error(sprintf("Le fichier $file n'existe pas."));
            return Command::FAILURE;
        }

        // Vérifier que le fichier est un .csv (extension + mime_type)
        if (
            pathinfo($file, PATHINFO_EXTENSION) !== 'csv' ||
            mime_content_type($file) !== 'text/csv'
        ) {
            $io->error(sprintf("Le fichier $file n'est pas un .csv."));
            return Command::FAILURE;
        }

        // lecture du fichier
        $content = fopen($file, 'r');

        // Echappe la première ligne
        fgetcsv($content);

        // Compteurs
        $valid = 0;
        $error = 0;

        while ($ligne = fgetcsv($content, 50, ',')) {
            $insee = $ligne[0];
            $phone = $ligne[1];

            // Verifier le numéro insee et phone 
            if ($this->checkInsee($insee) && $this->checkPhone($phone)) {
                // formater le numéro 
                $phone =  str_replace([' ', '+33'], ['', '0'], $phone);
                // enregistrer dans la bdd
                $this->connection->insert('recipients', [
                    'insee' => $insee,
                    'phone' => $phone
                ]);
                $valid++;
            } else {
                $error++;
            }
        }

        // fermeture du fichier
        fclose($content);

        $io->success("$valid imports, $error erreurs");
        return Command::SUCCESS;
    }

    // Valide le numéro INSEE
    private function checkInsee(string $insee): bool
    {
        return preg_match('/^\d{5}$/', $insee);
    }

    // Valide le numéro de téléphone
    private function checkPhone(string $phone): bool
    {
        return preg_match('/^(?:\+33\s?|0)[67]\d{8}$/', $phone);
    }
}
