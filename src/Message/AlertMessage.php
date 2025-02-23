<?php

namespace App\Message;

class AlertMessage
{
    public function __construct(
        private string $phone,
        private string $content,
        private string $insee = '',
    ) {}

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getInsee(): string
    {
        return $this->insee;
    }
}
