<?php
require_once 'Database.php';
session_start();
class ProductController
{
    public function index()
    {

        require 'products_list.php';
    }

    public function create()
    {
        require 'create_product.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? null;
            $quantity_available = $_POST['quantity'] ?? null;

            if (empty($name)) {
                $_SESSION['error_message'] = 'Product name is required.';
                header("Location: /product/create");
                exit();
            }

            if ($price <= 0) {
                $_SESSION['error_message'] = 'Price must be a positive value.';
                header("Location: /product/create");
                exit();
            }

            if ($quantity_available < 0) {
                $_SESSION['error_message'] = 'Quantity must be a non-negative value.';
                header("Location: /product/create");
                exit();
            }

            $slug = $this->generateSlug($name);

            $db = Database::getInstance()->getConnection();

            $query = "INSERT INTO products (name, price, quantity_available, slug) VALUES (:name, :price, :quantity_available, :slug)";
            $stmt = $db->prepare($query);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity_available', $quantity_available);
            $stmt->bindParam(':slug', $slug);

            try {
                $stmt->execute();
                header("Location: /");
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Failed to add product!";
                header("Location: /product/create");
                exit();
            }
        } else {
            echo "Invalid request method.";
        }
    }

    public function edit()
    {
        if (!isset($_GET['id'])) {
            echo "Product ID is required.";
            return;
        }

        $productId = (int) $_GET['id'];
        try {
            $db = Database::getInstance()->getConnection();

            $query = "SELECT * FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                echo "Product not found.";
                return;
            }

            require 'edit_product.php';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int) $_POST['id'];
            $name = $_POST['name'] ?? null;
            $price = (float) $_POST['price'] ?? null;
            $quantity = (int) $_POST['quantity'] ?? null;

            if (empty($name)) {
                $_SESSION['error_message'] = 'Product name is required.';
                header("Location: /product/edit?id=" . $productId);
                exit();
            }

            if ($price <= 0) {
                $_SESSION['error_message'] = 'Price must be a positive value.';
                header("Location: /product/edit?id=" . $productId);
                exit();
            }

            if ($quantity < 0) {
                $_SESSION['error_message'] = 'Quantity must be a non-negative value.';
                header("Location: /product/edit?id=" . $productId);
                exit();
            }

            $slug = $this->generateSlug($name);

            $db = Database::getInstance()->getConnection();

            $query = "UPDATE products SET name = :name, price = :price, quantity_available = :quantity_available, slug = :slug WHERE id = :id";

            $stmt = $db->prepare($query);

            $stmt->bindParam(':id', $productId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity_available', $quantity);
            $stmt->bindParam(':slug', $slug);

            try {
                $stmt->execute();
                header("Location: /");
            } catch (PDOException $e) {
                $_SESSION['error_message'] = "Failed to update product!";
                header("Location: /product/edit?id=" . $productId);
                exit();
            }
        } else {
            echo "Invalid request.";
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Invalid request method.";
            return;
        }

        if (!isset($_POST['id'])) {
            echo "Product ID is required.";
            return;
        }

        $productId = (int)$_POST['id'];

        try {
            $db = Database::getInstance()->getConnection();

            $query = "DELETE FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Product deleted successfully.";
                header("Location: /");
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to delete product!";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function purchase()
    {
        if (!isset($_GET['id'])) {
            echo "Product ID is required.";
            return;
        }

        $productId = (int) $_GET['id'];
        try {
            $db = Database::getInstance()->getConnection();

            $query = "SELECT * FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                echo "Product not found.";
                return;
            }

            require 'purchase_product.php';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function processPurchase()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Invalid request method.";
            return;
        }

        if (!isset($_POST['user_id']) || !isset($_POST['id']) || !isset($_POST['quantity'])) {
            echo "Missing required purchase data.";
            return;
        }

        $user_id = (int) $_POST['user_id'];
        $product_id = (int) $_POST['id'];
        $quantity = (int) $_POST['quantity'];

        try {
            $db = Database::getInstance()->getConnection();

            $query = "SELECT * FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                echo "Product not found.";
                return;
            }

            if ($product['quantity_available'] < $quantity) {
                echo "Not enough stock available. Only " . $product['quantity_available'] . " items left.";
                return;
            }

            $total_price = $product['price'] * $quantity;

            $new_quantity = $product['quantity_available'] - $quantity;
            $updateQuery = "UPDATE products SET quantity_available = :quantity_available WHERE id = :id";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bindParam(':quantity_available', $new_quantity, PDO::PARAM_INT);
            $updateStmt->bindParam(':id', $product_id, PDO::PARAM_INT);
            $updateStmt->execute();

            $logQuery = "INSERT INTO transactions (user_id, product_id, quantity, total_price, transaction_date)
                         VALUES (:user_id, :product_id, :quantity, :total_price, NOW())";
            $logStmt = $db->prepare($logQuery);
            $logStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $logStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $logStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $logStmt->bindParam(':total_price', $total_price);
            $logStmt->execute();

            header("Location: /");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function generateSlug($string)
    {
        $slug = strtolower($string);

        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        $slug = trim($slug, '-');

        return $slug;
    }

    public function checkrole()
    {
        require 'unauthorize.php';
    }
}
