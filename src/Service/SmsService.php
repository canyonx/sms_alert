<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class SmsService
{
    public function __construct(private LoggerInterface $logger) {}

    /**
     * Ecrit dans les logs lors de l'envoi d'un SMS
     * Ajout message, date
     *
     * @param string $phone
     */
    public function sendSms(string $phone, string $message): void
    {
        $log = "Sms envoyé à $phone : $message | " . (new \DateTime())->format('Y-m-d H:i:s');

        $this->logger->info($log);
    }
}
