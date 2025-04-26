<?php

require_once(__DIR__ . "/../models/Project.php");

class projectsController
{
    private $projectModel;

    public function __construct()
    {
        $this->projectModel = new Project();
    }

    public function GET($project_id = null)
    {
        if ($project_id) {
            $project = $this->projectModel->getById($project_id);
            if ($project) {
                http_response_code(200);
                echo json_encode($project);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Proyecto no encontrado"]);
            }
        } else {
            $projects = $this->projectModel->getAll();
            http_response_code(200);
            echo json_encode($projects);
        }
    }

    public function POST()
    {
        $user = verifyJWT();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['title'], $data['description'], $data['start_date'], $data['delivery_date'], $data['status'])) {
            $data['user_id'] = $user->user->id;

            $result = $this->projectModel->create($data);
            if ($result) {
                http_response_code(201);
                echo json_encode(["message" => "Proyecto creado exitosamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al crear el proyecto"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }

    public function DELETE($project_id)
    {
        if ($this->projectModel->delete($project_id)) {
            http_response_code(200);
            echo json_encode(["message" => "Proyecto eliminado exitosamente"]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Proyecto no encontrado"]);
        }
    }

    public function PUT($project_id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['title'], $data['description'], $data['start_date'], $data['delivery_date'], $data['status'], $data['user_id'])) {
            $result = $this->projectModel->update($project_id, $data);
            if ($result) {
                http_response_code(200);
                echo json_encode(["message" => "Proyecto actualizado exitosamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al actualizar el proyecto"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }

    public function user()
    {
        $user = verifyJWT();

        $user_id = $user->user->id;

        $projects = $this->projectModel->getByUserId($user_id);
        if ($projects) {
            http_response_code(200);
            echo json_encode($projects);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron proyectos para el usuario especificado"]);
        }
    }

    public function upload($project_id)
    {
        $user = verifyJWT();

        if (!isset($_FILES['file'])) {
            http_response_code(400);
            echo json_encode(["message" => "No se proporcionó un archivo"]);
            return;
        }

        $file = $_FILES['file'];
        $uploadDir = __DIR__ . "/../uploads/projects/$project_id/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $this->projectModel->addFile($project_id, $filePath);

            http_response_code(200);
            echo json_encode(["message" => "Archivo subido exitosamente", "path" => $filePath]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al subir el archivo"]);
        }
    }

    public function files($project_id)
    {
        $user = verifyJWT();

        $files = $this->projectModel->getFiles($project_id);
        if ($files) {
            http_response_code(200);
            echo json_encode($files);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron archivos para este proyecto"]);
        }
    }
}
