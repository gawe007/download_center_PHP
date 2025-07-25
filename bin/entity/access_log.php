<?php
require_once("connection.php");

class AcccesLog{
    protected $conn;
    private $id;
    private $id_user;
    private $timestamp;
    private $action;
    private $dbStatus;
    private $dbError;

    public function __construct(){
        $k = new Connection();
        $this->conn = $k::connect();
        $this->id = 0;
        $this->id_user = 0;
        $this->action = "";
        $this->timestamp = "";
        $this->dbStatus = false;
        $this->dbError = "No request Sent";
    }

    public function setId($i){
        $this->id = $i;
    }

    public function setUserId($u){
        $this->id_user = $u;
    }

    public function setAction($a){
        $this->action = strip_tags($a);
    }

    public function getId(){
        return $this->id;
    }

    public function getUserId(){
        return $this->id_user;
    }

    public function getAction(){
        return $this->action;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function getDbStatus(){
        return $this->dbStatus;
    }

    public function getDberror(){
        return $this->dbError;
    }

    private function checkVar(): bool{
        if(empty($this->id_user))return false;
        if(empty($this->action))return false;
        return true;
    }

    public function save(): void{
        $sql = "INSERT INTO access_log (id_user, action) VALUES (?, ?);";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $this->id_user, $this->action);
            $stmt->execute();
            $this->dbStatus = true;
        }catch(Exception $e){
            $this->dbStatus = false;
            $this->dbError = $e->getMessage();
        }finally{
            if(isset($stmt)){
                $stmt->close();
            }
        }
    }

     public function getLogs($draw, $start, $length, $search, $order_col, $order_dir): array {
        $columns = ['id', 'id_user', 'action', 'timestamp'];

        // Validate column index
        $order_col = is_numeric($order_col) && isset($columns[$order_col]) ? (int)$order_col : 0;
        $order_by = $columns[$order_col];

        // Sanitize direction
        $order_dir = strtoupper($order_dir) === 'DESC' ? 'DESC' : 'ASC';

        // Sanitize search input
        $safe_search = $this->conn->real_escape_string($search);
        $where = !empty($safe_search) ? "WHERE action LIKE '%$safe_search%'" : "";

        $order = "ORDER BY $order_by $order_dir";
        $limit = "LIMIT $start, $length";

        // Total records
        $total_sql = "SELECT COUNT(*) as count FROM access_log";
        $total = $this->conn->query($total_sql)->fetch_assoc()['count'] ?? 0;

        // Filtered records
        $filtered_sql = "SELECT COUNT(*) as count FROM access_log $where";
        $filtered = $this->conn->query($filtered_sql)->fetch_assoc()['count'] ?? 0;

        // Fetch paginated data
        $data_sql = "SELECT * FROM access_log $where $order $limit";
        $result = $this->conn->query($data_sql);
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    $row['id'],
                    $row['id_user'],
                    $row['action'],
                    $row['timestamp']
                ];
            }
        }

        return [
            'data' => $data,
            'recordsFiltered' => $filtered,
            'recordsTotal' => $total
        ];
    }
}