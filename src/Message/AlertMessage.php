<?php

namespace App\Message;

class AlertMessage
{
    public function __construct(
        private string $phone,
        private string $content,
    ) {}

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
