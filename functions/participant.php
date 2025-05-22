<?php
require_once 'db.php';

class Participant {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Inscription participant
    public function register($nom, $email, $telephone, $mot_de_passe) {
        $query = "INSERT INTO participant (nom, email, telephone, mot_de_passe, date_inscription, etat) 
                  VALUES (:nom, :email, :telephone, :mot_de_passe, NOW(), 1)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        return $stmt->execute();
    }

    // Connexion participant
    public function login($email, $mot_de_passe) {
        $query = "SELECT * FROM participant WHERE email = :email AND etat = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $participant = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($participant && password_verify($mot_de_passe, $participant['mot_de_passe'])) {
            return $participant;
        }
        return false;
    }
}
?>