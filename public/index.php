<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

// Ruta clientes
require '../src/rutas/routes.php';
//require '../src/rutas/login.php';

$app->run();