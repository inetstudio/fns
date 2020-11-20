<?php

namespace InetStudio\Fns\Drivers\Fns\Responses;

use InetStudio\Fns\Drivers\Fns\Models\CheckTicketResult;

final class CheckTicketResponse extends TicketResponse
{
    private ?CheckTicketResult $result;

    protected final function __construct(string $processingStatus, ?CheckTicketResult $result = null)
    {
        parent::__construct($processingStatus);

        $this->result = $result;
    }

    public static function create(string $processingStatus, ?CheckTicketResult $result = null): self
    {
        return new CheckTicketResponse($processingStatus, $result);
    }

    public function getResult(): ?CheckTicketResult
    {
        return $this->result;
    }
}
