<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Monolog\Attribute\WithMonologChannel;

#[WithMonologChannel('sms')]
class SmsService
{
    public function __construct(private LoggerInterface $logger) {}

    /**
     * Ecrit dans les logs lors de l'envoi d'un SMS
     * Ajout message, date
     *
     * @param string $phone
     */
    public function sendSms(string $phone, string $message, string $insee = ''): void
    {
        $log = "Sms envoyé à $phone : $message | $insee | " . (new \DateTime())->format('Y-m-d H:i:s');

        $this->logger->info($log);
    }
}
