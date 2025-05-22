<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../functions/organisateur.php';

$organisateur = new Organisateur();
$organisateurs = $organisateur->getAll();
$message = '';

if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $etat = $_POST['etat'];
    if ($organisateur->updateStatus($id, $etat)) {
        $message = '<div class="alert alert-success">Statut mis à jour avec succès.</div>';
    } else {
        $message = '<div class="alert alert-danger">Erreur lors de la mise à jour du statut.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Organisateurs - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-logo">Admin</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="organisateurs.php" class="sidebar-link active">
                    <i class="fas fa-users"></i>
                    Organisateurs
                </a>
            </li>
            <li class="sidebar-item">
                <a href="evenements.php" class="sidebar-link">
                    <i class="fas fa-calendar"></i>
                    Événements
                </a>
            </li>
            <li class="sidebar-item">
                <a href="categories.php" class="sidebar-link">
                    <i class="fas fa-tags"></i>
                    Catégories
                </a>
            </li>
            <li class="sidebar-item">
                <a href="logout.php" class="sidebar-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </a>
            </li>
        </ul>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Gestion des Organisateurs</h1>
        </div>

        <!-- Messages -->
        <?php echo $message; ?>

        <!-- Tableau des organisateurs -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Organisation</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organisateurs as $org): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($org['nom']); ?></td>
                            <td><?php echo htmlspecialchars($org['email']); ?></td>
                            <td><?php echo htmlspecialchars($org['telephone']); ?></td>
                            <td><?php echo htmlspecialchars($org['organisation']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $org['etat'] == 1 ? 'status-pending' : ($org['etat'] == 2 ? 'status-approved' : 'status-rejected'); ?>">
                                    <?php
                                    if ($org['etat'] == 1) {
                                        echo '<i class="fas fa-clock"></i> En attente';
                                    } elseif ($org['etat'] == 2) {
                                        echo '<i class="fas fa-check"></i> Accepté';
                                    } else {
                                        echo '<i class="fas fa-times"></i> Refusé';
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="id" value="<?php echo $org['id']; ?>">
                                    <select name="etat" class="status-select">
                                        <option value="1" <?php if ($org['etat'] == 1) echo 'selected'; ?>>En attente</option>
                                        <option value="2" <?php if ($org['etat'] == 2) echo 'selected'; ?>>Accepté</option>
                                        <option value="0" <?php if ($org['etat'] == 0) echo 'selected'; ?>>Refusé</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn-update">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</body>
</html>