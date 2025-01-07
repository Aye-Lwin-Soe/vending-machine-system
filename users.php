<?php
require_once 'CrudOperations.php';

$crud = new CrudOperations();

$userId = $crud->create('users', [
    'username' => 'Alicee',
    'password' => password_hash('mypassword', PASSWORD_BCRYPT),
    'role' => 'user'
]);
echo "User created with ID: $userId\n";


$users = $crud->read('users');
print_r($users);


$crud->update('users', ['username' => 'Alice Updated'], ['id' => $userId]);
echo "User updated successfully.\n";

$crud->delete('users', ['id' => $userId]);
echo "User deleted successfully.\n";

?>