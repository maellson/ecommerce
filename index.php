<?php 

//Arquivo de rotas
session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;




header('Content-Type: text/html; charset=utf-8');

$app = new Slim();

$app->config('debug', true);

require_once ("admin_.php");
require_once ("functions.php");
require_once ("site.php");
require_once ("admin-users.php");
require_once ("admin-categories.php");
require_once ("admin-products.php");



$app->run();

 ?>