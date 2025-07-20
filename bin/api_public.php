<?php
require_once("config/config.php");
require_once("session.php");
require_once("entity/user.php");
$session = new session();

header("Access-Control-Allow-Origin: ".HOSTNAME_FULL_URL);
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$apiError = [
    'status' => FALSE,
    'message' => 'Request Failed.'
];

$apiSuccess = [
    'status' => TRUE,
    'message' => 'Request processed successfully.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//if(true){
    $data = json_decode(file_get_contents("php://input"), true);
    if(!isset($data['action']) || empty($data['action'])){
        http_response_code(400);
        echo json_encode($apiError);
        exit;
    }
    switch ($data['action']) {
        case 'login':
            $d = $data['data'];
            $id = $session->login($d['email'], $d['password']);
            if(!empty($id) && $id != 0){
                $session->newSession($id);
                http_response_code(200);
                $apiSuccess['message'] = 'Login successfull';
                echo json_encode($apiSuccess);
                exit();
            }
            http_response_code(400);
            $apiError['message'] = 'Login failed';
            echo json_encode($apiError);
            exit();
        default:
            http_response_code(400);
            $apiError['message'] = 'Invalid action.';
            echo json_encode($apiError);
            exit();
    }
}else{
    http_response_code(405);
    $apiError['message'] = 'Invalid action.';
    echo json_encode($apiError);
    exit();
}
