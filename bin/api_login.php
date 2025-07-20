<?php
session_start();
require_once("session.php");
require_once("entity/user.php");
require_once("entity/file.php");
$session = new session();
$session->loadSessionById($_SESSION['session_id']);
$session_global = $session->getGlobal();

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


// Get Authorization header
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if (strpos($authHeader, 'Bearer ') !== 0) {
    http_response_code(401);
    $apiError['message'] = 'HTTPAuth Failed';
    echo json_encode($apiError);
    exit;
}

$token = str_replace("Bearer ", "", $authHeader);
$s = $session->loadSession(base64_decode($token));
if(count($s) < 1){
    http_response_code(403);
    $apiError['message'] = 'Token Missing';
    echo json_encode($apiError);
    exit;
}


if (base64_decode($token) !== $session_global['auth_token']) {
    http_response_code(403);
    $apiError['message'] = 'Token Mismacth';
    echo json_encode($apiError);
    exit;
}

function checkLogin(){
    global $session, $apiError;
    if(!$session->logged_in()){
                http_response_code(400);
                $apiError['message'] = 'Invalid session.'. $_SESSION['session_id'];
                echo json_encode($apiError);
                exit;
            }
}

function checkAdmin(){
    global $session_global, $apiError;
    if($session_global['session_user_level'] <= 1){
                http_response_code(400);
                $apiError['message'] = 'Insuficient Leverage.';
                echo json_encode($apiError);
                exit;
            }
}

function checkSystem(){
    global $session_global, $apiError;
    if($session_global['session_user_level'] <= 2){
                http_response_code(400);
                $apiError['message'] = 'Insuficient Leverage.';
                echo json_encode($apiError);
                exit;
            }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//if(true){
    $data = json_decode(file_get_contents("php://input"), true);
    if(!isset($data['action']) || empty($data['action'])){
        http_response_code(400);
        echo json_encode($apiError);
        exit;
    }
    switch ($data['action']) {
        case 'logout':
            checkLogin();
            $session->logout();
            http_response_code(200);
            echo json_encode($apiSuccess);
            break;
        case 'update_user':
            if(!$session->logged_in()){
                http_response_code(400);
                $apiError['message'] = 'Invalid action.';
                echo json_encode($apiError);
                exit;
            }
            $d = $data['data'];
            if($session_global['session_user_level'] != 3){
                http_response_code(400);
                $apiError['message'] = 'Not sufficient clearance.';
                echo json_encode($apiError);
                exit;
            }
            try{
                $user = new user(null);
                $user->setId($d['id']);
                $user->load();
                $user->setLevel($d['level']);
                $user->setEmail($d['email']);
                $user->setName($d['name']);
                $user->update();
                $status = true;
                $error = "";
                http_response_code(200);
                echo json_encode(['status' => $status, 'message' => $error]);
            }catch (Exception $e){
                $status = false;
                $error= $e->getMessage();
                http_response_code(400);
                echo json_encode(['status' => $status, 'message' => $error]);
            }
            break;
        case 'update_user_password':
            checkLogin();
            $d = $data['data'];
            checkSystem();
            try{
                $user = new user(null);
                $user->setId($d['id']);
                $user->load();
                $user->setPassword($d['password']);
                if($user->update_password()){
                    $status = true;
                    $error = "";
                    http_response_code(200);
                    echo json_encode(['status' => $status, 'message' => $error]);
                }
            }catch(Exception $e){
                $status = false;
                $error= $e->getMessage();
                http_response_code(400);
                echo json_encode(['status' => $status, 'message' => $error]);
            }
            break;
        case 'delete_user':
            checkLogin();
            $d = $data['data'];
            checkSystem();
            try{
                $user = new user(null);
                $user->setId($d['id']);
                $user->load();
                $user_level = $user->getLevel();
                if($user_level == '3' || $user_level == 3){
                    $status = false;
                    $error= "System user can only be deleted via database";
                    http_response_code(400);
                    echo json_encode(['status' => $status, 'message' => $error]);
                    exit();
                }
                $c = $user->user_check($session_global['email'], $d['password']);
                if($c == $session_global['session_user_id']){
                    $status = $user->delete();
                    if($status){
                        $error = "";
                        http_response_code(200);
                        echo json_encode(['status' => $status, 'message' => $error]);
                        exit();
                    }else{
                        $error= "failed";
                        http_response_code(400);
                        echo json_encode(['status' => $status, 'message' => $error]);
                    }
                }
            }catch(Exception $e){
                $status = false;
                $error= $e->getMessage();
                http_response_code(400);
                echo json_encode(['status' => $status, 'message' => $error]);
            }
            break;
        case 'add_user':
            checkLogin();
            $d = $data['data'];
            checkSystem();
            try{
                $user = new user(null);
                $user->setId($d['id']);
                $user->load();
                $user->setLevel($d['level']);
                $user->setEmail($d['email']);
                $user->setName($d['name']);
                $user->setPassword($d['password']);
                $user->save();
                $status = true;
                $error = "";
                http_response_code(200);
                echo json_encode(['status' => $status, 'message' => $error]);
            }catch (Exception $e){
                $status = false;
                $error= $e->getMessage();
                http_response_code(400);
                echo json_encode(['status' => $status, 'message' => $error]);
            }
            break;
        case 'add_file':
            try{
                $d = $data['data'];
                $file = new file();
                $file->setIdUser($session_global['session_user_id']);
                $file->setName($d['name']);
                $file->setExtension($d['extension']);
                $file->setFileName($d['actualName']);
                $file->setFileSize($d['size']);
                $file->setFileType($d['type']);
                $file->setOS($d['operating_system']);
                $file->setArchitecture($d['architecture']);
                $file->setSha256($d['sha256']);
                $file->setCategories($d['categories']);
                $file->setVersion($d['version']);
                if(isset($d['clearance']) && !empty($d['clearance'])){
                    if($d['clearance'] != 0){
                         $file->setNeedClearance(1);
                         $file->setClearanceLevel($d['clearance']);
                    }
                }
                $file->setInformation($d['information']);
                $file->setPublisher($d['publisher']);
                $file->setPublisherLink($d['publisher_link']);
                $file->save();
                if($file->getDBstatus()){
                    $status = true;
                    $error = "";
                    http_response_code(200);
                    echo json_encode(['status' => $status, 'message' => $error]);
                    exit();
                }else{
                    $error = 'Failed saving File '.$file->getDBerror();
                    http_response_code(400);
                    echo json_encode(['status' => FALSE, 'message' => $error]);
                    exit();
                }
            }catch(Exception $e){
                $error = $e->getMessage();
                http_response_code(400);
                echo json_encode(['status' => $status, 'message' => $error]);
            }
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