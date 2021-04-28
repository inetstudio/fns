<?php

declare(strict_types=1);

namespace InetStudio\Fns\Receipts\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use InetStudio\Fns\Receipts\Contracts\DTO\ItemDataContract;

class ItemData extends DataTransferObject implements ItemDataContract
{
    public ?string $id;

    public string $qr_code;

    public array $data = [];
}
