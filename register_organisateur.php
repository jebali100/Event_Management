<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription organisateur</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
    <script src="js/register_organisateur.js"></script>
</head>
<body>
<?php
session_start();
require_once 'functions/organisateur.php';
include 'navbar.php';

$organisateur = new Organisateur();
$message = '';

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];
    $organisation = $_POST['organisation'];
    if ($organisateur->register($nom, $email, $mot_de_passe, $telephone, $adresse, $organisation)) {
        $message = '<div class="alert alert-success">Inscription réussie ! En attente d\'approbation.</div>';
    } else {
        $message = '<div class="alert alert-danger">Erreur lors de l\'inscription.</div>';
    }
}
?>

<div class="form-container">
    <h2>Inscription organisateur</h2>
    <?php echo $message; ?>
    <form method="POST" id="registerOrganisateurForm">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez votre nom">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email">
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="Entrez votre numéro de téléphone">
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <textarea name="adresse" id="adresse" class="form-control" placeholder="Entrez votre adresse" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="organisation" class="form-label">Organisation</label>
            <input type="text" name="organisation" id="organisation" class="form-control" placeholder="Nom de votre organisation">
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" placeholder="Entrez votre mot de passe">
        </div>
        <button type="submit" name="register" class="btn btn-custom">S'inscrire</button>
    </form>
</div>

<?php include 'footer.php'; ?>