<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - Liste</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/evenement.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php
session_start();
require_once 'functions/db.php';
require_once 'functions/evenement.php';
require_once 'functions/categorie.php';
require_once 'functions/participation.php';

$evenement = new Evenement();
$categorie = new Categorie();
$participation = new Participation();
$events = $evenement->getAll([2]); // Only accepted events (etat = 2)
$categories = $categorie->getAll();

// Handle participation actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['participant']) || !isset($_SESSION['participant']['id'])) {
        header('Location: login.php');
        exit;
    }

    $participant_id = $_SESSION['participant']['id'];
    $event_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($event_id && $action) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=evenements_db", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if ($action === 'participate') {
                $stmt = $pdo->prepare("INSERT INTO participation (id_participant, id_evenement, date_inscription) VALUES (?, ?, CURDATE())");
                $stmt->execute([$participant_id, $event_id]);
                header('Location: evenement.php');
                exit;
            } elseif ($action === 'cancel') {
                $stmt = $pdo->prepare("DELETE FROM participation WHERE id_participant = ? AND id_evenement = ?");
                $stmt->execute([$participant_id, $event_id]);
                header('Location: evenement.php');
                exit;
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    }
}

require_once 'navbar.php';
?>

<div class="container">
    <h2 class="page-title">Événements</h2>

    <!-- Filter and Sort Controls -->
    <div class="filters-container">
        <div class="filter-group">
            <label for="sortDate">Trier par date :</label>
            <select id="sortDate">
                <option value="asc">Date croissante</option>
                <option value="desc">Date décroissante</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="sortCategory">Filtrer par catégorie :</label>
            <select id="sortCategory">
                <option value="all">Toutes</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Section Événements -->
    <div class="events-grid" id="eventsList">
        <?php foreach ($events as $event): ?>
            <div class="event-card" 
                 data-category-id="<?php echo $event['id_categorie']; ?>" 
                 data-date="<?php echo date('c', strtotime($event['date_event'])); ?>">
                <img src="images/<?php echo $event['image']; ?>" alt="<?php echo $event['titre']; ?>">
                <div class="event-content">
                    <h3><?php echo $event['titre']; ?></h3>
                    <p><?php echo substr($event['description'], 0, 100); ?>...</p>
                    <div class="event-details">
                        <p><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($event['date_event'])); ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo $event['lieu']; ?></p>
                        <p><i class="fas fa-tags"></i> <?php echo $event['categorie_nom']; ?></p>
                        <?php
                        if (isset($_SESSION['participant'])) {
                            $participant_id = $_SESSION['participant']['id'];
                            $isParticipating = false;
                            
                            try {
                                $pdo = new PDO("mysql:host=localhost;dbname=evenements_db", "root", "");
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM participation WHERE id_participant = ? AND id_evenement = ?");
                                $stmt->execute([$participant_id, $event['id']]);
                                $isParticipating = $stmt->fetchColumn() > 0;
                            } catch (PDOException $e) {
                                echo "<p class='error-message'>Erreur de connexion à la base de données</p>";
                            }
                            
                            if ($isParticipating) {
                                // Show cancel participation button
                                echo '<form method="POST" class="participation-form" onsubmit="return confirmParticipation(\'annuler\')">';
                                echo '<input type="hidden" name="action" value="cancel">';
                                echo '<input type="hidden" name="event_id" value="' . $event['id'] . '">';
                                echo '<button type="submit" class="btn-danger">Annuler la participation</button>';
                                echo '</form>';
                            } else {
                                // Show participate button
                                echo '<form method="POST" class="participation-form" onsubmit="return confirmParticipation(\'participer\')">';
                                echo '<input type="hidden" name="action" value="participate">';
                                echo '<input type="hidden" name="event_id" value="' . $event['id'] . '">';
                                echo '<button type="submit" class="btn-success">Participer</button>';
                                echo '</form>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
function confirmParticipation(action) {
    return confirm(`Voulez-vous ${action === 'participer' ? 'participer à' : 'annuler votre participation à'} cet événement ?`);
}
</script>
<script src="js/evenement.js"></script>
</body>
</html>