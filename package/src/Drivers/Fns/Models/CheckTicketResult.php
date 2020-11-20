<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

final class CheckTicketResult extends TicketResult
{
    protected final function __construct(int $code, string $message)
    {
        parent::__construct($code, $message);
    }

    public static function create(int $code, string $message): self
    {
        return new CheckTicketResult($code, $message);
    }
}
