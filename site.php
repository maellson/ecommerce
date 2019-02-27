<?php

use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function() {
    
    $products = Product::listAll();
    $p = Product::checkList($products);
    
    //var_dump($p);

    
	$page = new Page();

	$page->setTpl("index", array(
            'products'=> $p
                
        ));

    });
