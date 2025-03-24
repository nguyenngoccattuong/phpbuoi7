<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'qlsv');
define('DB_USER', 'root');
define('DB_PASS', 'root');

class DatabaseConfig {
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $user = DB_USER;
    private $password = DB_PASS;
    
    protected $conn;
    
    public function getConnection() {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        
        // Create PDO instance
        try {
            $this->conn = new PDO($dsn, $this->user, $this->password, $options);
            return $this->conn;
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            return null;
        }
    }
    
    // Nếu cần chức năng tạo database/tables
    public function createDatabaseIfNotExists() {
        try {
            $pdo = new PDO("mysql:host=" . $this->host, $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . $this->dbname);
            return true;
        } catch(PDOException $e) {
            echo "Database creation error: " . $e->getMessage();
            return false;
        }
    }
}
?>
