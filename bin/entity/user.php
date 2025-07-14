<?php
require_once("connection.php");

class user{
    protected $conn;
    private $id;
    private $email;
    protected $password;
    private $name;
    private $timestamp;

    private $last_insert_id;

    public function __construct($id){
        $this->id = $id ?? 0;
        $this->email = "";
        $this->password = "";
        $this->name = "";
        $this->timestamp = "";

        $this->last_insert_id = 0;

        $this->conn = new stdClass();
        try {
            $c = new Connection();
            $this->conn = $c::connect();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
            exit();
        }
    }

    public function setId($id): void{
        $this->id = $id;
    }

    public function setEmail($e): void{
        $this->email = strip_tags($e);
    }

    public function setPassword($p): void{
        $this->password = self::hashPassword($p);
    }

    public static function hashPassword($p): string{
        return password_hash($p, PASSWORD_BCRYPT);
    }

    public function setName($n): void{
        $this->name = strip_tags($n);
    }

    public function load(): void{
        $this->blockZero();
        $sql = "SELECT * FROM user WHERE id = ? LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("i", $this->id);
            if($stmt->execute){
                while($data = $stmt->get_result()->fetch_array()){
                    $this->id = $data['id'];
                    $this->email = $data['email'];
                    $this->password = $data['password'];
                    $this->name = $data['name'];
                    $this->timestamp = $data['timestamp'];
                }
            }
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function user_check($email, $pass): bool{
        $sql = "SELECT password FROM user WHERE email = ? LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("s", $email);
            if($stmt->execute){
                while($data = $stmt->get_result()->fetch_array()){
                    $password = $data['password'];
                }

                if(password_verify(self::hashPassword($pass), $password)){
                    return true;
                }
            }
            return false;
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function update(): bool{
        $this->blockZero();
        $sql = "UPDATE user SET email = ?, password = ?, name = ?, WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("sssi", $this->email, $this->password, $this->name, $this->id);
            if($stmt->execute){
                return true;
            }
            return false;
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function delete(): bool{
        $this->blockZero();
        $sql = "DELETE FROM  user WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("i", $this->id);
            if($stmt->execute){
                return true;
            }
            return false;
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function save(): void{
        $this->blockZero();
        if(!$this->checkEmpty()) die();
        $sql = "INSERT INTO user (email, password, name) VALUES (?, ?, ?)";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("sss", $this->email, $this->password, $this->name);
            if(!$stmt->execute){
                throw new ErrorException($stmt->error . " " . E_USER_ERROR);
            }else{
                $this->last_insert_id = $this->conn->insert_id;
            }
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function checkEmpty(): bool{
        if(empty($this->email) || empty($this->password) || empty($this->name)) return false;
        return true;
    }

    public function blockZero(): void{
        if($this->id == 0){
            throw new ErrorException("User error");
            exit();
        }
    }

    public function getId(){
        return $this->id;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getName(){
        return $this->name;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }
}