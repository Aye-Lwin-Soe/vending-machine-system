<?php

require_once 'CrudOperations.php';

$crud = new CrudOperations();

$transactionId = $crud->create('transactions', [
    'user_id' => 3,
    'product_id' => 2,
    'quantity' => 3,
    'total_price' => 300.00,
    'transaction_date' => date('Y-m-d H:i:s')
]);
echo "Transaction created with ID: $transactionId\n";

$transactions = $crud->read('transactions');
print_r($transactions);

$crud->update('transactions', ['quantity' => 4], ['id' => $transactionId]);
echo "Transaction updated successfully.\n";

$crud->delete('transactions', ['id' => $transactionId]);
echo "Transaction deleted successfully.\n";
require_once 'CrudOperations.php';

$crud = new CrudOperations();

$transactionId = $crud->create('transactions', [
    'user_id' => 3,
    'product_id' => 2,
    'quantity' => 3,
    'total_price' => 300.00,
    'transaction_date' => date('Y-m-d H:i:s')
]);
echo "Transaction created with ID: $transactionId\n";

$transactions = $crud->read('transactions');
print_r($transactions);

$crud->update('transactions', ['quantity' => 4], ['id' => $transactionId]);
echo "Transaction updated successfully.\n";

$crud->delete('transactions', ['id' => $transactionId]);
echo "Transaction deleted successfully.\n";

