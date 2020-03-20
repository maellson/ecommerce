<?php

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;

$app->get('/', function() {
    
    $products = Product::listAll();
    $p = Product::checkList($products);
    
    //var_dump($p);

    
	$page = new Page();

	$page->setTpl("index", array(
            'products'=> $p
                
        ));

    });
    
$app->get("/categories/:idcategory", function($idcategory){
    
     $category = new Category();
     $category->get((int)$idcategory);
      
    $page = new Page();

	$page->setTpl("category",[
            'category' =>$category->getValues(),
            'products'=> Product::checkList($category->getProducts())
                
        ]);
     
     
    }); 
