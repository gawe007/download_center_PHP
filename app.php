<?php
date_default_timezone_set('Asia/Jakarta');
require_once("bin/session.php");
require_once("bin/entity/access_log.php");
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
            $ac = new AcccesLog();
            $ac->setUserId($this->global['session_user_id']);
            $ac->setAction('Session timed out.');
            $ac->save();
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
            case 'admin':
                if($this->global['session_user_level'] >= 2){
                    return true;
                }
                return false;
            default:
                return false;
        }
    }

    public function handleClearance($clearance): void{
        if(empty($clearance) OR !$this->checkClearance($clearance)){
            header("Location: index.php?r=admin"); // or wherever your router points
            exit();
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
            case 'about' :
                include("theme/about.php");
                break;
            case 'license' :
                include("theme/license.php");
                break;
            case 'privacy' :
                include("theme/privacy.php");
                break;
            case 'files' :
                include("theme/files.php");
                break;
            case 'deleted-files':
                $this->checkSession();
                $this->handleClearance('system');
                include("theme/deleted-files.php");
                break;
            case 'profile':
                $this->checkSession();
                include('theme/profile.php');
                break;
            case 'admin' :
                $this->checkSession();
                include("theme/admin.php");
                break;
            case 'terms' :
                include("theme/terms.php");
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