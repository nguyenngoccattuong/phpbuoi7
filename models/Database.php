<?php
require_once 'config/database.php';

class Database extends DatabaseConfig {
    private $stmt;
    private $error;
    
    public function __construct() {
        $this->conn = $this->getConnection();
    }
    
    // Prepare statement with query
    public function query($sql) {
        $this->stmt = $this->conn->prepare($sql);
    }
    
    // Bind values
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    
    // Execute the prepared statement
    public function execute() {
        return $this->stmt->execute();
    }
    
    // Get result set as array of objects
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    // Get single record as object
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    
    // Get row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Get last inserted ID
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    
    // Thực hiện truy vấn trực tiếp (không qua prepare)
    public function directQuery($sql) {
        return $this->conn->exec($sql);
    }
}
?>
