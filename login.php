<?php 
session_start();
require_once 'CrudOperations.php';

$crud = new CrudOperations();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $crud->read('users', ['username' => $username]);

    if ($user && password_verify($password, $user[0]['password'])) {
        $_SESSION['user_id'] = $user[0]['id'];
        $_SESSION['user_name'] = $user[0]['username'];
        $_SESSION['user_role'] = $user[0]['role'];

        if ($user[0]['role'] === 'admin') {
            header('Location: /');
        } else {
            header('Location: userdashboard.php');
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid username or password!";
    }
}

?>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
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
<body>
<?php
    if (isset($_SESSION['success_message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']); 
    }
    if (isset($_SESSION['error_message'])) {
        echo "<div class='row'><div class='offset-3 col-md-6 alert alert-error text-center'>" . $_SESSION['error_message'] . "</div></div>";
        unset($_SESSION['error_message']); 
        session_write_close();
    }
    ?>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-5" style="width: 400px;">
            <div class="card-body">
                <form method="POST" action="#">
                    <div class="form-group">
                        <label for="username">User Name:</label>
                        <input type="text" id="username" name="username" class="form-control mt-2" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control mt-2" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
