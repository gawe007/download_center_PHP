<?php
session_start();
require_once("entity/user.php");

class session {
    private $user;

    public function __construct()
    {
        $this->user = new user(null);
    }

    public function login($email, $pass): bool {
        try{
            if($this->user->user_check($email, $pass)){
                return true;
            }
            return false;
        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function newSession($id): void{
        $this->user = new user($id);

        $_SESSION['id'] = $this->user->getId();
        $_SESSION['email'] = $this->user->getEmail();
        $_SESSION['name'] = $this->user->getName();
        $_SESSION['auth_token'] = uniqid($this->user->getId());
    }

    public function logged_in(): bool {
        if(!isset($_SESSION['id']) || empty($_SESSION['id'])) return false;
        return true;
    }

    public function logout(): void {
        session_unset();
        session_destroy();
    }
}