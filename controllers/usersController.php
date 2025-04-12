<?php

class usersController
{
    public function GET()
    {
        http_response_code(200);
        $response = ["message" => "GET desde users"];
        echo json_encode($response);
    }

    public function POST()
    {
        http_response_code(200);
        $response = ["message" => "POST desde users"];
        echo json_encode($response);
    }

    public function DELETE($user_id)
    {
        http_response_code(200);
        $response = ["message" => "DELETE desde users". $user_id];
        echo json_encode($response);
    }
}
