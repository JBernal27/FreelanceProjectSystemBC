<?php

require_once(__DIR__ . "/../models/User.php");
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class authController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login() // login
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['email'], $data['password'])) {
            $user = $this->userModel->getByEmail($data['email']);
            if ($user && password_verify($data['password'], $user['password'])) {

                $key = $_ENV['JWT_SECRET'];

                if(!isset($key)) {
                    http_response_code(500);
                    echo json_encode(["message" => "Error: JWT_SECRET no está configurado"]);
                    return;
                }

                $payload = [
                    "iss" => "FreelanceProjectSystem.com",
                    "aud" => "FreelanceProjectSystem.com",
                    "iat" => time(),
                    "exp" => time() + 86400, // 1 día de expiración
                    "user" => [
                        "id" => $user['id'],
                        "name" => $user['name'],
                        "email" => $user['email']
                    ]
                ];

                $jwt = JWT::encode($payload, $key, 'HS256');

                http_response_code(200);
                echo json_encode([
                    "message" => "Inicio de sesión exitoso",
                    "token" => $jwt,
                    "user" => [
                        "id" => $user['id'],
                        "name" => $user['name'],
                        "email" => $user['email']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Credenciales inválidas"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }

    public function register()//register
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name'], $data['email'], $data['password'])) {
            $existingUser = $this->userModel->getByEmail($data['email']);
            if ($existingUser) {
                http_response_code(409);
                echo json_encode(["message" => "El correo electrónico ya está registrado"]);
                return;
            }

            $result = $this->userModel->create($data);
            if ($result) {
                http_response_code(201);
                echo json_encode(["message" => "Usuario registrado exitosamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al registrar el usuario"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }
}