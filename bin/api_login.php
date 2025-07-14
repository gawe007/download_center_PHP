<?php
require_once("session.php");
$session = new session();

header("Access-Control-Allow-Origin: *");
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
            if($id){
                $session->newSession($id);
                http_response_code(200);
                echo json_encode($apiSuccess);
            }else{
                http_response_code(400);
                echo json_encode($apiError);
            }
            break;
        case 'logout':
            if(!$session->logged_in()){
                http_response_code(400);
                $apiError['message'] = 'Invalid action.';
                echo json_encode($apiError);
                exit;
            }
            $session->logout();
            http_response_code(200);
            echo json_encode($apiSuccess);
            break;
        default:
            http_response_code(400);
            $apiError['message'] = 'Invalid action.';
            echo json_encode($apiError);
            exit;
    }
        
}else{
    http_response_code(405);
    $apiError['message'] = 'Method not allowed. Only POST requests are accepted.';
    echo json_encode($apiError);
    exit;
}