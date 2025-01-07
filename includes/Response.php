<?php

class Response {
    public static function send($status, $data) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
