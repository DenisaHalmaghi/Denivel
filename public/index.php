<?php

use App\Framework\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application(dirname(__DIR__));

echo $response = $app->processRequest();

//TODO: send back the response
