<?php

namespace App\Controller;

use App\Service\SmsService;
use Doctrine\DBAL\Connection;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SmsController extends AbstractController
{
    // Route de test des log
    #[Route('/test', name: 'app_test')]
    public function index(SmsService $smsService): JsonResponse
    {
        $smsService->sendSms('0612345678', 'Alerte pluie');

        return $this->json(['message' => 'Sms envoyé, check log']);
    }

    /**
     * Endpoint Alerter 
     * envoi un sms en fonction du code insee fournit
     *
     * @param string $insee
     */
    #[Route('/alerter/{insee}', name: 'app_alerter')]
    public function alerter(string $insee, SmsService $smsService, Connection $connection): JsonResponse
    {
        // SELECT phone FROM recipients WHERE insee = :insee
        $qb = $connection->createQueryBuilder();
        $qb->select('phone')
            ->from('recipients')
            ->andWhere('insee = :i')
            ->setParameter('i', $insee);

        $result = $qb->executeQuery()
            ->fetchAllAssociative();

        // dump($result);

        // compteur d'envoi
        $count = 0;

        foreach ($result as $value) {
            // dump($value['phone']);
            $smsService->sendSms($value['phone'], 'Alerte pluie');
            $count++;
        }

        return $this->json(['message' => $count . ' sms envoyés, check logs in /var/log/dev.log'], 200);
    }
}
