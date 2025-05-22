<?php
require_once 'db.php';

class Organisateur {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Inscription organisateur
    public function register($nom, $email, $mot_de_passe, $telephone, $adresse, $organisation) {
        $query = "INSERT INTO organisateur (nom, email, mot_de_passe, telephone, adresse, organisation, date_inscription, etat) 
                  VALUES (:nom, :email, :mot_de_passe, :telephone, :adresse, :organisation, NOW(), 1)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':organisation', $organisation);
        return $stmt->execute();
    }

    // Connexion organisateur
    public function login($email, $mot_de_passe) {
        $query = "SELECT * FROM organisateur WHERE email = :email AND etat = 2";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $organisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($organisateur && password_verify($mot_de_passe, $organisateur['mot_de_passe'])) {
            return $organisateur;
        }
        return false;
    }

    // Lire tous les organisateurs
    public function getAll() {
        $query = "SELECT * FROM organisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lire un organisateur par ID
    public function getById($id) {
        $query = "SELECT * FROM organisateur WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour le statut
    public function updateStatus($id, $etat) {
        $query = "UPDATE organisateur SET etat = :etat WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':etat', $etat, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Récupérer les événements d'un organisateur
    public function getEventsByOrganisateurId($id_organisateur) {
        $query = "SELECT e.*, c.nom AS categorie_nom FROM evenement e 
                  JOIN categorie c ON e.id_categorie = c.id 
                  WHERE e.id_organisateur = :id_organisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_organisateur', $id_organisateur);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>