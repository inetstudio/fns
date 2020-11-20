<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

final class GetTicketResult extends TicketResult
{
    private ?Ticket $ticket;

    protected final function __construct(int $code, string $message = null, ?Ticket $ticket = null)
    {
        parent::__construct($code, $message);

        $this->ticket = $ticket;
    }

    public static function create(int $code, string $message = null, $ticket = null): self
    {
        return new GetTicketResult($code, $message, $ticket);
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }
}
