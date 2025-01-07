<?php
require_once 'vendor/autoload.php'; 

use \Firebase\JWT\JWT;

class JWTUtility {

    private static $secretKey = 'your_secret_key'; 

    public static function generateJWT($userId) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; 
        $payload = [
            'iss' => 'your_issuer', 
            'sub' => $userId,      
            'iat' => $issuedAt,     
            'exp' => $expirationTime
        ];
        
        try {
            return JWT::encode($payload, self::$secretKey, 'HS512');
        } catch (Exception $e) {
            return ['error' => 'Failed to encode JWT: ' . $e->getMessage()];
        }
    }

    public static function validateJWT($jwt) {
        try {
            $decoded = JWT::decode($jwt, self::$secretKey);
            return (array) $decoded;  
        } catch (Exception $e) {
            return null;  
        }
    }
}
