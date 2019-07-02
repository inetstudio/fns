<?php

namespace InetStudio\Fns\Receipts\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class AttachPointsToReceiptsCommand.
 */
class AttachPointsToReceiptsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inetstudio:fns:receipts:ahunter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach points to receipts';

    /**
     * Execute the console command.
     *
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $pointsService = app()->make('InetStudio\AddressesPackage\Points\Contracts\Services\Back\ItemsServiceContract');
        $receiptsService = app()->make('InetStudio\Fns\Receipts\Contracts\Services\Back\ItemsServiceContract');
        $ahunterService = app()->make('InetStudio\AddressesPackage\Points\Contracts\Services\Back\AHunterServiceContract');

        $receipts = $receiptsService->getModel()->doesntHave('points')->get();

        $bar = $this->output->createProgressBar(count($receipts));

        foreach ($receipts as $receipt) {
            if ($receipt->hasJSONData('receipt', 'address_process')) {
                continue;
            }

            $receiptAddress = $receipt['receipt']['document']['receipt']['retailPlaceAddress'] ?? '';

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

            if ($hash) {
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

            $receipt->setJSONData('receipt', 'address_process', true);
            $receipt->save();

            $bar->advance();
        }

        $bar->finish();
    }
}
