<?php

namespace InetStudio\Fns\Receipts\Services;

use Illuminate\Support\Arr;
use InetStudio\Fns\Receipts\Contracts\DTO\ItemDataContract;
use InetStudio\Fns\Contracts\Services\ReceiptsServiceContract;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\Fns\Receipts\Contracts\Services\ItemsServiceContract;

class ItemsService implements ItemsServiceContract
{
    protected ReceiptModelContract $model;

    protected ReceiptsServiceContract $receiptsService;

    public function __construct(ReceiptModelContract $model, ReceiptsServiceContract $receiptsService)
    {
        $this->model = $model;
        $this->receiptsService = $receiptsService;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function save(ItemDataContract $data): ReceiptModelContract
    {
        $item = $this->model::find($data->id) ?? new $this->model;

        $item->qr_code = $data->qr_code;
        $item->data = $data->data;

        $item->save();

        return $item;
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

                    $data = resolve(
                        'InetStudio\Fns\Receipts\Contracts\DTO\ItemDataContract',
                        [
                            'parameters' => [
                                'qr_code' => $qrCode,
                                'data' => $data,
                            ],
                        ]
                    );

                    $receiptModel = $this->save($data);
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
