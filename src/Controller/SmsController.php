<?php

namespace App\Controller;

use App\Service\SmsService;
use Doctrine\DBAL\Connection;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SmsController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(SmsService $smsService): JsonResponse
    {

        $smsService->sendSms('0612345678', 'Alerte pluie');

        return $this->json([
            'message' => 'Sms envoyé, check log',
        ]);
    }

    #[Route('/alert/{insee]', name: 'app_alert')]
    public function alert(string $insee, SmsService $smsService, Connection $connection): JsonResponse
    {
        // $qb = $connection->createQueryBuilder();
        // $qb->select('p')
        // ->andWhere('insee' = '')

        $smsService->sendSms('0612345678', 'Alerte pluie');

        return $this->json([
            'message' => 'Sms envoyé, check log',
        ]);
    }
}
