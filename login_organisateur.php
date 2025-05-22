<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion organisateur</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/navbar.css" rel="stylesheet">
    <link href="css/footer.css" rel="stylesheet">
</head>
<body>
<?php
session_start();
require_once 'functions/organisateur.php';
include 'navbar.php';

$organisateur = new Organisateur();
$message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $user = $organisateur->login($email, $mot_de_passe);
    if ($user) {
        $_SESSION['organisateur'] = $user;
        header('Location: organisateur/dashboard.php');
    } else {
        $message = '<div class="alert alert-danger">Email, mot de passe incorrect ou compte non approuvé.</div>';
    }
}
?>

<main class="main-content">
    <div class="form-container">
        <h2>Connexion organisateur</h2>
        <?php echo $message; ?>
        <form method="POST" id="loginOrganisateurForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" name="login" class="btn btn-custom">Se connecter</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>