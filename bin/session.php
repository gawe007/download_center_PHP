<?php
require_once("entity/connection.php");
require_once("entity/user.php");
require_once("config/config.php");
class session {
    private $user;
    private $global;
    private $last_refreshed;
    protected $conn;
    public $last_insert_id;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->user = new user(null);
        $c = new Connection();
        $this->conn = $c->connect();
        $this->last_insert_id = 0;
        $this->last_refreshed = time();
        $this->global = [
            'full_url' => HOSTNAME_FULL_URL,
            'domain' => HOSTNAME_DOMAIN,
            'auth_token' => '',
            'session_id' => $_SESSION['id'] ?? null,
            'session_user_id' => '',
            'session_user_name' => '',
            'session_user_email' => '',
            'session_user_level' => '1',
            'created' => 0,
            'life' => SESSION_TIME_LIFE
        ];
        if(isset($_SESSION['session_id'])){
            $this->loadSessionById($_SESSION['session_id']);
        }
    }

    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($id, $value) {
        $_SESSION['auth_token'] = $value;
        $_SESSION['session_id'] = $id;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        session_destroy();
    }

    public function login($email, $pass): int {
        try{
            $id = $this->user->user_check($email, $pass);
            if(!empty($id) && $id != 0){
                return $id;
            }
            return 0;
        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    private function generateAuthKey(){
        return bin2hex(random_bytes(16));
    }

    public function newSession($id): void{
        $this->user = new user(null);
        $this->user->setId($id);
        $this->user->load();
        $auth_token = self::generateAuthKey();
        $created = time();
        $life = 1800;
        $keep_alive = 1;
        $sql = "INSERT INTO session (auth_token, id_user, created, life, keep_alive) VALUES (?, ?, ?, ?, ?)";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("siiii", $auth_token, $id, $created, $life, $keep_alive);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error . " " . E_USER_ERROR);
            }else{
                self::init();
                self::set($this->conn->insert_id, $auth_token);
                $this->last_insert_id = $this->conn->insert_id;
                $this->global['auth_token'] = $auth_token;
                $this->global['session_id'] = $this->conn->insert_id;
                $this->global['session_user_id'] = $id;
                $this->global['created'] = $created;
                $this->global['life'] = $life;

            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function loadSession($auth_token): mixed{
        $data = [];
        $sql = "SELECT * FROM session WHERE auth_token = ? LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $auth_token);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error . " " . E_USER_ERROR);
            }else{
                $d = $stmt->get_result();
                while($row = $d->fetch_assoc()){
                    $data[] = $row;
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
        return $data;
    }

    public function loadSessionById($id): void{
        $sql = "SELECT * FROM session WHERE id = ? LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $id);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error . " " . E_USER_ERROR);
            }else{
                $d = $stmt->get_result();
                while($row = $d->fetch_array()){
                    $this->user->setId($row['id_user']);
                    $this->user->load();
                    $this->global['auth_token'] = $row['auth_token'];
                    $this->global['session_id'] = $row['id'];
                    $this->global['session_user_id'] = $row['id_user'];
                    $this->global['session_user_name'] = $this->user->getName();
                    $this->global['session_user_level'] = $this->user->getLevel();
                    $this->global['session_user_email'] = $this->user->getEmail();
                    $this->global['created'] = $row['created'];
                    $this->global['life'] = $row['life'];
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function validateSession(): bool {
        //if session id not exist
        if(!isset($_SESSION['session_id'])){
            return false;
        }
        $this->loadSessionById($_SESSION['session_id']);
        //if id not retreived
        if(!isset($this->global['session_id']) || empty($this->global['session_id'])){
            return false;
        }
        //if token not identical
        if($this->global['auth_token'] != $_SESSION['auth_token']){
            return false;
        }
        //if token expired
        if($this->global['created'] + $this->global['life'] < time()){
            $this->logout();
            return false;
        }
        $this->extendLife();
        return true;
    }

    public function refreshSession(): bool{
        $status = false;
        $created = time();
        $new_auth_token = self::generateAuthKey();
        $sql = "UPDATE session SET auth_token = ?, created = ? WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sii", $new_auth_token, $created, $this->global['session_id']);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error);
            }else{
                self::init();
                self::set($this->global['session_id'], $new_auth_token);
                $this->global['auth_token'] = $new_auth_token;
                $status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException("Session refresh failed ".$e->getMessage());
        }
        return $status;
    }

    public function extendLife(): void{
        if(time() - $this->last_refreshed < 600) return;
        $created = time();
        $sql = "UPDATE session SET created = ? WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $created, $this->global['session_id']);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error);
            }else{
                $this->global['created'] = $created;
                $this->last_refreshed = $created;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException("Session refresh failed ".$e->getMessage());
        }
    }

    public function deleteExpiredSession(): bool{             
        $status = false;
        $now = time();
        $sql = "DELETE FROM session WHERE created + life < ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $now);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error);
            }else{
                $status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
        return $status;
    }

    public function deleteCurrentSession(): bool{
        $status = false;
        $sql = "DELETE FROM session WHERE auth_token = ?";
        $auth_token = $_SESSION['auth_token'] ?? null;
        if(empty($auth_token)) return false;
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $auth_token);
            if(!$stmt->execute()){
                throw new ErrorException($stmt->error . " " . E_USER_ERROR);
            }else{
                $status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }
        return $status;
    }

    public function logged_in(): bool {
        return $this->validateSession();
    }

    public function logout(): bool {
        $act = $this->deleteCurrentSession();
        if($act){
            $this->global['auth_token'] = "";
            $this->global['session_id'] = "";
            $this->global['session_user_email'] = "";
            $this->global['session_user_id'] = "";
            $this->global['session_user_level'] = "1";
            $this->global['session_user_name'] = "";
            self::init();
            self::destroy();
            return true;
        }
        return false;
    }

    public function getGlobal(): array{
        return $this->global;
    }
}