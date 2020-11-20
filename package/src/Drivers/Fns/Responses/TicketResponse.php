<?php

namespace InetStudio\Fns\Drivers\Fns\Responses;

abstract class TicketResponse
{
    private string $processingStatus;

    protected function __construct(string $processingStatus)
    {
        $this->processingStatus = $processingStatus;
    }

    public function getProcessingStatus(): string
    {
        return $this->processingStatus;
    }
}
