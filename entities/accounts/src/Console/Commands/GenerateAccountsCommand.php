<?php

namespace InetStudio\Fns\Accounts\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class GenerateAccountsCommand.
 */
class GenerateAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inetstudio:fns:accounts:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate FNS accounts';

    /**
     * @var Client
     */
    protected $client;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();
    }

    /**
     * Запуск команды.
     *
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $accountsService = app()->make('InetStudio\Fns\Accounts\Contracts\Services\Back\ItemsServiceContract');
        $fnsService = app()->make('InetStudio\Fns\Contracts\Services\Back\FnsServiceContract');

        /*
        $users = [];

        $names = $this->getRandomNames(45);
        foreach ($names as $name) {
            if ($name != '') {
                $phoneOperation = dd($this->getVirtualPhoneNumber());

                $users[] = [
                    'name' => $name,
                    'email' => $this->getFakeEmail($name),
                    'login' => '_',
                    'password' => '_',
                ];
            }
        }

        foreach ($users as $user) {
            $accountsService->saveModel($user);
        }

        $operations = $this->getAllOperations();
        */


    }

    protected function getVirtualPhoneNumber(): array
    {
        $url = 'https://onlinesim.ru/api/getNum.php';

        $requestParams = [
            'query' => [
                'apikey' => '',
                'service' => '7',
            ],
        ];

        try {
            $response = $this->client->get($url, $requestParams);
            $responseContent = $response->getBody()->getContents();

            return json_decode($responseContent, true);
        } catch (ClientException $error) {
            if ($error->hasResponse()) {
                $response = $error->getResponse();
                $body = (string) $response->getBody();

                return ['error' => $body];
            } else {
                return [];
            }
        }
    }

    protected function getAllOperations()
    {
        $url = 'https://onlinesim.ru/api/getState.php';

        $requestParams = [
            'query' => [
                'apikey' => '',
            ],
        ];

        try {
            $response = $this->client->get($url, $requestParams);
            $responseContent = $response->getBody()->getContents();

            return json_decode($responseContent, true);
        } catch (ClientException $error) {
            return [];
        }
    }

    protected function processOperations()
    {

    }

    protected function getFakeEmail(string $name): string
    {
        $domain = [
            '@ya.ru',
            '@yandex.ru',
            '@mail.ru',
            '@bk.ru',
            '@gmail.com',
            '@list.ru',
        ];

        return preg_replace('/\s+/', '.', $this->translit($name)).$domain[array_rand($domain, 1)];
    }

    /**
     * Получаем массив рандомных имен.
     *
     * @param  int  $count
     *
     * @return array
     */
    protected function getRandomNames(int $count = 1): array
    {
        $url = 'http://freegenerator.ru/fio';

        $requestParams = [
            'form_params' => [
                'fam' => 1,
                'imya' => 1,
                'otch' => 0,
                'pol' => 0,
                'count' => $count,
            ]
        ];

        try {
            $response = $this->client->post($url, $requestParams);
            $body = $response->getBody()->getContents();

            $body = trim(str_replace([' <br> ', ' ', '﻿'], ['\n', ' ', ' '], $body));

            return explode('\n', str_replace('  ', ' ', $body));
        } catch (ClientException $error) {
            return [];
        }
    }

    /**
     * Транслитерация.
     *
     * @param  string  $value
     *
     * @return string
     */
    protected function translit(string $value): string
    {
        $value = mb_strtolower($value);

        $dict = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'е', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j',
            'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
            'х' => 'h', 'ч' => 'ch', 'ц' => 'ts', 'ш' => 'sh', 'щ' => 'shсh', 'у' => 'y', 'ь' => '','э' => 'je', 'ю' => 'yu', 'я' => 'ya',
            'кв' => 'q', 'с' => 's', 'це' => 'ce', 'ру' => 'ru', 'ы' => 'yi',
        ];

        return strtr($value, $dict);
    }
}
