<?php
require_once 'CrudOperations.php';

$crud = new CrudOperations();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $role = $_POST['role']; 

    try {
        $userId = $crud->create('users', [
            'username' => $name,
            'password' => $password,
            'role' => $role
        ]);
        echo "User registered successfully with ID: $userId";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .card {
            width: 500px; 
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="card shadow">
        <h4 class="text-center mb-4">Register</h4>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
    </div>
</body>
</html>
