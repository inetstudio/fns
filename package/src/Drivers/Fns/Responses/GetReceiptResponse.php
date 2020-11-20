<?php

namespace InetStudio\Fns\Drivers\Fns\Responses;

use InetStudio\Fns\Drivers\Fns\Models\GetReceiptResult;

final class GetReceiptResponse extends ReceiptResponse
{
    protected ?GetReceiptResult $result;

    protected final function __construct(string $processingStatus, ?GetReceiptResult $result = null)
    {
        parent::__construct($processingStatus);

        $this->result = $result;
    }

    public static function create(string $processingStatus, ?GetReceiptResult $result = null): self
    {
        return new GetReceiptResponse($processingStatus, $result);
    }

    public function getResult(): ?GetReceiptResult
    {
        return $this->result;
    }
}
