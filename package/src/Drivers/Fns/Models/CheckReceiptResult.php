<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

final class CheckReceiptResult extends ReceiptResult
{
    protected final function __construct(int $code, string $message)
    {
        parent::__construct($code, $message);
    }

    public static function create(int $code, string $message): self
    {
        return new CheckReceiptResult($code, $message);
    }
}
