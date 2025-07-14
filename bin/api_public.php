<?php
function error($code, $message){
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $message]);
}

function success($data){
    http_response_code(200);
    echo json_encode(['status' => 'success', 'data' => $data]);
}
