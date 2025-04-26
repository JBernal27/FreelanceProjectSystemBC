<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyJWT()
{
    $headers = getallheaders();
    $key = $_ENV['JWT_SECRET'] ?? 'default_secret_key';

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["message" => "No se proporcionó un token"]);
        exit;
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);

    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Token inválido", "error" => $e->getMessage()]);
        exit;
    }
}