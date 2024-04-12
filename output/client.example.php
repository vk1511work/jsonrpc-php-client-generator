<?php

include_once "../JsonRpcPhpClient/Client.php";
include_once "../JsonRpcPhpClient/Exceptions/Response.php";
include_once "../JsonRpcPhpClient/Exceptions/SingleRequest.php";
include_once "../JsonRpcPhpClient/Request/DefaultInterface.php";
include_once "../JsonRpcPhpClient/Request/Single.php";
include_once "../JsonRpcPhpClient/Request/Batch.php";
include_once "../JsonRpcPhpClient/Response/DefaultInterface.php";
include_once "../JsonRpcPhpClient/Response/Success.php";
include_once "../JsonRpcPhpClient/Response/Error.php";
include_once "../JsonRpcPhpClient/Response/Error/Error.php";

class JsonRpcClient
{
    public \JsonRpcPhpClient\Client $client;
    public function __construct()
    {
        $this->client = new JsonRpcPhpClient\Client(endpoint: "https://api.sweb.ru/notAuthorized");
    }

    /**
     * Получение токена
     *
     * @param string $login Логин
     * @param string $password Пароль
     * @return \JsonRpcPhpClient\Response\Error|\JsonRpcPhpClient\Response\Success
     */
    public function getToken(string $login, string $password)
    {
        return $this->client->post("getToken", ["login" => $login, "password" => $password]);
    }
}

$client = new JsonRpcClient();
$result = $client->getToken("aaaa", "bbbb");
var_dump($result->getJson());




