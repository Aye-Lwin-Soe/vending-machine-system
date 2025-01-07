<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    var_dump($_SESSION['user_role']);
    header("Location: /unauthorize");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    require_once 'ProductsController.php';
    $db = new ProductsController();
    $db->createProduct($name, $price, $quantity);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .alert {

            border: 1px solid transparent;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo "<div class='row'><div class='offset-3 col-md-6 alert alert-error text-center'>" . $_SESSION['error_message'] . "</div></div>";
        unset($_SESSION['error_message']);
        session_write_close();
    }
    ?>
    <h1 class="text-center mt-3">Create a New Product</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="offset-3 col-md-6">
                <form action="/product/store" method="POST" onsubmit="return validateForm()">
                    <label for="name">Product Name:</label><br>
                    <input type="text" id="name" name="name" class="form-control" required><br><br>
                    <?php if (isset($errors['name'])): ?>
            <span style="color:red;"><?php echo $errors['name']; ?></span>
        <?php endif; ?>

                    <label for="price">Price:</label><br>
                    <input type="number" id="price" name="price" min="0.01" step="0.01" class="form-control" required><br><br>
                    <?php if (isset($errors['price'])): ?>
            <span style="color:red;"><?php echo $errors['price']; ?></span>
        <?php endif; ?>

                    <label for="quantity">Quantity Available:</label><br>
                    <input type="number" id="quantity" name="quantity" class="form-control" min="0" required><br><br>
                    <?php if (isset($errors['quantity'])): ?>
            <span style="color:red;"><?php echo $errors['quantity']; ?></span>
        <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var name = document.getElementById('name').value;
            var price = parseFloat(document.getElementById('price').value);
            var quantity = parseInt(document.getElementById('quantity').value);

            if (!name || price <= 0 || quantity < 0) {
                alert('Please ensure all fields are filled correctly. Price must be positive, and quantity cannot be negative.');
                return false;
            }

            return true;
        }
    </script>

</body>

</html>