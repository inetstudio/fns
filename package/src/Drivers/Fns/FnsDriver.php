<?php

namespace InetStudio\Fns\Drivers\Fns;

use stdClass;
use SoapClient;
use Carbon\Carbon;
use SimpleXMLElement;
use InetStudio\Fns\Drivers\Fns\Models\Receipt;
use InetStudio\Fns\Drivers\Fns\Requests\AuthRequest;
use InetStudio\Fns\Drivers\Fns\Models\TemporaryToken;
use InetStudio\Fns\Contracts\Drivers\FnsDriverContract;
//use InetStudio\Fns\Drivers\Fns\Models\GetReceiptResult;
//use InetStudio\Fns\Drivers\Fns\Models\CheckReceiptResult;
use InetStudio\Fns\Drivers\Fns\Requests\GetReceiptRequest;
use InetStudio\Fns\Drivers\Fns\Requests\CheckReceiptRequest;
//use InetStudio\Fns\Drivers\Fns\Responses\GetReceiptResponse;
//use InetStudio\Fns\Drivers\Fns\Responses\CheckReceiptResponse;

class FnsDriver implements FnsDriverContract
{
    protected string $server;

    protected string $user;

    protected string $token;

    protected ?TemporaryToken $temporaryToken = null;

    protected ?SoapClient $client = null;

    public final function __construct()
    {
        $settings = config('services.fns', []);

        $this->server = $settings['url'] ?? '';
        $this->user = $settings['user'] ?? '';
        $this->token = $settings['token'] ?? '';
    }

    public function getTemporaryToken(): TemporaryToken
    {
        if ($this->temporaryToken === null) {
            $client = new SoapClient($this->server.'/open-api/AuthService/0.1?wsdl');

            $message = $this->getRequestMessage(new AuthRequest($this->token));

            $response = $client->__soapCall('GetMessage', [$message]);

            $authResponse = new SimpleXMLElement($response->Message->any);
            $result = $authResponse->children('tns', true)->Result;
            $this->temporaryToken = TemporaryToken::create($result->Token->__toString(), Carbon::createFromTimeString($result->ExpireTime->__toString()));
        }

        return $this->temporaryToken;
    }

    public function checkReceipt(array $params): bool
    {
        $receipt = $this->getReceiptObject($params);
        $messageId = $this->getCheckReceiptMessageId($receipt);

        $client = $this->createClient();

        $object = new stdClass;
        $object->MessageId = $messageId;

        $attempts = 0;
        $result = '';

        while ($result !== 'COMPLETED' && $attempts < 10) {
            $response = $client->__soapCall('GetMessage', [$object]);
            $result = $response->ProcessingStatus;

            if ($result === 'COMPLETED') {
                break;
                //$checkReceiptResponse = new SimpleXMLElement($response->Message->any);
                //$result = $checkReceiptResponse->children('tns', true)->Result;

                //return CheckReceiptResponse::create($response->ProcessingStatus, CheckReceiptResult::create(intval($result->Code->__toString()), $result->Message->__toString()));
            }

            $attempts++;
        }

        return ($result === 'COMPLETED');
        //return CheckReceiptResponse::create($response->ProcessingStatus);
    }

    protected function getCheckReceiptMessageId(Receipt $receipt): string
    {
        $client = $this->createClient();

        $message = $this->getRequestMessage(new CheckReceiptRequest($receipt));
        $response = $client->__soapCall('SendMessage', [$message]);

        return $response->MessageId;
    }

    public function getReceipt(array $params): array
    {
        $receipt = $this->getReceiptObject($params);
        $messageId = $this->getGetReceiptMessageId($receipt);

        $client = $this->createClient();

        $object = new stdClass;
        $object->MessageId = $messageId;

        $attempts = 0;
        $result = '';

        while ($result !== 'COMPLETED' && $attempts < 10) {
            $response = $client->__soapCall('GetMessage', [$object]);

            $result = $response->ProcessingStatus;

            if ($result === 'COMPLETED') {
                $getReceiptResponse = new SimpleXMLElement($response->Message->any);
                $result = $getReceiptResponse->children('tns', true)->Result;
                $code = intval($result->Code->__toString());
                $message = null;
                $receipt = null;

                if ($code == 200) {
                    return json_decode($result->Receipt->__toString());
                } else {
                    //$message = $result->Message->__toString();
                    return [];
                }

                //return GetReceiptResponse::create($response->ProcessingStatus, GetReceiptResult::create($code, $message, $receipt));
            }

            $attempts++;
        }

        return [];
        //return GetReceiptResponse::create($response->ProcessingStatus);
    }

    protected function getGetReceiptMessageId(Receipt $receipt): string
    {
        $client = $this->createClient();

        $message = $this->getRequestMessage(new GetReceiptRequest($receipt));
        $response = $client->__soapCall('SendMessage', [$message]);

        return $response->MessageId;
    }

    protected function createClient(): SoapClient
    {
        if ($this->client === null) {
            if ($this->temporaryToken === null || $this->temporaryToken->getExpireTime() < Carbon::now()) {
                $this->temporaryToken = null;
                $this->getTemporaryToken();
            }

            $this->client = new SoapClient($this->server.'/open-api/ais3/KktService/0.1?wsdl', [
                'stream_context' => stream_context_create([
                    'http' => [
                        'header' => 'FNS-OpenApi-Token: '.$this->temporaryToken->getToken().PHP_EOL.'FNS-OpenApi-UserToken: '.base64_encode($this->user)
                    ]
                ])
            ]);
        }

        return $this->client;
    }

    protected function getRequestMessage($object): stdClass
    {
        $any = new stdClass;
        $any->any = $object;

        $message = new stdClass;
        $message->Message = $any;

        return $message;
    }

    protected function getReceiptObject(array $params): Receipt
    {
        return new Receipt(
            (int) $params['n'],
            Carbon::parse($params['t']),
            (int) $params['s'],
            (int) $params['fn'],
            (int) $params['i'],
            (int) $params['fp']
        );
    }
}
