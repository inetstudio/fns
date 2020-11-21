<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

abstract class ReceiptResult
{
    protected int $code;

    protected ?string $message;

    protected function __construct(int $code, ?string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
