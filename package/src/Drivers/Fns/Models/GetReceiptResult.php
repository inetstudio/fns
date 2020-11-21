<?php

namespace InetStudio\Fns\Drivers\Fns\Models;

final class GetReceiptResult extends ReceiptResult
{
    protected $receipt;

    protected final function __construct(int $code, ?string $message = null, $receipt = null)
    {
        parent::__construct($code, $message);

        $this->receipt = $receipt;
    }

    public static function create(int $code, ?string $message = null, $receipt = null): self
    {
        return new GetReceiptResult($code, $message, $receipt);
    }

    public function getReceipt()
    {
        return $this->receipt;
    }
}
