<?php
class DB {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Load database configuration
        require_once dirname(__DIR__) . '/config/config.php';
        
        // Create connection
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Get database instance (Singleton pattern)
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    // Get database connection
    public function getConnection() {
        return $this->conn;
    }

    // Execute a query and return result
    public function query($sql) {
        return $this->conn->query($sql);
    }

    // Prepare a statement
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    // Sanitize input data
    public function escapeString($value) {
        return $this->conn->real_escape_string($value);
    }

    // Get the last inserted ID
    public function getLastId() {
        return $this->conn->insert_id;
    }

    // Get error message
    public function getError() {
        return $this->conn->error;
    }

    // Begin transaction
    public function beginTransaction() {
        $this->conn->begin_transaction();
    }

    // Commit transaction
    public function commit() {
        $this->conn->commit();
    }

    // Rollback transaction
    public function rollback() {
        $this->conn->rollback();
    }
}