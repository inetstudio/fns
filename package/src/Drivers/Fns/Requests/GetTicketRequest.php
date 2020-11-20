<?php

namespace InetStudio\Fns\Drivers\Fns\Requests;

final class GetTicketRequest extends TicketRequest
{
    protected function getRequestElementName(): string
    {
        return 'GetTicketRequest';
    }

    protected function getInfoElementName(): string
    {
        return 'GetTicketInfo';
    }
}
