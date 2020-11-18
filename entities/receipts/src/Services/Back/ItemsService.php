<?php

namespace InetStudio\Fns\Receipts\Services\Back;

use Illuminate\Support\Arr;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\Fns\Contracts\Services\ReceiptsServiceContract;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\Fns\Receipts\Contracts\Services\Back\ItemsServiceContract;

class ItemsService extends BaseService implements ItemsServiceContract
{
    protected ReceiptsServiceContract $receiptsService;

    public function __construct(ReceiptModelContract $model, ReceiptsServiceContract $receiptsService)
    {
        parent::__construct($model);

        $this->receiptsService = $receiptsService;
    }

    public function save(array $data, int $id): ReceiptModelContract
    {
        $itemData = Arr::only($data, $this->model->getFillable());

        return $this->saveModel($itemData, $id);
    }

    public function getReceiptByQrCode(string $qrCode): ?ReceiptModelContract
    {
        $receipt = $this->model::where('qr_code', $qrCode)->first();

        if ($receipt) {
            return $receipt;
        }

        $receipt = null;

        $params = $this->parseQrCode($qrCode);

        if (empty($params)) {
            return $receipt;
        }

        $checkResult = $this->receiptsService->checkReceipt($params);

        if ($checkResult) {
            $receiptData = $this->receiptsService->getReceipt($params);

            if ($receiptData) {
                $receipt = $this->saveModel(
                    [
                        'qr_code' => $qrCode,
                        'receipt' => $receiptData,
                    ]
                );
            }
        }

        return $receipt;
    }

    protected function parseQrCode(string $qrCode): array
    {
        if (! $qrCode) {
            return [];
        }

        $params = [];
        parse_str(trim($qrCode), $params);

        $params = Arr::only($params, ['t', 's', 'fn', 'i', 'fp', 'n']);

        if (count($params) != 6) {
            return [];
        }

        return $params;
    }
}
