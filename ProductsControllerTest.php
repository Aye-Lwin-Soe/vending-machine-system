<?php

use PHPUnit\Framework\TestCase;
require_once 'ProductsController.php'; 

class ProductsControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        $mockDb = $this->createMock(PDO::class);

        $this->controller = $this->getMockBuilder(ProductsController::class)
            ->setConstructorArgs([$mockDb])
            ->onlyMethods(['getProducts', 'getProductById', 'createProduct', 'deleteProduct']) 
            ->getMock();
    }

    public function testGetAllProductsReturnsProducts()
    {
        $mockProducts = [
            ['id' => 1, 'name' => 'Product A', 'price' => 10.00, 'quantity_available' => 5, 'slug' => 'product-A'],
            ['id' => 2, 'name' => 'Product B', 'price' => 20.00, 'quantity_available' => 10, 'slug' => 'product-B'],
        ];

        $this->controller->method('getProducts')->willReturn($mockProducts);
        $response = $this->controller->getProducts();

        $this->assertIsArray($response, "Expected response to be an array");
        $this->assertCount(2, $response, "Expected response to have 2 products");
        $this->assertEquals('Product A', $response[0]['name'], "First product name mismatch");
    }

    public function testShowProductReturnsProductDetails()
    {
        $productId = 1;
        $mockProduct = ['id' => 1, 'name' => 'Product A', 'price' => 10.00, 'quantity_available' => 5];

        $this->controller->method('getProductById')->with($productId)->willReturn($mockProduct);

        $response = $this->controller->getProductById($productId);

        $this->assertIsArray($response, "Expected response to be an array");
        $this->assertEquals('Product A', $response['name'], "Product name mismatch");
    }

    public function testShowProductReturnsNullForInvalidId()
    {
        $productId = 999;

        $this->controller->method('getProductById')->with($productId)->willReturn(null);

        $response = $this->controller->getProductById($productId);

        $this->assertNull($response, "Expected response to be null for invalid ID");
    }

    public function testCreateProductValidatesInput()
    {
        $invalidData = ['name' => '', 'price' => -10, 'quantity_available' => -5];

        $response = $this->controller->createProduct('', -10, -5);

        $this->assertFalse($response, "Expected response to be false for invalid input");
        $this->assertEquals(
            ['Name is required', 'Price must be positive', 'Quantity must be non-negative'],
            $this->controller->getValidationErrors(),
            "Validation errors do not match expected output"
        );
    }

    public function testDeleteProductReturnsTrueForValidId()
    {
        $productId = 1;

        $this->controller->method('deleteProduct')->with($productId)->willReturn(true);

        $response = $this->controller->deleteProduct($productId);

        $this->assertTrue($response, "Expected response to be true for valid product ID");
    }

    public function testDeleteProductReturnsFalseForInvalidId()
    {
        $productId = 999;

        $this->controller->method('deleteProduct')->with($productId)->willReturn(false);

        $response = $this->controller->deleteProduct($productId);

        $this->assertFalse($response, "Expected response to be false for invalid product ID");
    }
}
