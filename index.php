<?php 

//Arquivo de rotas
session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;



header('Content-Type: text/html; charset=utf-8');

$app = new Slim();

$app->config('debug', true);

require_once ("admin_.php");
require_once ("admin-users.php");
require_once ("admin-categories.php");
require_once ("admin-products.php");

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

    });


$app->run();

 ?>