<?php
require_once 'TestController.php'; 

class ProductControllerTest {

    private $controller;
    private $mockProductService;

    public function setUp() {
        $this->mockProductService = new class {
            public $mockGetAllProducts;
            public $mockGetProductById;

            public function getAllProducts() {
                if (is_callable($this->mockGetAllProducts)) {
                    return call_user_func($this->mockGetAllProducts);
                }
                return [];
            }

            public function getProductById($id) {
                if (is_callable($this->mockGetProductById)) {
                    return call_user_func($this->mockGetProductById, $id);
                }
                return null;
            }
        };

        $this->controller = new TestController($this->mockProductService);
    }

    public function testIndexReturnsProducts() {
        $this->mockProductService->mockGetAllProducts = function () {
            return [
                ['id' => 1, 'name' => 'Product 1'],
                ['id' => 2, 'name' => 'Product 2']
            ];
        };

        $response = $this->controller->index();

        $expectedResponse = '[{"id":1,"name":"Product 1"},{"id":2,"name":"Product 2"}]';
        $this->assertEquals($expectedResponse, $response);
    }

    public function testShowReturnsSingleProduct() {
        $productId = 1;

        $this->mockProductService->mockGetProductById = function ($id) {
            return ['id' => $id, 'name' => 'Product ' . $id];
        };

        $response = $this->controller->show($productId);

        $expectedResponse = '{"id":1,"name":"Product 1"}';
        $this->assertEquals($expectedResponse, $response);
    }

    private function assertEquals($expected, $actual) {
        if ($expected === $actual) {
            echo "Test passed\n";
        } else {
            echo "Test failed\nExpected: $expected\nGot: $actual\n";
        }
    }
}

$test = new ProductControllerTest();
$test->setUp();
$test->testIndexReturnsProducts();
$test->testShowReturnsSingleProduct();
