<?php
$base_url = '/proyecto2'; 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../models/User.php';
include_once '../config/database.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->email = $data->email;
$user->password = $data->password;

if($user->emailExists()) {
    if(password_verify($user->password, $user->password)) {
        $token = array(
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        );

        http_response_code(200);
        echo json_encode(
            array(
                "message" => "Login successful",
                "token" => $token
            )
        );
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Login failed"));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "User does not exist"));
}

?>