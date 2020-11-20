<?php

namespace InetStudio\Fns\Drivers\Fns\Responses;

use InetStudio\Fns\Drivers\Fns\Models\GetTicketResult;

final class GetTicketResponse extends TicketResponse
{
    private ?GetTicketResult $result;

    protected final function __construct(string $processingStatus, ?GetTicketResult $result = null)
    {
        parent::__construct($processingStatus);

        $this->result = $result;
    }

    public static function create(string $processingStatus, ?GetTicketResult $result = null): self
    {
        return new GetTicketResponse($processingStatus, $result);
    }

    public function getResult(): ?GetTicketResult
    {
        return $this->result;
    }
}
