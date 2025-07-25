<?php
require_once('pathResolver.php');
require_once(pathResolver::root()."/bin/config/config.php");
require_once('connection.php');
class file{
    protected $conn;
    private $id;
    private $id_user;
    private $extension;
    private $name;
    private $file_name;
    private $file_type;
    private $file_size;
    private $sha256;
    private $categories;
    private $operating_system;
    private $need_clearance;
    private $clearance_level;
    private $version;
    private $publisher;
    private $publisher_link;
    private $information;
    private $architecture;
    private $download_count;
    private $deleted;
    private $timestamp;

    private $db_status;
    private $db_error;
    private $last_insert_id;
    public function __construct(){
        $c = new connection();
        $this->conn = $c::connect();

        $this->id = 0;
        $this->id_user = 0;
        $this->extension= "";
        $this->name = "[default display name]";
        $this->file_name = "";
        $this->file_type = "";
        $this->file_size = 0;
        $this->sha256 = "";
        $this->need_clearance = false;
        $this->clearance_level = 0;
        $this->publisher = "";
        $this->publisher_link = "";
        $this->information = "";
        $this->architecture = "Other";
        $this->deleted = 0;
        $this->timestamp = "";
        $this->db_status = false;
        $this->db_error = "";
        $this->last_insert_id = 0;
    }

    public function setId($i){
        $this->id = $i ?? 0;
    }

    public function setIdUser($iu){
        $this->id_user = $iu ?? 0;
    }

    public function setExtension($x){
        $this->extension =$x;
    }

    public function setName($n){
        $this->name = strip_tags($n);
    }

    public function setFileName($fn){
        $this->file_name = strip_tags($fn);
    }

    public function setFileType($ft){
        $this->file_type = $ft;
    }

    public function setFileSize($fs){
        $this->file_size = $fs;
    }

    public function setSha256($s){
        $this->sha256 = $s;
    }

    public function setCategories($c){
        $this->categories = strip_tags($c);
    }

    public function setOS($os){
        $this->operating_system = strip_tags($os);
    }

    public function setNeedClearance($nc){
        $this->need_clearance = $nc ?? 0;
    }

    public function setClearanceLevel($cl){
        $this->clearance_level = $cl ?? 0;
    }

    public function setVersion($v){
        $this->version = strip_tags($v);
    }

    public function setPublisher($p){
        $this->publisher = strip_tags($p);
    }

    public function setPublisherLink($pl){
        $this->publisher_link = strip_tags($pl);
    }

    public function setInformation($i){
        $this->information = strip_tags($i);
    }

    public function setArchitecture($a){
        $this->architecture = strip_tags($a);
    }

    public function getId(){
        return $this->id;
    }

    public function getIdUser(){
        return $this->id_user;
    }

    public function getExtension(){
        return $this->extension;
    }

    public function getName(){
        return $this->name;
    }

    public function getFileName(){
        return $this->file_name;
    }

    public function getFileType(){
        return $this->file_type;
    }

    public function getFileSize(){
        return $this->file_size;
    }

    public function getSha256(){
        return $this->sha256;
    }

    public function getCategories(){
        return $this->categories;
    }

    public function getOS(){
        return $this->operating_system;
    }

    public function getNeedClearance(){
        return $this->need_clearance;
    }

    public function getClearanceLevel(){
        return $this->clearance_level;
    }

    public function getVersion(){
        return $this->version;
    }

    public function getPublisher(){
        return $this->publisher;
    }

    public function getPublisherLink(){
        return $this->publisher_link;
    }

    public function getInformation(){
        return $this->information;
    }

    public function getArchitecture(){
        return $this->architecture;
    }

