<?php

require_once 'CrudOperations.php';

$crud = new CrudOperations();

$productId = $crud->create('products', [
    'name' => 'Test Product Three',
    'price' => 20000,
    'quantity_available' => 3,
    'slug' => 'test-product-three',
]);
echo "Product created with ID: $productId\n";

$transactions = $crud->read('products');
print_r($transactions);

$crud->update('products', ['quantity_available' => 4], ['id' => $productId]);
echo "Product updated successfully.\n";

$crud->delete('products', ['id' => $productId]);
echo "Product deleted successfully.\n";


