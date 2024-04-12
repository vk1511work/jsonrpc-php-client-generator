<?php

include_once "./JsonRpcPhpClient/Client.php";
include_once "./JsonRpcPhpClient/Exceptions/Response.php";
include_once "./JsonRpcPhpClient/Exceptions/SingleRequest.php";
include_once "./JsonRpcPhpClient/Request/DefaultInterface.php";
include_once "./JsonRpcPhpClient/Request/Single.php";
include_once "./JsonRpcPhpClient/Request/Batch.php";
include_once "./JsonRpcPhpClient/Response/DefaultInterface.php";
include_once "./JsonRpcPhpClient/Response/Success.php";
include_once "./JsonRpcPhpClient/Response/Error.php";
include_once "./JsonRpcPhpClient/Response/Error/Error.php";

class JsonRpcClient
{
    public \JsonRpcPhpClient\Client $client;
    public function __construct()
    {
        $this->client = new JsonRpcPhpClient\Client(endpoint: "https://api.sweb.ru/notAthorized");
    }

    /**
    * Получение нового токена для авторизации
    * @param string $login Логин
    * @param string|null $password Пароль
    * @return \JsonRpcPhpClient\Response\Error|\JsonRpcPhpClient\Response\Success
    */
    public function getToken(string $login, ?string $password)
    {
        return $this->client->post("getToken", ["login" => $login, "password" => $password]);
    }

    /**
    * Проверка доступности логина для регистрации
    * @param string $login Желаемый логин пользователя
    * @return \JsonRpcPhpClient\Response\Error|\JsonRpcPhpClient\Response\Success
    */
    public function checkLogin(string $login)
    {
        return $this->client->post("checkLogin", ["login" => $login]);
    }
}