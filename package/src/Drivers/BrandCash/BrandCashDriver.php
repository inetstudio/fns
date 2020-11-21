<?php

namespace InetStudio\Fns\Drivers\BrandCash;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use GuzzleHttp\Exception\ClientException;
use InetStudio\Fns\Drivers\Fns\Models\GetReceiptResult;
use InetStudio\Fns\Drivers\Fns\Models\CheckReceiptResult;
use InetStudio\Fns\Drivers\Fns\Responses\GetReceiptResponse;
use InetStudio\Fns\Contracts\Drivers\BrandCashDriverContract;
use InetStudio\Fns\Drivers\Fns\Responses\CheckReceiptResponse;

class BrandCashDriver implements BrandCashDriverContract
{
    protected array $brandCashParams;

    protected Client $client;

    public function __construct()
    {
        $this->brandCashParams = config('services.brand_cash', []);
        $this->client = new Client();
    }

    public function checkReceipt(array $params)
    {
        return CheckReceiptResponse::create('COMPLETED', CheckReceiptResult::create(200, ''));
    }

    public function getReceipt(array $params)
    {
        $requestParams = $this->prepareParams($params);

        try {
            $response = $this->client->get(trim($this->brandCashParams['url'], '/').'/receipts/get', $requestParams);
        } catch (ClientException $error) {
            return GetReceiptResponse::create('PROCESSING');
        }

        $responseCode = $response->getStatusCode();
        $receiptData = ($responseCode == 200) ? json_decode($response->getBody()->getContents()) : null;

        return GetReceiptResponse::create('COMPLETED', GetReceiptResult::create($responseCode, null, $receiptData));
    }

    protected function prepareParams(array $params): array
    {
        return [
            'query' => array_merge($params, Arr::only($this->brandCashParams, 'api_key')),
        ];
    }
}
