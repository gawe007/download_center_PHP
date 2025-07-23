<?php
require_once("bin/session.php");
class app {
    private $login_status;
    private $request;
    private $session;
    public $global;
    public $id;

    public function __construct($request)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->session = new session();
        $this->global = $this->session->getGlobal();
        //$this->session->startBasicSession();
        $this->request = $request ?? null;
    }

    public function checkSession(): void{
        if(!$this->session->validateSession()){
            $this->session->logout();
            $this->loadPage('login');
            exit();
        }
        //$this->session->loadSessionById($_SESSION['session_id']);
        $this->global = $this->session->getGlobal();
    }

    public function checkClearance($clearance): bool{
        if(empty($clearance)){
            return false;
        }
        switch($clearance){
            case 'system':
                if($this->global['session_user_level'] == 3){
                    return true;
                }
                return false;
                exit();
            case 'admin':
                if($this->global['session_user_level'] >= 2){
                    return true;
                }
                return false;
                exit();
            default:
                return false;
        }
    }

    public function handleClearance($clearance): void{
        if(empty($clearance)){
            $this->loadPage('admin');
        }
        if(!$this->checkClearance($clearance)){
            $this->loadPage('admin');
        }
    }

    public function loadPage($page): void{
        $session = $this->session;
        $global = $this->global;
        include("theme/header.php");
        switch($page){
            case 'login' :
                include("theme/login.php");
                break;
            case 'files' :
                include("theme/files.php");
                break;
            case 'admin' :
                $this->checkSession();
                include("theme/admin.php");
                break;
            case 'user-admin' :
                $this->checkSession();
                $this->handleClearance('system');
                include("theme/user-admin.php");
                break;
            case 'data-admin' :
                $this->checkSession();
                $this->handleClearance('admin');
                include("theme/data-admin.php");
                break;
            case 'files-admin':
                $this->checkSession();
                $this->handleClearance('admin');
                include("theme/files-admin.php");
                break;
            case 'file-info':
                include("theme/file-info.php");
                break;
            case 'upload-new-file':
                $this->checkSession();
                $this->handleClearance('admin');
                include("theme/upload-file.php");
                break;
            default :
                include("theme/home.php");
        }
        include("theme/footer.php");
    }

    public function render(): void{
        $this->loadPage($this->request);
    }
}