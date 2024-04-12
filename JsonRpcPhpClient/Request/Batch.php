<?php

namespace JsonRpcPhpClient\Request;

use JsonRpcPhpClient\Exceptions\SingleRequest;

class Batch implements DefaultInterface
{
    public ?array $data = null;

    public function __construct(...$data)
    {
        foreach ($data as $item) {
            if (get_class($item) == 'JsonRpcPhpClient\Request\Single') {
                $this->data[] = $item;
            } else {
                throw new SingleRequest();
            }
        }
    }

    public function json(): string
    {
        $array = [];
        if (is_array($this->data)) {
            /**
             * @var Single $item
             */
            foreach ($this->data as $item) {
                $array[] = $item->toArray();
            }
        }
        return json_encode($array);
    }
}