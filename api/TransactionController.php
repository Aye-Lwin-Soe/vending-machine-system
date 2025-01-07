<?php
require_once './includes/Database.php';
require_once './includes/Response.php';

class TransactionController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function index() {
        $query = 'SELECT * FROM transactions';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        Response::send(200, $transactions);
    }

    public function show($id) {
        $query = 'SELECT * FROM transactions WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($transaction) {
            Response::send(200, $transaction);
        } else {
            Response::send(404, ['message' => 'Transaction not found']);
        }
    }

    public function store($data) {
        $query = 'INSERT INTO transactions (user_id, product_id, quantity, total_price, transaction_date) VALUES (:user_id, :product_id, :quantity, :total_price, :transaction_date)';
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->bindParam(':transaction_date', $data['transaction_date']);

        if ($stmt->execute()) {
            Response::send(201, ['message' => 'Transaction created successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to create transaction']);
        }
    }

    public function update($id, $data) {
        $query = 'UPDATE transactions SET user_id = :user_id, product_id = :product_id, quantity=:quantity, total_price=:total_price, transaction_date=:transaction_date WHERE id = :id';
        $stmt = $this->conn->prepare($query);
       
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':total_price', $data['total_price']);
        $stmt->bindParam(':transaction_date', $data['transaction_date']);

        if ($stmt->execute()) {
            Response::send(200, ['message' => 'Transaction updated successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to update transaction']);
        }
    }

    public function destroy($id) {
        $query = 'DELETE FROM transactions WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            Response::send(200, ['message' => 'Transaction deleted successfully']);
        } else {
            Response::send(500, ['message' => 'Failed to delete transaction']);
        }
    }
}
