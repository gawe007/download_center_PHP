<?php
require_once("config/config.php");
require_once("session.php");
require_once("entity/user.php");
require_once("entity/file.php");
require_once("fileService.php");
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
                $glb = $session->getGlobal();
                $url = 'home';
                if($glb['session_user_level'] > 1){
                    $url = 'admin';
                }
                $apiSuccess['message'] = $url;
                http_response_code(200);
                echo json_encode($apiSuccess);
                exit();
            }
            http_response_code(400);
            $apiError['message'] = 'Login failed';
            echo json_encode($apiError);
            exit();
        case 'getFiles':
            $draw = $data['draw'];
            $start = $data['start'];
            $length = $data['length'];
            $search = $data['search']['value'];
            $order_col = $data['order'][0]['column'];
            $order_dir = $data['order'][0]['dir'];
            $file = new file();
            list($dt, $filtered, $total) = $file->getFiles($draw, $start, $length, $search, $order_col, $order_dir);
            $response = [
                "draw" => intval($draw),
                "recordsTotal" => $total,
                "recordsFiltered" => $filtered,
                "data" => $dt,
            ];
            http_response_code(200);
            echo json_encode($response);
            break;
        case 'liveSearch':
            $param = strip_tags($data['param']);
            $file = new file();
            $matches = $file->liveSearchFiles($param);
            http_response_code(200);
            echo json_encode($matches);
            break;
        case 'verifyIntegrity':
            $id = strip_tags(base64_decode($data['id']));
            $file = new file();
            $file->setId($id);
            $file->load();
            if(!$file->getDBstatus()){
             http_response_code(400);
             echo false;
             exit();   
            }
            $fileService = new FileService();
            http_response_code(200);
            $check = $fileService->verifyIntegrity($file->getFileName(), $file->getSha256());
            if($check){
                http_response_code(200);
                echo true;
                exit();
            }
            http_response_code(400);
            echo false;
            break;
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
