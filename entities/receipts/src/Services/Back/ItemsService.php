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

    public function getReceiptByQrCode(string $qrCode): array
    {
        $receipt = $this->model::where('qr_code', $qrCode)->first();

        if ($receipt) {
            return [
                'code' => 200,
                'message' => null,
                'receipt' => $receipt,
            ];
        }

        $params = $this->parseQrCode($qrCode);

        if (empty($params)) {
            return [
                'code' => 406,
                'message' => 'Переданы не полные данные',
                'receipt' => null,
            ];
        }

        $checkResult = $this->receiptsService->checkReceipt($params);

        switch ($checkResult->getResult()->getCode()) {
            case 200:
                $getReceiptResult = $this->receiptsService->getReceipt($params);

                $receiptModel = null;

                if ($getReceiptResult->getResult()->getCode() === 200) {
                    $receipt = $getReceiptResult->getResult()->getReceipt();
                    $receiptData = (array) $receipt;

                    $data = [];

                    if (! isset($receiptData['content'])) {
                        $data['content'] = $receiptData;
                    } else {
                        $data = $receiptData;
                    }

                    $receiptModel = $this->saveModel(
                        [
                            'qr_code' => $qrCode,
                            'data' => $data,
                        ]
                    );
                }

                return [
                    'code' => $getReceiptResult->getResult()->getCode(),
                    'message' => $getReceiptResult->getResult()->getMessage(),
                    'receipt' => $receiptModel,
                ];

            default:

                return [
                    'code' => $checkResult->getResult()->getCode(),
                    'message' => $checkResult->getResult()->getMessage(),
                    'receipt' => null,
                ];
        }
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
