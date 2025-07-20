<?php
session_start();
require_once("session.php");
require_once("entity/user.php");
require_once("entity/file.php");
require_once("fileService.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Parse incoming POST data
$data = json_decode(file_get_contents('php://input'), true);
$fileID = $data['fileID'] ?? null;
if(!$fileID || $fileID == null) {
    if(!$file->getDBstatus()){
    http_response_code(400);
    exit('Internal server error. '.$file->getDBerror());
}
}
$file = new file();
$file->setId($fileID);
$file->load();
if(!$file->getDBstatus()){
    http_response_code(400);
    exit('Internal server error. '.$file->getDBerror());
}

$fileName = $file->getFileName();
$fileService = new FileService();
$filePath = $fileService->getFilePath($fileName);
if(!$filePath || $filePath == null){
    http_response_code(400);
    exit('File Not Found : '.$fileID);
}


if($file->getNeedClearance()){
    $session = new session();
    $session->loadSessionById($_SESSION['session_id']);
    $global = $session->getGlobal();
    $headers = getallheaders();
    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

    if (strpos($authHeader, 'Bearer ') !== 0) {
        http_response_code(401);
        exit("Http auth failed");
    }

    $token = str_replace("Bearer ", "", $authHeader);
    if(!isset($global) || empty($global) || empty($global['auth_token'])){
        http_response_code(401);
        exit("Session failed to load");
    }


    if (base64_decode($token) !== $global['auth_token']) {
        http_response_code(400);
        exit("Token mismatch");
    }
        $file_cleareance = $file->getClearanceLevel();
        $request_clearance = $global['session_user_level'];
        if($file_cleareance > $request_clearance){
            http_response_code(403);
            exit('Unauthorized');
        }

    }
$file->addDownloadCount();
// Required headers for file download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// Clean any previous output
ob_clean();
flush();

// Stream file in chunks
$chunkSize = 8 * 1024 * 1024; // 1MB
$handle = fopen($filePath, 'rb');

while (!feof($handle)) {
    echo fread($handle, $chunkSize);
    flush(); // Push to browser
}

fclose($handle);
exit;
