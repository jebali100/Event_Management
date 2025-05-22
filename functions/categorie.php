<?php
require_once 'db.php';

class Categorie {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Créer une catégorie
    public function add($nom, $icone) {
        try {
            $nom = trim($nom);
            $icone = trim($icone);
            
            if (empty($nom) || empty($icone)) {
                throw new Exception("Les champs nom et icone sont obligatoires");
            }

            $query = "INSERT INTO categorie (nom, icone) VALUES (:nom, :icone)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':icone', $icone);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout de la catégorie: " . $e->getMessage());
            return false;
        }
    }

    // Lire toutes les catégories
    public function getAll() {
        try {
            $query = "SELECT * FROM categorie ORDER BY nom ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la lecture des catégories: " . $e->getMessage());
            return [];
        }
    }

    // Lire une catégorie par ID
    public function getById($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("ID invalide");
            }

            $query = "SELECT * FROM categorie WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la lecture de la catégorie: " . $e->getMessage());
            return null;
        }
    }

    // Mettre à jour une catégorie
    public function update($id, $nom, $icone) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("ID invalide");
            }

            $nom = trim($nom);
            $icone = trim($icone);
            
            if (empty($nom) || empty($icone)) {
                throw new Exception("Les champs nom et icone sont obligatoires");
            }

            $query = "UPDATE categorie SET nom = :nom, icone = :icone WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':icone', $icone);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour de la catégorie: " . $e->getMessage());
            return false;
        }
    }

    // Supprimer une catégorie
    public function delete($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("ID invalide");
            }

            // Démarrer une transaction
            $this->conn->beginTransaction();

            // Vérifier si la catégorie existe
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM categorie WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if ($stmt->fetchColumn() === 0) {
                throw new Exception("Catégorie non trouvée");
            }

            // Vérifier si la catégorie est utilisée
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM evenement WHERE categorie_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                throw new Exception("Cette catégorie est utilisée par des événements et ne peut pas être supprimée");
            }

            // Supprimer la catégorie
            $query = "DELETE FROM categorie WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de la suppression de la catégorie");
            }

            // Valider la transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Erreur lors de la suppression de la catégorie: " . $e->getMessage());
            return false;
        }
    }
}
?>