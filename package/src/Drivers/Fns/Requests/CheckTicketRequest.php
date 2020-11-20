<?php

namespace InetStudio\Fns\Drivers\Fns\Requests;

final class CheckTicketRequest extends TicketRequest
{
    protected function getRequestElementName(): string
    {
        return 'CheckTicketRequest';
    }

    protected function getInfoElementName(): string
    {
        return 'CheckTicketInfo';
    }
}
