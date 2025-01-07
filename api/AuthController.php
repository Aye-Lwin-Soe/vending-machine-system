<?php
require_once './includes/Database.php';
require_once './includes/JWTUtility.php';
require_once './includes/Response.php';

class AuthController {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function login($data) {  
        $username = $data['username'];
        $password = $data['password'];

        $query = 'SELECT * FROM users WHERE username = :username';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $jwt = JWTUtility::generateJWT($user['id']);
            Response::send(200, $jwt);
        } else {
            return ['message' => 'Invalid username or password'];
        }
    }
}
