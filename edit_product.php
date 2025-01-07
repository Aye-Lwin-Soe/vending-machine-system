<?php
session_start();
if ($_SESSION['user_role'] != 'admin') {
    header("Location: /unauthorize");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: products_list.php");
    exit();
}

$product_id = $_GET['id'];

require_once 'ProductsController.php';
$db = new ProductsController();

$product = $db->getProductById($product_id);

if (!$product) {
    header("Location: products_list.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    require_once 'ProductsController.php';
    $db = new ProductsController();
    $db->updateProduct($id, $name, $price, $quantity);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
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
    <?php if (isset($product)) : ?>

        <h1 class="text-center mt-3">Edit Product</h1>
        <div class="container-fluid">
            <div class="row">
                <div class="offset-3 col-md-6">
                    <form action="/product/update" method="POST">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                        <label for="name">Product Name:</label><br>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $product['name']; ?>" required><br><br>

                        <label for="price">Price:</label><br>
                        <input type="number" id="price" name="price" class="form-control" min="0.01" step="0.01" value="<?php echo $product['price']; ?>" required><br><br>

                        <label for="quantity">Quantity Available:</label><br>
                        <input type="number" id="quantity" name="quantity" min="1" class="form-control" value="<?php echo $product['quantity_available']; ?>" required><br><br>

                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    <?php else : ?>
        <p>No product data available.</p>
    <?php endif; ?>
</body>

</html>