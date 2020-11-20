<?php

namespace InetStudio\Fns\Drivers\Fns\Requests;

use InetStudio\Fns\Drivers\Fns\Models\Receipt;

abstract class ReceiptRequest
{
    protected Receipt $receipt;

    public final function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    protected abstract function getRequestElementName(): string;

    protected abstract function getInfoElementName(): string;

    public final function __toString()
    {
        return '
            <tns:'.$this->getRequestElementName().' xmlns:tns="urn://x-artefacts-gnivc-ru/ais3/kkt/KktTicketService/types/1.0">
                <tns:'.$this->getInfoElementName().'>
                    <tns:Sum>'.$this->receipt->sum.'</tns:Sum>
                    <tns:Date>'.$this->receipt->time->format('Y-m-d\TH:i:s').'</tns:Date>
                    <tns:Fn>'.$this->receipt->fn.'</tns:Fn>
                    <tns:TypeOperation>'.$this->receipt->type.'</tns:TypeOperation>
                    <tns:FiscalDocumentId>'.$this->receipt->fd.'</tns:FiscalDocumentId>
                    <tns:FiscalSign>'.$this->receipt->fpd.'</tns:FiscalSign>
                </tns:'.$this->getInfoElementName().'>
            </tns:'.$this->getRequestElementName().'>
        ';
    }
}
