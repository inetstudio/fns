<?php

namespace InetStudio\Fns\Services\Back;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract;

class BrandCashService implements BrandCashServiceContract
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function checkReceipt(array $params): bool
    {
        return true;

        $settings = config('services.brand_cash');

        $requestParams = [
            'query' => array_merge($params, $settings),
        ];

        try {
            $response = $this->client->get('https://api.brand.cash/v2/receipts/check', $requestParams);
            $responseCode = $response->getStatusCode();

            return $responseCode == 204;
        } catch (ClientException $error) {
            return false;
        }
    }

    public function getReceipt(array $params): ?array
    {
        $settings = config('services.brand_cash');

        $requestParams = [
            'query' => array_merge($params, $settings),
        ];

        try {
            $response = $this->client->get('https://api.brand.cash/v2/receipts/get', $requestParams);
        } catch (ClientException $error) {
            return null;
        }

        $responseCode = $response->getStatusCode();
        $receiptContent = ($responseCode == 200) ? $response->getBody()->getContents() : null;

        return ($receiptContent)
            ? [
                'document' => [
                    'receipt' => json_decode($receiptContent, true),
                ],
            ]
            : null;
    }
}
