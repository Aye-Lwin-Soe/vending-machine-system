<?php

class TestController {
    private $productService;

    public function __construct($productService) {
        $this->productService = $productService;
    }

    public function index() {
        $products = $this->productService->getAllProducts();
        return json_encode($products); 
    }

    public function show($id) {
        $product = $this->productService->getProductById($id);
        return json_encode($product); 
    }
}
