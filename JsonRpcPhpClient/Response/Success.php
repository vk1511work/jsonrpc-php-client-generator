<?php

namespace JsonRpcPhpClient\Response;

use JsonRpcPhpClient\Exceptions\Response;

class Success implements DefaultInterface
{
    public string $id;
    public string $version;
    public $result;

    public string $json;

    public function __construct(string $json)
    {
        $array = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Response();
        }
        $this->json = $json;
        $this->id = $array['id'];
        $this->version = $array['jsonrpc'];
        $this->result = $array['result'];

        if ($this->version != "2.0" || empty($this->id)) {
            throw new Response();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function getJson(): string
    {
        return $this->json;
    }
    public function isSuccess(): bool
    {
        return true;
    }

}