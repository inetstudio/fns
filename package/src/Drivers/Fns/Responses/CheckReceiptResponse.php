<?php

namespace InetStudio\Fns\Drivers\Fns\Responses;

use InetStudio\Fns\Drivers\Fns\Models\CheckReceiptResult;

final class CheckReceiptResponse extends ReceiptResponse
{
    protected ?CheckReceiptResult $result;

    protected final function __construct(string $processingStatus, ?CheckReceiptResult $result = null)
    {
        parent::__construct($processingStatus);

        $this->result = $result;
    }

    public static function create(string $processingStatus, ?CheckReceiptResult $result = null): self
    {
        return new CheckReceiptResponse($processingStatus, $result);
    }

    public function getResult(): ?CheckReceiptResult
    {
        return $this->result;
    }
}
