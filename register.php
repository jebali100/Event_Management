<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<?php
require_once 'functions/participant.php';

$participant = new Participant();
$message = '';

if (isset($_POST['register'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirmation = $_POST['confirmation'];
    if ($mot_de_passe !== $confirmation) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif ($participant->register($nom, $prenom, $email, $mot_de_passe)) {
        $message = '<div class="alert alert-success">Inscription réussie ! Connectez-vous.</div>';
    } else {
        $error = 'Erreur lors de l\'inscription.';
    }
}
?>

<div class="register-container">
    <div class="register-card">
        <h2 class="register-title">Inscription</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php echo $message; ?>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="confirmation" name="confirmation" placeholder="Confirmation du mot de passe" required>
            </div>
            <button type="submit" name="register" class="btn btn-custom register-button">S'inscrire</button>
        </form>
        <div class="text-center mt-4">
            <p class="mb-0">Déjà inscrit ? <a href="login.php" class="text-primary">Se connecter</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>