<?php

require_once 'Database.php';

class ProductsController {
    private $db;
    private $table = 'products'; 
    private $transactionsTable = 'transactions';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createProduct($name, $price, $quantity_available) {
        $query = "INSERT INTO $this->table (name, price, quantity_available) VALUES (:name, :price, :quantity_available)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity_available', $quantity_available);
        
        if ($stmt->execute()) {
            return "Successfull";
        } else {
            return "Product not found.";
        }
    }

    public function getProducts() {
        $query = "SELECT * FROM $this->table";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($id, $name, $price, $quantity_available) {
        $query = "UPDATE $this->table SET name = :name, price = :price, quantity_available = :quantity_available WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity_available', $quantity_available);
        
        if ($stmt->execute()) {
            return "Successfull";
        } else {
            return "Fail.";
        }
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return "Successfull";
        } else {
            return "Fail";
        }
    }

    public function purchaseProduct($user_id, $product_id, $quantity) {
        $product = $this->getProductById($product_id);

        if ($product) {
            if ($product['quantity_available'] >= $quantity) {
               
                $total_price = $product['price'] * $quantity;

                $new_quantity = $product['quantity_available'] - $quantity;
                $this->updateProductQuantity($product_id, $new_quantity);

                $this->logTransaction($user_id, $product_id, $quantity, $total_price);

                return "Purchase successful. Total price: $" . number_format($total_price, 2);
            } else {
                return "Not enough stock available. Only " . $product['quantity_available'] . " items left.";
            }
        } else {
            return "Product not found.";
        }
    }

    private function updateProductQuantity($product_id, $new_quantity) {
        $query = "UPDATE $this->table SET quantity_available = :quantity_available WHERE id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':quantity_available', $new_quantity);
        $stmt->bindParam(':product_id', $product_id);

        $stmt->execute();
    }

    private function logTransaction($user_id, $product_id, $quantity, $total_price) {
        $query = "INSERT INTO $this->transactionsTable (user_id, product_id, quantity, total_price, transaction_date)
                  VALUES (:user_id, :product_id, :quantity, :total_price, NOW())";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':total_price', $total_price);

        $stmt->execute();
    }
}

 ?>