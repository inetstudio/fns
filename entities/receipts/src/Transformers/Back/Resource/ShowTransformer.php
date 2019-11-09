<?php

namespace InetStudio\Fns\Receipts\Transformers\Back\Resource;

use League\Fractal\TransformerAbstract;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\Fns\Receipts\Contracts\Transformers\Back\Resource\ShowTransformerContract;

/**
 * Class ShowTransformer.
 */
class ShowTransformer extends TransformerAbstract implements ShowTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  ReceiptModelContract|null  $item
     *
     * @return array
     */
    public function transform(?ReceiptModelContract $item): array
    {
        return ($item) ? $item->toArray() : [];
    }
}
