<?php

namespace InetStudio\Fns\Drivers\Fns\Requests;

final class GetReceiptRequest extends ReceiptRequest
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
