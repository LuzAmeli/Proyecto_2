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

$user->name = $data->name;
$user->email = $data->email;
$user->password = $data->password;

if($user->create()) {
    http_response_code(201);
    echo json_encode(
        array('message' => 'User Created')
    );
} else {
    http_response_code(400);
    echo json_encode(
        array('message' => 'User Not Created')
    );
}
?>