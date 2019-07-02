<?php

namespace InetStudio\Fns\Services\Back;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\Fns\Contracts\Services\Back\FnsServiceContract;

/**
 * Class FnsService.
 */
class FnsService implements FnsServiceContract
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * FnsService constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Регистрация в сервисе ФНС.
     *
     * @param  array  $params
     *
     * @return bool
     */
    public function signUp(array $params): bool
    {
        $url = 'https://proverkacheka.nalog.ru:9999/v1/mobile/users/signup';

        $body = sprintf(
            '
            {
                "email": "%s",
                "name": "%s",
                "phone": "%s"
            }
            ',
            $params['email'],
            $params['name'],
            $params['phone']
        );

        $requestParams = [
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        try {
            $response = $this->client->post($url, $requestParams);
            $responseCode = $response->getStatusCode();

            return $responseCode == 204;
        } catch (ClientException $error) {
            return false;
        }
    }

    /**
     * Авторизация в сервисе ФНС.
     *
     * @param  array  $params
     *
     * @return array
     */
    public function login(array $params): array
    {
        $url = 'https://proverkacheka.nalog.ru:9999/v1/mobile/users/login';

        $requestParams = [
            'auth' => [
                $params['login'],
                $params['password'],
            ],
        ];

        try {
            $response = $this->client->get($url, $requestParams);
            $responseCode = $response->getStatusCode();
            $responseContent = $response->getBody()->getContents();

            return ($responseCode == 200) ? json_decode($responseContent, true) : [];
        } catch (ClientException $error) {
            return [];
        }
    }

    /**
     * Проверка существования чека.
     *
     * @param  array  $params
     *
     * @return bool
     *
     * @throws BindingResolutionException
     */
    public function checkReceipt(array $params): bool
    {
        $account = $this->getAccount();

        if (! $account) {
            return false;
        }

        $url = sprintf(
            'https://proverkacheka.nalog.ru:9999/v1/ofds/*/inns/*/fss/%d/operations/%d/tickets/%d',
            $params['fn'],
            $params['n'],
            $params['i']
        );

        $sum = ((float) str_replace([' ', ','], ['', '.'], $params['s'])) * 100;

        $requestParams = [
            'query' => [
                'sum' => $sum,
                'date' => $params['t'],
                'fiscalSign' => $params['fp'],
            ],
            'auth' => $account,
            'headers' => [
                'device-id' => '',
                'device-os' => '',
            ],
        ];

        try {
            $response = $this->client->get($url, $requestParams);
            $responseCode = $response->getStatusCode();

            return $responseCode == 204;
        } catch (ClientException $error) {
            return false;
        }
    }

    /**
     * Возвращаем содержимое чека.
     *
     * @param  array  $params
     *
     * @return array|null
     *
     * @throws BindingResolutionException
     */
    public function getReceipt(array $params): ?array
    {
        $account = $this->getAccount();

        if (! $account) {
            return null;
        }

        $url = sprintf(
            'https://proverkacheka.nalog.ru:9999/v1/inns/*/kkts/*/fss/%d/tickets/%d',
            $params['fn'],
            $params['i']
        );

        $requestParams = [
            'query' => [
                'fiscalSign' => $params['fp'],
                'sendToEmail' => 'no',
            ],
            'auth' => $account,
            'headers' => [
                'device-id' => '',
                'device-os' => '',
            ],
        ];

        try {
            $response = $this->client->get($url, $requestParams);
        } catch (ClientException $error) {
            if ($error->hasResponse()) {
                $response = $error->getResponse();
                $responseCode = $response->getStatusCode();
                $responseBody = (string) $response->getBody();

                if ($responseCode == 402 && $responseBody == 'daily limit reached for the specified user') {
                    $this->blockAccount($account[0]);
                }
            }

            return null;
        }

        $responseCode = $response->getStatusCode();
        $receiptContent = $response->getBody()->getContents();

        if ($responseCode == 202 || $receiptContent == '') {
            $attempts = 0;

            while ($receiptContent == '' && $attempts < 10) {
                try {
                    $response = $this->client->get($url, $requestParams);
                } catch (ClientException $error) {
                    if ($error->hasResponse()) {
                        $response = $error->getResponse();
                        $responseCode = $response->getStatusCode();
                        $responseBody = (string) $response->getBody();

                        if ($responseCode == 402 && $responseBody == 'daily limit reached for the specified user') {
                            $this->blockAccount($account[0]);
                        }
                    }

                    return null;
                }

                $receiptContent = $response->getBody()->getContents();

                $attempts++;
                sleep(2);
            }
        }

        return ($receiptContent)
            ? json_decode($receiptContent, true)
            : null;
    }

    /**
     * Получаем активный аккаунт.
     *
     * @return array|null
     *
     * @throws BindingResolutionException
     */
    protected function getAccount(): ?array
    {
        $accountsService = app()->make('InetStudio\Fns\Accounts\Contracts\Services\Back\ItemsServiceContract');

        $account = $accountsService->getActiveAccount();

        if (! $account) {
            return null;
        }

        return [
            $account['login'],
            $account['password'],
        ];
    }

    /**
     * Блокируем аккаунт.
     *
     * @param  string  $login
     *
     * @throws BindingResolutionException
     */
    protected function blockAccount(string $login): void
    {
        $accountsService = app()->make('InetStudio\Fns\Accounts\Contracts\Services\Back\ItemsServiceContract');

        $accountsService->blockAccount($login, time());
    }
}
