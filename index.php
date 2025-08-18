<?php

header('Content-Type: application/json; charset=utf-8');

use API\HTTP\Request;
use API\Router\Router;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$request = Request::Init();

require_once 'src/config/routes.php';
require_once 'src/config/config.php';

$router->dispatch($request->URI(), $request->method());