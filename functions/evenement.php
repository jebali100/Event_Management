<?php
require_once 'db.php';

class Evenement {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($titre, $description, $date_event, $lieu, $image, $id_categorie, $id_organisateur) {
        $query = "SELECT id FROM organisateur WHERE id = :id_organisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_organisateur', $id_organisateur, PDO::PARAM_INT);
        $stmt->execute();
        if (!$stmt->fetch()) {
            error_log("Invalid id_organisateur: $id_organisateur");
            return false;
        }

        $query = "SELECT id FROM categorie WHERE id = :id_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->execute();
        if (!$stmt->fetch()) {
            error_log("Invalid id_categorie: $id_categorie");
            return false;
        }

        $query = "SELECT id FROM evenement WHERE titre = :titre AND date_event = :date_event AND id_organisateur = :id_organisateur";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':date_event', $date_event);
        $stmt->bindParam(':id_organisateur', $id_organisateur, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()) {
            error_log("Duplicate event detected: $titre, $date_event, $id_organisateur");
            return false;
        }

        $query = "INSERT INTO evenement (titre, description, date_event, lieu, image, id_categorie, id_organisateur, etat) 
                  VALUES (:titre, :description, :date_event, :lieu, :image, :id_categorie, :id_organisateur, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date_event', $date_event);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        $stmt->bindParam(':id_organisateur', $id_organisateur, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function update($id, $titre, $description, $date_event, $lieu, $image, $id_categorie) {
        $query = "UPDATE evenement 
                  SET titre = :titre, description = :description, date_event = :date_event, 
                      lieu = :lieu, image = :image, id_categorie = :id_categorie 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date_event', $date_event);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM evenement WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM evenement WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($etat = null) {
        $query = "SELECT e.*, c.nom AS categorie_nom 
                  FROM evenement e 
                  JOIN categorie c ON e.id_categorie = c.id";
        if ($etat !== null) {
            if (is_array($etat)) {
                $placeholders = implode(',', array_fill(0, count($etat), '?'));
                $query .= " WHERE e.etat IN ($placeholders)";
            } else {
                $query .= " WHERE e.etat = :etat";
            }
        }
        $stmt = $this->conn->prepare($query);
        if ($etat !== null) {
            if (is_array($etat)) {
                foreach ($etat as $index => $value) {
                    $stmt->bindValue($index + 1, $value, PDO::PARAM_INT);
                }
            } else {
                $stmt->bindParam(':etat', $etat, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $etat) {
        $query = "UPDATE evenement SET etat = :etat WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':etat', $etat, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>