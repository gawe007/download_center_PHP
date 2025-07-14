<?php
require_once("pathResolver.php");
require_once(pathResolver::root()."/bin/config/config.php");
class Connection {
    public static function connect(): mixed {
        try{
            $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            if($conn->connect_error){
                throw new Exception($conn->connect_error. " " .E_USER_ERROR);
                exit();
            }
            return $conn;
        }catch(Exception $e){
            throw new ErrorException("Database Coonection failed. ". $e->getMessage());
        }
    }
}