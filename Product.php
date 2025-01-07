<?php

class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Product
    public function create($name, $price, $quantity) {
        $sql = "INSERT INTO products (name, price, quantity_available) VALUES (:name, :price, :quantity)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name, 'price' => $price, 'quantity' => $quantity]);
        return $stmt->rowCount();
    }

    // Read All Products
    public function readAll() {
        $sql = "SELECT * FROM products";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update Product
    public function update($id, $name, $price, $quantity) {
        $sql = "UPDATE products SET name = :name, price = :price, quantity_available = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name, 'price' => $price, 'quantity' => $quantity, 'id' => $id]);
        return $stmt->rowCount();
    }

    // Delete Product
    public function delete($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
?>
