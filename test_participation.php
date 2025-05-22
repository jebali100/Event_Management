<?php
require_once 'functions/participation.php';

$participation = new Participation();
$participant_id = 1; // Changez ceci pour tester avec différents IDs

try {
    $sql = "SELECT * FROM participation";
    if (method_exists($participation->db, 'execute')) {
        $result = $participation->db->execute($sql, []);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    } else if ($participation->conn) {
        $stmt = $participation->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
