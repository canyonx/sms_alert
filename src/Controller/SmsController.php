<?php

namespace App\Controller;

use Exception;
use App\Service\SmsService;
use App\Message\AlertMessage;
use Doctrine\DBAL\Connection;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SmsController extends AbstractController
{
    /**
     * Endpoint Alerter 
     * envoi un sms en fonction du code insee fournit
     * Utilisation de apiKey pour sécuriser la route, définie dans .env
     *
     * @param string $insee
     * @param string $apiKey
     */
    #[Route('/alerter/{insee}/{apiKey}', name: 'app_alerter')]
    public function alerter(
        string $insee,
        string $apiKey,
        SmsService $smsService,
        Connection $connection,
        MessageBusInterface $bus
    ): JsonResponse {
        // Check api key
        if ($apiKey !== $this->getParameter('api_key')) {
            return $this->json([
                'message' => 'Try again, you got the wrong api key !'
            ], 401);
        }

        // SELECT phone FROM recipients WHERE insee = :insee
        $qb = $connection->createQueryBuilder();
        $qb->select('phone, insee')
            ->from('recipients')
            ->andWhere('insee = :i')
            ->setParameter('i', $insee);

        $result = $qb->executeQuery()
            ->fetchAllAssociative();

        // compteur d'envoi
        $count = 0;

        foreach ($result as $value) {
            // écrire un log (sans messenger async)
            // $smsService->sendSms($value['phone'], 'Alerte pluie');

            // utiliser le bus pour envoyer les messages de manière async
            $bus->dispatch(new AlertMessage($value['phone'], 'Alerte orage', $value['insee']));

            $count++;
        }

        return $this->json([
            'message' => $count . ' sms envoyés, check logs in /var/log/sms_dev-YYYY-mm-dd.log'
        ], 200);
    }
}
