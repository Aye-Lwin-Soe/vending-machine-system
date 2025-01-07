<?php

class ProductService {
    public function getAllProducts() {
        return [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
        ];
    }

    public function getProductById($id) {
        return ['id' => $id, 'name' => 'Product ' . $id];
    }
}
