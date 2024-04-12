# Jsonrpc Php Client Generator

## Getting started

For generate file with PHP JSON-RPC 2.0 client run script from command line
```bash
php ./script/create.client.php --output ./output/client.php --schema ./schema/onenrpc.json 
```
where:
 * --schema is path to OpenRPC schema file (you can use URL)
 * --output is output file

Than you can include or edit this file to use in your project. For example:
```php
include_once './client.php';

$client = new JsonRpcClient();
//some method from OpenRPC scheme
$client->checkLogin("my_login");
```
## Requirements
 * PHP version 8.0 and higher
 * PHP --with-curl installation