    public function getDownloadCount(){
        return $this->download_count;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function getDBstatus(){
        return $this->db_status;
    }

    public function getDBerror(){
        return $this->db_error;
    }

    public function getLastInsertId(){
        return $this->last_insert_id;
    }
    
    public function isSoftDeleted(){
        return $this->deleted;
    }

    public function isValid(): bool{
        $required = [
            $this->id_user,
            $this->extension,
            $this->file_name,
            $this->file_type,
            $this->file_size,
            $this->version,
            $this->sha256
        ];

        foreach ($required as $item) {
            if (empty($item)) {
                return false;
            }
        }

        return true;
    }

    public function save(): void{
        if(!$this->isValid()){
            $this->db_status = false;
            $this->db_error = "file state not valid";
            return;
        }
        $sql = "INSERT INTO `files`(`id_user`, `extension`, `name`, `file_name`, `file_type`, `file_size`, `sha256`, `categories`, `need_clearance`, `clearance_level`, `deleted`, `operating_system`, `version`, `publisher`, `publisher_link`, `information`, `architecture`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issssissiiissssss", $this->id_user, $this->extension, $this->name, $this->file_name, $this->file_type, $this->file_size, $this->sha256, $this->categories, $this->need_clearance, $this->clearance_level, $this->deleted, $this->operating_system, $this->version, $this->publisher, $this->publisher_link, $this->information, $this->architecture);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
            }else{
                $this->db_status = true;
                $this->last_insert_id = $this->conn->insert_id;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function update(){
        if($this->id == 0){ 
            throw new Error('Empty Reference');
            return;
        }
        $sql = "UPDATE `files` SET `name` = ?, `categories`= ?, `need_clearance`= ?, `clearance_level`= ?, `operating_system`= ?, `version`= ?, `publisher`= ?, `publisher_link`= ?, `information`= ?, `architecture`=? WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssiissssssi", $this->name, $this->categories, $this->need_clearance, $this->clearance_level, $this->operating_system, $this->version, $this->publisher, $this->publisher_link, $this->information, $this->architecture, $this->id);
            if(!$stmt->execute()){
                $this->db_status = false;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(){
        if($this->id == 0){ 
            throw new Error('Empty Reference');
            return;
        }
        $sql = "DELETE FROM files WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id);
            if(!$stmt->execute()){
                $this->db_status = false;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function restore(){
        if($this->id == 0){ 
            throw new Error('Empty Reference');
            return;
        }
        $sql = "UPDATE files SET deleted = FALSE WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id);
            if(!$stmt->execute()){
                $this->db_status = false;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function softDelete(): void{
        if($this->id == 0){ 
            throw new Error('Empty Reference');
            return;
        }
        $sql = "UPDATE files SET deleted = TRUE WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id);
            if(!$stmt->execute()){
                $this->db_status = false;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function addDownloadCount(){
        if($this->id == 0){ 
            throw new Error('Empty Reference');
            return;
        }
        $newCount = $this->download_count + 1;
        $sql = "UPDATE files SET downloaded_count = ? WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $newCount, $this->id);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function load(): void{
        if($this->id == 0){ 
            throw new Error('Empty Reference');
            return;
        }
        $sql = "SELECT * FROM files WHERE id = ?";
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $this->id);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
                $d = $stmt->get_result();
                while($row = $d->fetch_array()){
                    $this->id = $row['id'];
                    $this->id_user = $row['id_user'];
                    $this->extension= $row['extension'];
                    $this->name = $row['name'];
                    $this->file_name = $row['file_name'];
                    $this->file_type = $row['file_type'];
                    $this->file_size = $row['file_size'];
                    $this->sha256 = $row['sha256'];
                    $this->version = $row['version'];
                    $this->operating_system = $row['operating_system'];
                    $this->categories = $row['categories'];
                    $this->need_clearance = $row['need_clearance'];
                    $this->clearance_level = $row['clearance_level'];
                    $this->publisher = $row['publisher'];
                    $this->publisher_link = $row['publisher_link'];
                    $this->information = $row['information'];
                    $this->architecture = $row['architecture'];
                    $this->deleted = $row['deleted'] ? true : false;
                    $this->download_count = $row['downloaded_count'];
                    $this->timestamp = $row['timestamp'];
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
    }

    public function getFiles($draw, $start, $length, $search, $order_col, $order_dir): array{
        // Read parameters from DataTables
        $columns = ['id', 'name', 'extension', 'categories', 'operating_system', 'version', 'publisher', 'information', 'architecture', 'downloaded_count']; // your actual table columns
        $order_by = $columns[$order_col];

        // Build query
        $where = !empty($search) ? "AND (name LIKE '%$search%' OR categories LIKE '%$search%')" : "";
        $order = "ORDER BY $order_by $order_dir";
        $limit = "LIMIT $start, $length";

        // Total records
        $total = $this->conn->query("SELECT COUNT(*) as count FROM files")->fetch_assoc()['count'];

        // Filtered records
        $filtered_sql = "SELECT COUNT(*) as count FROM files WHERE deleted = '0' $where";
        $filtered = $this->conn->query($filtered_sql)->fetch_assoc()['count'];

        // Fetch data
        $data_sql = "SELECT * FROM files WHERE deleted = 0 $where $order $limit";
        $result = $this->conn->query($data_sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [$row['id'], $row['name'], $row['extension'], $row['categories'], $row['operating_system'], $row['version'], $row['publisher'], $row['information'], $row['architecture'], $row['downloaded_count']];
        }

        return [$data, $filtered, $total];
    }

    public function liveSearchFiles(string $search, int $limit = 10): array {
        $searchTerm = "%{$search}%";
        $stmt = $this->conn->prepare("
            SELECT id, name, extension, categories 
            FROM files 
            WHERE deleted = 0 AND (name LIKE ? OR categories LIKE ?) 
            ORDER BY name ASC 
            LIMIT ?
        ");
        $stmt->bind_param("ssi", $searchTerm, $searchTerm, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $matches = [];
        while ($row = $result->fetch_assoc()) {
            $matches[] = $row;
        }
        
        return $matches;
    }

    public function getAllFilesNotDeleted(): array{
        $data = [];
        $sql = "SELECT * FROM files WHERE deleted = 0";
        try{
            $stmt = $this->conn->prepare($sql);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
                $d = $stmt->get_result();
                while($row = $d->fetch_assoc()){
                    $data[] = $row;
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
        return $data;
    }

    public function getDeletedFiles(): array{
        $data = [];
        $sql = "SELECT * FROM files WHERE deleted = 1";
        try{
            $stmt = $this->conn->prepare($sql);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
                throw new ErrorException($stmt->error);
            }else{
                $this->db_status = true;
                $d = $stmt->get_result();
                while($row = $d->fetch_assoc()){
                    $data[] = $row;
                }
            }
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
        return $data;
    }

    public function getFilesFavourites(): array{
        $data = [];
        $sql = "SELECT id, name, downloaded_count FROM files ORDER BY downloaded_count DESC LIMIT 5";
        try{
            $stmt = $this->conn->prepare($sql);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
                throw new ErrorException($stmt->error);
            }
            $this->db_status = true;
            $d = $stmt->get_result();
            $data = $d->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
        return $data;
    }

    public function getNewestFile(): array{
        $data = [];
        $sql = "SELECT * FROM files ORDER BY id DESC LIMIT 1";
        try{
            $stmt = $this->conn->prepare($sql);
            if(!$stmt->execute()){
                $this->db_status = false;
                $this->db_error = $stmt->error;
                throw new ErrorException($stmt->error);
            }
                $this->db_status = true;
                $d = $stmt->get_result();
                $data = $d->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }catch (Exception $e) {
            throw $e;
        }
        return $data;
    }

}