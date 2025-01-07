<?php

require_once 'api/UserController.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = $uri[1] ?? null;
$id = $uri[2] ?? null;
if ($resource === 'users') {
    $controller = new UserController();

    switch ($requestMethod) {
        case 'GET':
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $controller->store($data);
            break;

        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                $controller->update($id, $data);
            } else {
                Response::send(400, ['message' => 'ID is required']);
            }
            break;

        case 'DELETE':
            if ($id) {
                $controller->destroy($id);
            } else {
                Response::send(400, ['message' => 'ID is required']);
            }
            break;

        default:
            Response::send(405, ['message' => 'Method not allowed']);
            break;
    }
} else {
    Response::send(404, ['message' => 'Resource not found']);
}
