<?php
session_start();
require_once("session.php");
require_once("entity/user.php");
require_once("entity/file.php");
require_once("fileService.php");
$session = new session();
try{
    $session->loadSessionById($_SESSION['session_id']);
}catch(Exception $e){
    http_response_code(401);
    echo json_encode('Session Timed Out');
    exit;
}

$session_global = $session->getGlobal();

header("Access-Control-Allow-Origin: ".HOSTNAME_FULL_URL);
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
    if($session_global['session_user_level'] < 2){
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
        case 'loadFile':
            checkLogin();
            checkAdmin();
            $id = base64_decode($data['id']);
            try{
                $file = new file();
                $file->setId($id);
                $file->load();
                $d = [
                    'name' => $file->getName(),
                    'version' => $file->getVersion(),
                    'needClearance' => $file->getNeedClearance(),
                    'clearanceLevel' => $file->getClearanceLevel(),
                    'categories' => $file->getCategories(),
                    'os' => $file->getOS(),
                    'architecture' => $file->getArchitecture(),
                    'publisher' => $file->getPublisher(),
                    'publisherLink' => $file->getPublisherLink(),
                    'info' => $file->getInformation()
                ];
                http_response_code(200);
                echo json_encode(['message' => "Load Success", 'data' => $d]);
            }catch(Exception $e){
                $error= $e->getMessage();
                http_response_code(400);
                echo json_encode(['message' => $error]);
            }
            break;
        case 'updateFile':
            checkLogin();
            checkAdmin();
            $d = $data['data'];
            $needC = 0;
            if(intval($d['clearanceLevel']) > 0){
                $needC = 1;
            }
            try{
                $file = new file();
                $file->setId($d['id']);
                $file->setName($d['name']);
                $file->setVersion($d['version']);
                $file->setCategories($d['categories']);
                $file->setOS($d['os']);
                $file->setNeedClearance($needC);
                $file->setClearanceLevel(intval($d['clearanceLevel']));
                $file->setArchitecture($d['architecture']);
                $file->setPublisher($d['publisher']);
                $file->setPublisherLink($d['publisherLink']);
                $file->setInformation($d['info']);
                $file->update();
                http_response_code(200);
                echo json_encode(['message' => 'Update Success']);
            }catch(Exception $e){
                http_response_code(400);
                echo json_encode(['message' => 'Update Failed']);
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
            checkLogin();
            checkAdmin();
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
        case 'restoreFile':
            checkLogin();
            checkSystem();
            $id = intval(base64_decode($data['id']));
            $fileService = new FileService();
            $file = new file();
            try{
                $file->setId($id);
                $file->load();
                if($fileService->getFilePath($file->getFileName()) == null){
                    http_response_code(400);
                    exit(json_encode(['message' => "Failed to restore because the real file is not found."]));
                }
                $file->restore();
                http_response_code(200);
                echo json_encode(['message' => 'File restored.']);
            }catch(Exception $e){
                http_response_code(400);
                echo json_encode(['message' => $e->getMessage()]);
            }
            break;
        case 'deleteFile' :
            $id = intval(base64_decode($data['id']));
            $userID = $session_global['session_user_id'];
            $userLevel = $session_global['session_user_level'];
            if($userLevel < 2){
                http_response_code(400);
                           exit(json_encode(['message' => "You don't have the level needed for deleting this file."]));
            }
            $file = new file();
            try{
                $file->setId($id);
                $file->load();
                if($userLevel == 2){
                    if($file->getIdUser() != $userID){
                           http_response_code(400);
                           exit(json_encode(['message' => "Only original uploader and system can delete this file."]));
                    }
                }
                $file->softDelete();
                http_response_code(200);
                echo json_encode(['message' => 'File is deleted, you can restore the file from deleted files menu.']);
            }catch(Exception $e){
                http_response_code(400);
                echo json_encode(['message' => $e->getMessage()]);
            }
            break;
        case 'destroyFile' :
            checkLogin();
            checkSystem();
            $id = intval(base64_decode($data['id']));
            $fileService = new FileService();
            $file = new file();
            try{
                $file->setId($id);
                $file->load();
                $unlink_error = "Real File Deleted";
                if(!$fileService->deleteFile($file->getFileName())){
                    $unlink_error = "Real File failed to be  deleted.";
                }
                $file->delete();
                http_response_code(200);
                echo json_encode(['message' => 'File is destroyed. '.$unlink_error]);
            }catch(Exception $e){
                http_response_code(400);
                echo json_encode(['message' => $e->getMessage()]);
            }
            break;
        case 'validateEdit' :
            checkLogin();
            checkAdmin();
            $id = intval(base64_decode($data['id']));
            try{
                $file = new file();
                $file->setId($id);
                $file->load();
                $owner = $file->getIdUser();
                if($session_global['session_user_level'] == 2){
                    if($session_global['session_user_id'] != $owner){
                        http_response_code(403);
                        exit(json_encode(['message' => "Only original uploader and system can update this file."]));
                    }
                }
                http_response_code(200);
                echo json_encode(['message' => "Validated"]);
            }catch(Exception $e){
                http_response_code(403);
                echo json_encode(['message' => $e->getMessage()]);
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