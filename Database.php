<?php 
// Include the config file to get database constants
require_once "config.php";

class Database {
    private $server_name;
    private $db_name;
    private $user_name;
    private $password;
    public $con;
    
    public function __construct() {
        $this->server_name = HOST_DB;
        $this->db_name = NAME_DB;
        $this->user_name = USER_DB;
        $this->password = PASS_DB;
        
        $this->con = $this->db_connection($this->server_name, $this->user_name, $this->password, $this->db_name);
        if (!$this->con) {
            die("Database connection failed: Unable to connect to the database server.");
        }
    }
    
    private function db_connection($srvr_nm, $usr_nm, $psswrd, $db_nm) {
        try {
            $conn = new mysqli($srvr_nm, $usr_nm, $psswrd, $db_nm);
            if ($conn->connect_error) {
                error_log("Database connection failed: " . $conn->connect_error);
                return false;
            }
            return $conn;
        } catch (Exception $e) {
            error_log("Database connection exception: " . $e->getMessage());
            return false;
        }
    }
    
    public function query($sql) {
        if (!$this->con) {
            error_log("Cannot execute query - no database connection");
            return false;
        }
        return $this->con->query($sql);
    }
    
    public function db_close() {
        if ($this->con) {
            $this->con->close();
        }
    }
}
?>