<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - Accueil</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php
session_start();
require_once 'functions/evenement.php';
require_once 'functions/categorie.php';
include 'navbar.php';

$evenement = new Evenement();
$events = $evenement->getAll(2); // Événements acceptés
$categorie = new Categorie();
$categories = $categorie->getAll();
?>

<div class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Bienvenue sur notre plateforme d'événements</h1>
        <p class="hero-subtitle">Découvrez et participez aux événements les plus intéressants autour de vous</p>
        <a href="evenement.php" class="cta-button">Voir les événements</a>
    </div>
</div>

<div class="events-grid">
    <h2>Événements à venir</h2>
    <div class="grid-content">
        <?php foreach ($events as $event): ?>
            <?php if (strtotime($event['date_event']) > time()): ?>
                <div class="event-card">
                    <img src="images/<?php echo $event['image']; ?>" class="event-img" alt="<?php echo $event['titre']; ?>">
                    <div class="event-content">
                        <h3 class="event-title"><?php echo $event['titre']; ?></h3>
                        <p class="event-text"><?php echo substr($event['description'], 0, 100); ?>...</p>
                        <div class="event-date">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('d/m/Y H:i', strtotime($event['date_event'])); ?></span>
                        </div>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $event['lieu']; ?></span>
                        </div>
                        <a href="evenement.php?id=<?php echo $event['id']; ?>" class="event-btn">Voir plus</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<div class="categories-grid">
    <h2>Catégories</h2>
    <div class="grid-content">
        <?php foreach ($categories as $cat): ?>
            <div class="category-card">
                <i class="category-icon fas <?php echo $cat['icone']; ?>"></i>
                <h3 class="category-title"><?php echo $cat['nom']; ?></h3>
                <a href="evenement.php?categorie=<?php echo $cat['id']; ?>" class="category-btn">Voir les événements</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="organizer-section">
    <h2>Devenez organisateur</h2>
    <p class="organizer-subtitle">Rejoignez notre plateforme pour créer et gérer vos propres événements !</p>
    <div class="organizer-btns">
        <a href="register_organisateur.php" class="organizer-btn organizer-btn-primary">S'inscrire</a>
        <a href="login_organisateur.php" class="organizer-btn organizer-btn-outline">Se connecter</a>
    </div>
</div>

<?php include 'footer.php'; ?>