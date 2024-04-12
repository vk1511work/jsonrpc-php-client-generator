<?php

namespace JsonRpcPhpClient\Response;

use JsonRpcPhpClient\Exceptions\Response;
use JsonRpcPhpClient\Response\Error\Error as ClientError;

class Error implements DefaultInterface
{
    public ?string $version = null;
    public ?string $id = null;
    public ?ClientError $error = null;

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
        $this->error = new ClientError($array['error']['code'], $array['error']['message']);

        if ($this->version != "2.0" || empty($this->id) || empty($this->error->getCode()) || empty($this->error->getMessage())) {
            throw new Response();
        }
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getError(): ?ClientError
    {
        return $this->error;
    }

    public function getJson(): string
    {
        return $this->json;
    }

    public function isSuccess(): bool
    {
        return false;
    }
}