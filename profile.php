<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Événements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/profil.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['participant'])) {
    header('Location: login.php');
    exit;
}
require_once 'functions/participation.php';
require_once 'functions/evenement.php';

$participation = new Participation();
$evenement = new Evenement();
$participant_id = $_SESSION['participant'];
if (is_array($participant_id)) {
    $participant_id = $participant_id['id'];
}
$participations = $participation->getByParticipantId($participant_id);

include 'navbar.php';
?>

<div class="profile-page">
    <div class="profile-header">
        <div class="profile-info">
            <h1 class="profile-name"><?php echo htmlspecialchars($_SESSION['participant']['nom']); ?></h1>
            <p class="profile-email"><?php echo htmlspecialchars($_SESSION['participant']['email']); ?></p>
        </div>
    </div>

    <div class="participation-section">
        <h2 class="participation-title">Mes participations aux événements</h2>
        <?php if (empty($participations)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Vous n'avez participé à aucun événement pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="participation-grid">
                <?php foreach ($participations as $part): ?>
                    <div class="participation-card">
                        <img src="images/<?php echo htmlspecialchars($part['image']); ?>" class="participation-img" alt="<?php echo htmlspecialchars($part['titre']); ?>">
                        <div class="participation-content">
                            <h3 class="participation-title"><?php echo htmlspecialchars($part['titre']); ?></h3>
                            <p class="participation-text"><?php echo htmlspecialchars(substr($part['description'], 0, 100)); ?>...</p>
                            <div class="participation-info">
                                <div class="event-date">
                                    <i class="fas fa-calendar"></i>
                                    <span><?php echo date('d/m/Y H:i', strtotime($part['date_event'])); ?></span>
                                </div>
                                <div class="event-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($part['lieu']); ?></span>
                                </div>
                            </div>
                            <a href="evenement.php?id=<?php echo $part['id']; ?>" class="participation-btn">Voir détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>