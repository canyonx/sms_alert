<?php

namespace App\MessageHandler;

use App\Message\AlertMessage;
use App\Service\SmsService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AlertMessageHandler
{
    public function __construct(private SmsService $smsService) {}

    public function __invoke(AlertMessage $message)
    {
        $this->smsService->sendSms($message->getPhone(), $message->getContent());
    }
}
