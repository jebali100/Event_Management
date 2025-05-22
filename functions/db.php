<?php
// Connexion à la base de données avec PDO
class Database {
    private $host = 'localhost';
    private $db_name = 'evenements_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>