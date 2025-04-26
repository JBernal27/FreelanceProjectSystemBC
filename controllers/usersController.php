<?php

require_once(__DIR__ . "/../models/User.php");

class usersController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function GET($user_id = null)
    {
        if ($user_id) {
            $user = $this->userModel->getById($user_id);
            if ($user) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } else {
            $users = $this->userModel->getAll();
            http_response_code(200);
            echo json_encode($users);
        }
    }

    public function POST()
    {
        http_response_code(405);
        return json_encode(["code"=> http_response_code(405),"message" => "Método no permitido, para regitrar un usuario use el método POST en la ruta /users con el metodo POST"]);
    }

    public function DELETE($user_id)
    {
        if ($this->userModel->delete($user_id)) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario eliminado exitosamente"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Usuario no encontrado"]);
        }
    }

    public function PUT($user_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name'], $data['email'], $data['password'])) {
            $result = $this->userModel->update($user_id, $data);
            if ($result) {
                http_response_code(200);
                echo json_encode(["message" => "Usuario actualizado exitosamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al actualizar el usuario"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }
}