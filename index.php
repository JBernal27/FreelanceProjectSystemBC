<?php
    header("Content-Type: aplication/json");
    // print_r($_SERVER);
    $path = parse_url($_SERVER['REQUEST_URI'])['path'];
    $method = $_SERVER["REQUEST_METHOD"];
    $data = preg_split("/\//", $path);
    $c = $data[1];
    $c .= "Controller";
    if(file_exists("controllers/$c.php")){
        require_once("controllers/$c.php");
        $c = new $c;
        try{
            call_user_func(array($c, $method), $data[2]);
        }catch (error){
            http_response_code(405);
            $response = ["message" => $error];
            echo json_encode($response);
        }
    }else{
        http_response_code(404);
        $response = ["message" => "no se encontro la entidad: " . $data[1]];
        echo json_encode($response);
    }

?>