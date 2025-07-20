<?php
session_start();
require_once("session.php");
require_once("config/config.php");
require_once("entity/file.php");
require_once("entity/pathResolver.php");
if(!isset($_SESSION['session_id'])){
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

function streamSha256(string $filepath): string|false {
    if (!is_readable($filepath)) return false;

    $context = hash_init('sha256');

    $handle = fopen($filepath, 'rb');
    if (!$handle) return false;

    try {
        while (!feof($handle)) {
            $buffer = fread($handle, 8192); // 8KB chunk
            hash_update($context, $buffer);
        }
    } finally {
        fclose($handle);
    }

    return hash_final($context);
}

$session = new session();
if(!$session->validateSession()){
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$global = $session->getGlobal();

// Config
define('FILE_UPLOAD_DIR', realpath(__DIR__ . '/../files/') . DIRECTORY_SEPARATOR);
define('AUTH_SECRET', $global['auth_token']); // Optional server-side token validation

// Simple token check (replace with real validation logic)
function isTokenValid($token) {
    return base64_decode($token) === AUTH_SECRET;
}

// Auth check
$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : $headers['authorization'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);
if (!isTokenValid($token)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$fileobj = new file();
if (!is_dir(FILE_UPLOAD_DIR)) {
    mkdir(FILE_UPLOAD_DIR, 0777, true); // Use a safe mode in production
}
// Handle request method
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST': // File upload
        if (!isset($_FILES['filepond'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded']);
            exit;
        }

        $file = $_FILES['filepond'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        //$fileobj->setFileName(time());
        $safeName = uniqid('file-') . '.' . strtolower($ext);
        $targetPath = FILE_UPLOAD_DIR . $safeName;
        $mimeType = mime_content_type($file['tmp_name']);

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to move file '.FILE_UPLOAD_DIR]);
            exit;
        }

        $hash = streamSha256($targetPath);    
        echo json_encode(['filename' => $safeName, 'fileextension' => $ext, 'sha256' => $hash, 'mimetype' => $mimeType]);
        break;

    case 'DELETE': // Revert
        // FilePond sends the filename as raw POST body
        $raw = file_get_contents("php://input");
        $filename = basename(trim($raw));

        // Optional: sanitize and validate filename
        $filePath = FILE_UPLOAD_DIR . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            http_response_code(400);
            echo $filePath;
            exit();
        }

        // No need to return anything on revert
        http_response_code(200);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Unsupported method']);
}