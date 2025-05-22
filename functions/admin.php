<?php
require_once 'db.php';

class Admin {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

   

    // Connexion admin
    public function login($email, $mot_de_passe) {
        $query = "SELECT * FROM admin WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && $mot_de_passe === $admin['mot_de_passe']) { // Comparaison en texte brut
            return $admin;
        }
        return false;
    }
}
?>