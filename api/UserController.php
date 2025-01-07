<?php
require_once './includes/Database.php';
require_once './includes/Response.php';

class UserController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function index() {
        $query = 'SELECT * FROM users';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Response::send(200, $users);
    }

    public function show($id) {
        $query = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            Response::send(200, $user);
        } else {
            Response::send(404, ['message' => 'User not found']);
        }
    }

    public function store($data) {
        $query = 'INSERT INTO users (username, password, role) VALUES (:name, :password, :role)';
        $stmt = $this->conn->prepare($query);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', 'user');

        if ($stmt->execute()) {
            Response::send(201, ['message' => 'User created successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to create user']);
        }
    }

    public function update($id, $data) {
        $query = 'UPDATE users SET username = :name, password = :password WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':price', $password);

        if ($stmt->execute()) {
            Response::send(200, ['message' => 'User updated successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to update user']);
        }
    }

    public function destroy($id) {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            Response::send(200, ['message' => 'User deleted successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to delete user']);
        }
    }
}
