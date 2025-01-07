<?php

require_once 'Router.php';
require_once 'ProductController.php';

$router = new Router();
$controller = new ProductController();

$router->addRoute('GET', '/', [$controller, 'index']);               
$router->addRoute('GET', '/product/create', [$controller, 'create']); 
$router->addRoute('POST', '/product/store', [$controller, 'store']);  
$router->addRoute('GET', '/product/edit', [$controller, 'edit']);    
$router->addRoute('POST', '/product/update', [$controller, 'update']); 
$router->addRoute('POST', '/product/delete', [$controller, 'delete']); 
$router->addRoute('GET', '/product/purchase', [$controller, 'purchase']);
$router->addRoute('POST', '/product/processPurchase', [$controller, 'processPurchase']); 
$router->addRoute('GET', '/unauthorize', [$controller, 'checkrole']);
