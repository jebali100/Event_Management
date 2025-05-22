<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">

</head>
<body>
<?php include 'navbar.php'; ?>
<?php
session_start();
require_once 'functions/participant.php';

$participant = new Participant();
$message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $user = $participant->login($email, $mot_de_passe);
    if ($user) {
        $_SESSION['participant'] = $user;
        header('Location: index.php');
    } else {
        $message = 'Email ou mot de passe incorrect.';
    }
}
?>

<div class="login-container">
    <div class="login-card">
        <h2 class="login-title">Connexion</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" id="loginForm">
            <div class="mb-4">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
            </div>
            <button type="submit" name="login" class="btn btn-custom login-button">Se connecter</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>