<?php
require_once("session.php");

function error($code, $message){
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $message]);
}

function success($data){
    http_response_code(200);
    echo json_encode(['status' => 'success', 'data' => $data]);
}

$session = new session();
if(!$session->logged_in()){
    error(400, 'No Session existed');
}
