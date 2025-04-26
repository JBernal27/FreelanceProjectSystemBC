<?php
require 'core/core.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Expose-Headers: Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header("Content-Type: application/json");

$requestUri = $_SERVER['REQUEST_URI'] ?? "/";
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'] ?? "/";

$method = $_SERVER["REQUEST_METHOD"] ?? "GET";
$data = preg_split("/\//", $path);

$c = $data[1] ?? null;
$action = $data[2] ?? strtolower($method);
$param = $data[3] ?? null;

if ($c == "api") {
    header("Content-Type: text/html");
    readfile(__DIR__ . "/api-Information.html");
    exit;
}

$publicRoutes = ["auth", "api"];
if (!in_array($c, $publicRoutes)) {
    verifyJWT();
}

if ($c) {
    $controllerFile = "controllers/{$c}Controller.php";

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controllerClass = "{$c}Controller";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (is_numeric($action)) {
                $param = $action;
                $action = strtolower($method);
            }

            if (method_exists($controller, $action)) {
                try {
                    $controller->$action($param);
                } catch (Exception $error) {
                    http_response_code(500);
                    echo json_encode(["message" => "Error interno del servidor", "error" => $error->getMessage()]);
                }
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Método no permitido: $action"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Clase del controlador no encontrada: $controllerClass"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Controlador no encontrado: $c"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "No se especificó un controlador"]);
}
