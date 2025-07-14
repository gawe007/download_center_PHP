<?php
require_once("bin/session.php");
class app {
    private $login_status;
    private $request;
    private $session;
    public $id;

    public function __construct($request)
    {
        $this->session = new session();
        $this->request = $request ?? null;
        $this->checkSession();
    }

    public function checkSession(): void{
        if(!$this->session->logged_in()){
            $this->loadPage('login');
            exit();
        }
    }

    public function loadPage($page): void{
        include("theme/header.php");
        switch($page){
            case 'login' :
                include("theme/login.php");
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