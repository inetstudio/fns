<?php

namespace InetStudio\Fns\Receipts\Contracts\Services;

use InetStudio\Fns\Receipts\Contracts\DTO\ItemDataContract;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;

interface ItemsServiceContract
{
    public function save(ItemDataContract $data): ReceiptModelContract;

    public function getReceiptByQrCode(string $qrCode): array;
}
