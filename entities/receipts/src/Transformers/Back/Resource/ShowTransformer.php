<?php

namespace InetStudio\Fns\Receipts\Transformers\Back\Resource;

use Throwable;
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
     * @param  ReceiptModelContract  $item
     *
     * @return array
     *
     * @throws Throwable
     */
    public function transform(?ReceiptModelContract $item): array
    {
        return ($item) ? $item->toArray() : [];
    }
}
