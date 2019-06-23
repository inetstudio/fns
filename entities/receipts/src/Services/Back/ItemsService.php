<?php

namespace InetStudio\Fns\Receipts\Services\Back;

use Illuminate\Support\Arr;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\Fns\Receipts\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  ReceiptModelContract  $model
     */
    public function __construct(ReceiptModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return ReceiptModelContract
     */
    public function save(array $data, int $id): ReceiptModelContract
    {
        $itemData = Arr::only($data, $this->model->getFillable());
        $item = $this->saveModel($itemData, $id);

        return $item;
    }

    /**
     * Получаем чек из fns по QR коду.
     *
     * @param  string  $qrCode
     *
     * @return ReceiptModelContract|null
     *
     * @throws BindingResolutionException
     */
    public function getReceiptByQrCode(string $qrCode): ?ReceiptModelContract
    {
        $receipt = $this->model::where('qr_code', $qrCode)->first();

        if ($receipt) {
            return $receipt;
        }

        $receipt = null;

        $fnsService = app()->make('InetStudio\Fns\Contracts\Services\Back\FnsServiceContract');

        $params = $this->parseQrCode($qrCode);

        if (empty($params)) {
            return $receipt;
        }

        $checkResult = $fnsService->checkReceipt($params);

        if ($checkResult) {
            $receiptData = $fnsService->getReceipt($params);

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

    /**
     * Парсим QR код.
     *
     * @param  string  $qrCode
     *
     * @return array
     */
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
