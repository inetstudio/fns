<?php

namespace InetStudio\Fns\Receipts\Console\Commands;

use Illuminate\Console\Command;

class AttachPointsToReceiptsCommand extends Command
{
    protected $signature = 'inetstudio:fns:receipts:ahunter';

    protected $description = 'Attach points to receipts';

    public function handle()
    {
        $pointsService = resolve('InetStudio\AddressesPackage\Points\Contracts\Services\Back\ItemsServiceContract');
        $receiptsService = resolve('InetStudio\Fns\Receipts\Contracts\Services\ItemsServiceContract');
        $ahunterService = resolve('InetStudio\AddressesPackage\Points\Contracts\Services\Back\AHunterServiceContract');

        $receipts = $receiptsService->getModel()->doesntHave('points')->get();

        $bar = $this->output->createProgressBar(count($receipts));

        foreach ($receipts as $receipt) {
            if ($receipt->hasJSONData('data', 'address_process')) {
                continue;
            }

            $receiptAddress = $receipt['data']['content']['retailPlaceAddress'] ?? '';

            if (! $receiptAddress) {
                continue;
            }

            $ahunterResult = $ahunterService->recognizeAddress($receiptAddress);

            foreach ($ahunterResult['addresses'] as $address) {
                if ($address['quality']['precision'] > 80) {
                    $hash = $address['codes']['fias_house'] ?? md5($address['pretty']);

                    break;
                }
            }

            if (isset($hash)) {
                $point = $pointsService->getModel()->where('hash', '=', $hash)->first();

                if ($point) {
                    $pointsService->attachToObject(
                        [
                            $point->id,
                        ],
                        $receipt
                    );
                }
            }

            $receipt->setJSONData('data', 'address_process', true);
            $receipt->save();

            $bar->advance();
        }

        $bar->finish();
    }
}
