<?php

namespace JsonRpcPhpClient\Request;

class Single implements DefaultInterface
{
    const VERSION = "2.0";
    public ?string $id = null;
    public string $method;
    public ?array $params = null;

    /**
     * @param string|null $id
     * @param string $method
     * @param array|null $params
     */
    public function __construct(string $method, ?array $params, ?string $id)
    {
        $this->id = $id;
        $this->method = $method;
        $this->params = $params;
    }

    public function json(): string
    {
        return json_encode(
            $this->toArray()
        );
    }

    public function toArray(): array
    {
        $array = [
                "jsonrpc" => self::VERSION,
                "method" => $this->method
            ];
        if (isset($this->id)) {
            $array["id"] = $this->id;
        }
        if (isset($this->params)) {
            $array["params"] = $this->params;
        }
        return $array;
    }

}