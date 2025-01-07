<?php

require_once 'api/AuthController.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $uri[1] ?? null;

    $controller = new AuthController();

    switch ($requestMethod) {
    

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->login($data);
            break;

        

        default:
            Response::send(405, ['message' => 'Method not allowed']);
            break;
    }
