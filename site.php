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
    $pageN = (isset($_GET['page']))?(int)$_GET['page']:1;
    
        $category = new Category();
        
        $category->get((int)$idcategory);
        
        $pagination = $category->getProductsPage($pageN);
        
        $pages = [];
        
        for ($i = 1; $i <= $pagination['pages']; $i++) {
            array_push($pages, 
                    [
                        'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
                        'page'=>$i
                    ]);
        }

        $page = new Page();

        $page->setTpl("category",[
            'category' =>$category->getValues(),
            'products'=> $pagination["data"],
            'pages'=>$pages

        ]);
     
     
    }); 
