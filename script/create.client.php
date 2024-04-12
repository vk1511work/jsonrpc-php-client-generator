<?php

include_once "JsonRpcPhpClient/Generator.php";
include_once "JsonRpcPhpClient/Exceptions/OpenRpc.php";

$openRpcFile = '';
$clientFile = '';

foreach ($argv as $key => $value) {
    if ($value == "--schema") {
        $openRpcFile = $argv[($key+1)];
    }
    if ($value == "--output") {
        $clientFile = $argv[($key+1)];
    }
}

if (file_exists($clientFile)) {
    die("Output file is already exists: $clientFile\n");
}

if (file_exists($openRpcFile) == false) {
    die("OpenRpc file does not exist: $openRpcFile\n");
}

$generator = new \JsonRpcPhpClient\Generator(schema: file_get_contents($openRpcFile), outputFile: $clientFile);

$generator->generate();