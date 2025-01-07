<?php
session_start();
require_once 'ProductsController.php';
require_once 'check_role.php';


checkRole('admin');
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is required.");
}

$productId = (int)$_GET['id'];
$productsController = new ProductsController();
$product = $productsController->getProductById($productId);

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {
        $_SESSION['error_message'] = "Please enter a valid quantity.";
    } elseif ($quantity > $product['quantity_available']) {
        $_SESSION['error_message'] = "Not enough stock available.";
    } else {
        $userId = $_SESSION['user_id'];
        $success = $productsController->purchaseProduct($userId, $productId, $quantity);

        if ($success) {
            $_SESSION['success_message'] = "Purchase successful!";
            header("Location: products_list.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to process the purchase.";
        }
    }
}

$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="number"],
        input[type="submit"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            width: 100px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php if (isset($product)) : ?>
        <h1>Purchase Product</h1>

        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <div>
            <h4>Product Name: <?php echo htmlspecialchars($product['name']); ?></h4>
            <p>Price: $<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
            <p>Available Quantity: <?php echo htmlspecialchars($product['quantity_available']); ?></p>
        </div>

        <form method="POST" action="/product/processPurchase">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <div class="form-group">
                <label for="quantity">Quantity:</label><br />
                <input type="number" id="quantity" name="quantity" min="1" required>
            </div>

            <input type="submit" value="Purchase">
        </form>
    <?php else : ?>
        <p>No product data available.</p>
    <?php endif; ?>
</body>

</html>