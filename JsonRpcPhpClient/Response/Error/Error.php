<?php

namespace JsonRpcPhpClient\Response\Error;

class Error
{
    public ?int $code = null;
    public ?string $message = null;

    /**
     * @param int|null $code
     * @param string|null $message
     */
    public function __construct(?int $code, ?string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }


}