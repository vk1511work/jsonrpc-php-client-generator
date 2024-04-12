<?php

namespace JsonRpcPhpClient;

use JsonRpcPhpClient\Exceptions\OpenRpc;

class Generator
{
    public ?array $schema = null;
    public string $output = "";
    public string $outputFile = "";
    public function __construct(string $schema, string $outputFile)
    {
        $this->schema = json_decode($schema, true);
        $this->outputFile = $outputFile;
    }

    public function generate()
    {
        if ($this->verify() == false) {
            throw new OpenRpc();
        }
        $this->output .= '<?php

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
        $this->client = new JsonRpcPhpClient\Client(endpoint: "'.$this->schema["servers"][0]["url"].'");
    }';

        $this->output .= $this->generateMethods($this->schema["methods"]);

        $this->output .= "\n}";
        file_put_contents($this->outputFile, $this->output);

    }

    protected function verify(): bool
    {
        return strlen($this->schema["servers"][0]["url"]) > 0
            && sizeof($this->schema["methods"]) > 0;
    }

    protected function generateMethods(array $methods): string
    {
        $prepared = [];
        $methodOut = '';
        foreach ($methods as $method) {
            $item = ["params" => []];
            if (isset($method["name"])) {
                $item["name"] = $method["name"];
            } else {
                throw new OpenRpc("Invalid method name");
            }
            if (isset($method["description"])) {
                $item["description"] = $method["description"];
            }
            if (is_array($method["params"])) {
                foreach ($method["params"] as $param) {
                    if (isset($param["name"]) == false) {
                        throw new OpenRpc("Invalid parameter name");
                    }
                    if (isset($param["schema"]) == false) {
                        throw new OpenRpc("Invalid parameter schema");
                    }
                    $ref = $param["schema"]['$ref'];
                    if (isset($ref)) {
                        $exploded = explode("/", str_replace("#/", "", $ref));
                        $temp = &$this->schema;
                        foreach ($exploded as $key) {
                            $temp = &$temp[$key];
                        }
                        if (isset($temp['type'])) {
                            $param["type"] = $temp['type'] == "boolean" ? "bool" : $temp['type'];
                        } else {
                            throw new OpenRpc('Invalid parameter schema type');
                        }
                    } else {
                        throw new OpenRpc('Invalid parameter schema $ref');
                    }
                    $item["params"][] = $param;
                }
            }
            $prepared[] = $item;
        }
        foreach($prepared as $method) {
            $paramsPhpDoc = [];
            $paramsMethod = [];
            $paramsCall = [];
            foreach($method["params"] as $param) {
                $param["typeMethod"] = isset($param["required"]) && $param["required"] == false ? '?'.$param["type"] : $param["type"];
                $param["typePhpDoc"] = isset($param["required"]) && $param["required"] == false ? $param["type"].'|null' : $param["type"];
                $paramsPhpDoc[] = '    * @param '.$param["typePhpDoc"].' $'.$param["name"].(isset($param["description"]) ? ' '.$param["description"] : '');
                $paramsMethod[] = $param["typeMethod"].' $'.$param["name"];
                $paramsCall[] = '"'.$param["name"].'" => $'.$param["name"];
            }
            $code = "\n\n    /**
    * ".($method["description"] ?? "")."\n".implode("\n", $paramsPhpDoc).'
    * @return \JsonRpcPhpClient\Response\Error|\JsonRpcPhpClient\Response\Success
    */
    public function '.$method["name"].'('.implode(", ", $paramsMethod).')
    {
        return $this->client->post("'.$method["name"].'", ['.implode(", ", $paramsCall).']);
    }';
            $methodOut .= $code;
        }
        return $methodOut;
    }

}