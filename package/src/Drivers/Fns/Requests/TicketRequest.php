<?php

namespace InetStudio\Fns\Drivers\Fns\Requests;

use InetStudio\Fns\Drivers\Fns\Models\Ticket;

abstract class TicketRequest
{
    private Ticket $ticket;

    public final function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    protected abstract function getRequestElementName(): string;

    protected abstract function getInfoElementName(): string;

    public final function __toString()
    {
        return '
            <tns:'.$this->getRequestElementName().' xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0">
                <tns:'.$this->getInfoElementName().'>
                    <tns:Sum>'.$this->ticket->sum.'</tns:Sum>
                    <tns:Date>'.$this->ticket->time->format('Y-m-d\TH:i:s').'</tns:Date>
                    <tns:Fn>'.$this->ticket->fn.'</tns:Fn>
                    <tns:TypeOperation>'.$this->ticket->type.'</tns:TypeOperation>
                    <tns:FiscalDocumentId>'.$this->ticket->fd.'</tns:FiscalDocumentId>
                    <tns:FiscalSign>'.$this->ticket->fpd.'</tns:FiscalSign>
                </tns:'.$this->getInfoElementName().'>
            </tns:'.$this->getRequestElementName().'>
        ';
    }
}
