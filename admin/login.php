<?php
session_start();
require_once '../functions/admin.php';

$admin = new Admin();
$message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $result = $admin->login($email, $mot_de_passe);
    if ($result) {
        $_SESSION['admin'] = $result['id'];
        header('Location: organisateurs.php');
        exit;
    } else {
        $message = '<div class="alert alert-danger">Email ou mot de passe incorrect.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin-login.css">
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Administration</h1>
        <p class="login-subtitle">Zone de connexion réservée aux administrateurs</p>
        
        <?php echo $message; ?>
        
        <form method="POST" id="loginAdminForm">
            <div class="form-group">
                <label for="email">Email</label>
                <div class="position-relative">
                    <input type="email" class="form-control" id="email" name="email" required>
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <div class="position-relative">
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                    <i class="fas fa-lock"></i>
                </div>
            </div>
            
            <button type="submit" name="login" class="btn-login">
                <i class="fas fa-sign-in-alt"></i>
                Se connecter
            </button>
            
            <div class="loading"></div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion du chargement
        const form = document.querySelector('form');
        const loading = document.querySelector('.loading');
        const submitButton = document.querySelector('.btn-login');

        form.addEventListener('submit', () => {
            loading.style.display = 'block';
            submitButton.style.display = 'none';
        });

        // Réinitialisation après la réponse du serveur
        setTimeout(() => {
            loading.style.display = 'none';
            submitButton.style.display = 'flex';
        }, 2000);
    </script>
</body>
</html>
