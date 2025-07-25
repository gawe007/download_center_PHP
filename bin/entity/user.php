<?php
require_once("pathResolver.php");
require_once(pathResolver::root()."/bin/entity/connection.php");

class user{
    protected $conn;
    private $id;
    private $email;
    protected $password;
    private $name;
    private $level;
    private $timestamp;

    private $last_insert_id;

    public function __construct($id){
        $this->id = $id ?? 0;
        $this->email = "";
        $this->password = "";
        $this->name = "";
        $this->level = "";
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

    public function setLevel($l): void{
        $this->level = $l;
    }

    public function load(): void{
        $this->blockZero();
        $sql = "SELECT * FROM user WHERE id = ? LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id);
            if($stmt->execute()){
                $d = $stmt->get_result();
                while($data = $d->fetch_array()){
                    $this->id = $data['id'];
                    $this->email = $data['email'];
                    $this->password = $data['password'];
                    $this->name = $data['name'];
                    $this->level = $data['level'];
                    $this->timestamp = $data['timestamp'];
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function user_check($email, $pass): int{
        $sql = "SELECT * FROM user WHERE email = ? LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($data = $result->fetch_assoc()){
                    $password = $data['password'];
                    if(password_verify($pass, $password)){
                        $stmt->close();
                        return $data['id'];
                    }
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
        return 0;
    }

    public function update(): bool{
        $this->blockZero();
        $sql = "UPDATE user SET email = ?, password = ?, name = ?, level = ? WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssii", $this->email, $this->password, $this->name, $this->level, $this->id);
            if($stmt->execute()){
                $stmt->close();
                return true;
            }
            $stmt->close();
            return false;
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function update_password(): bool{
         $this->blockZero();
        $sql = "UPDATE user SET password = ? WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $this->password, $this->id);
            if($stmt->execute()){
                $stmt->close();
                return true;
            }
            $stmt->close();
            return false;
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function delete(): bool{
        $this->blockZero();
        $sql = "DELETE FROM user WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id);
            if($stmt->execute()){
                $stmt->close();
                return true;
            }
            $stmt->close();
            return false;
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());    
        }
        return false;
    }

    public function save(): void{
        if(!$this->checkEmpty()) die();
        $sql = "INSERT INTO user (email, password, name, level) VALUES (?, ?, ?, ?)";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $this->email, $this->password, $this->name, $this->level);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error . " " . E_USER_ERROR);
            }else{
                $this->last_insert_id = $this->conn->insert_id;
            }
            $stmt->close();
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

    public function getUsers(): array{
        $d = [];
        $sql = "SELECT * FROM user";
        try{
            $stmt = $this->conn->prepare($sql);
            if($stmt->execute()){
                $data = $stmt->get_result();
                while($row= $data->fetch_assoc()){
                    $d[] = $row;
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
        return $d;
    }

    public static function translateLevel($l): string{
        $level[1] = 'Normal';
        $level[2] = 'Admin';
        $level[3] = 'System';
        return is_int($l) ? (isset($level[$l]) ? $level[$l] : 1) : 1; 
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

    public function getLevel(){
        return $this->level;
    }

    public function getTranslatedLevel(){
        return self::translateLevel($this->level);
    }

    public function getTimestamp(){
        return $this->timestamp;
    }
}