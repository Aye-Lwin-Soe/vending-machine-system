<?php
require_once './includes/Database.php';
require_once './includes/Response.php';

require_once './includes/JWTUtility';
class ProductController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function index() { 
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $arr = explode(" ", $headers['Authorization']);
           
            if (count($arr) == 2) {
                $token = $arr[1]; 
            }
        } 

        if ($token) {
            $userData = JWTUtility::validateJWT($token);
            if ($userData) {
                $query = 'SELECT * FROM products';
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                Response::send(200, $products);
            } else {
                return ['status' => 401, 'message' => 'Unauthorized'];
            }
        } else {
            return ['status' => 400, 'message' => 'Token not provided'];
        }
    }

    public function show($id) {
        $query = 'SELECT * FROM products WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            Response::send(200, $product);
        } else {
            Response::send(404, ['message' => 'Product not found']);
        }
    }

    public function store($data) {
        $query = 'INSERT INTO products (name, price, quantity_available, slug) VALUES (:name, :price, :qty, :slug)';
        $stmt = $this->conn->prepare($query);
        $slug = strtolower($data['name']);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':qty', $data['qty']);
        $stmt->bindParam(':slug', $slug);

        if ($stmt->execute()) {
            Response::send(201, ['message' => 'Product created successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to create product']);
        }
    }

    public function update($id, $data) {
        $query = 'UPDATE products SET name = :name, price = :price, quantity_available=:qty, slug=:slug WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $slug = strtolower($data['name']);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':qty', $data['qty']);
        $stmt->bindParam(':slug', $slug);

        if ($stmt->execute()) {
            Response::send(200, ['message' => 'Product updated successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to update product']);
        }
    }

    public function destroy($id) {
        $query = 'DELETE FROM products WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            Response::send(200, ['message' => 'Product deleted successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to delete product']);
        }
    }
}
