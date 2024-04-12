<?php

namespace JsonRpcPhpClient;

use JsonRpcPhpClient\Response\DefaultInterface;
use JsonRpcPhpClient\Response\Error;
use JsonRpcPhpClient\Response\Success;

class Client
{
    public \CurlHandle $ch;
    public string $endpoint;
    public array $headers = [
        'Content-Type: application/json; charset=utf-8',
        'Accept: application/json'
    ];

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        $this->ch = $ch;
    }

    public function post(string $method, ?array $params = null): Success|Error
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode([
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params
        ]));
        $json = curl_exec($this->ch);
        $result = json_decode($json, true);
        curl_close($this->ch);
        return isset($result['error']) ? new Error($json) : new Success($json);
    }
}