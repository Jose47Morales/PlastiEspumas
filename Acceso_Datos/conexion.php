<?php
class Database { 
    private static $instance = null;
    private $conn;
    private $host = "dpg-clghacuf27hc739khpmg-a.oregon-postgres.render.com";
    private $user = "plastiespumas";
    private $pass = "bbnemI5faqT00JPJnKdyNOC6ZRqfQkct";
    private $name = "piaccess";

    private function __construct() {
        try {
            $connectionString = "pgsql:host={$this->host};dbname={$this->name};user={$this->user};password={$this->pass}";
            $this->conn = new PDO($connectionString);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Conexión exitosa (PDO)";
        } catch (PDOException $e) {
            die("Error de conexión (PDO): " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    } 

    public function getConnection() {
        return $this->conn;
    }
}
?>
