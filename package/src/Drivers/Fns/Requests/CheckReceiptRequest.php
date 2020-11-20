<?php

namespace InetStudio\Fns\Drivers\Fns\Requests;

final class CheckReceiptRequest extends ReceiptRequest
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
