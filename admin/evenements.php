<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login_admin.php');
    exit;
}
require_once '../functions/evenement.php';
require_once '../functions/categorie.php';

$evenement = new Evenement();
$categorie = new Categorie();
$events = $evenement->getAll(); // Get all events for admin
$categories = $categorie->getAll();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $id = $_POST['id'];
        $etat = $_POST['etat'];
        if ($evenement->updateStatus($id, $etat)) {
            $message = '<div class="alert alert-success">Statut mis à jour avec succès !</div>';
            $events = $evenement->getAll();
        } else {
            $message = '<div class="alert alert-danger">Erreur lors de la mise à jour du statut.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Événements - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/admin-event.css">
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h2 class="sidebar-logo">Admin</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="organisateurs.php" class="sidebar-link">
                    <i class="fas fa-users"></i>
                    Organisateurs
                </a>
            </li>
            <li class="sidebar-item">
                <a href="evenements.php" class="sidebar-link active">
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
            <h1 class="page-title">Gestion des Événements</h1>
        </div>

        <!-- Messages -->
        <?php echo $message; ?>

        <!-- Filtre de statut -->
        <div class="mb-4">
            <div class="form-group">
                <label for="statusFilter" class="form-label">Filtrer par statut :</label>
                <select id="statusFilter" class="form-select">
                    <option value="all">Tous</option>
                    <option value="1">En attente</option>
                    <option value="2">Accepté</option>
                    <option value="3">Terminé</option>
                    <option value="0">Refusé</option>
                </select>
            </div>
        </div>

        <!-- Tableau des événements -->
        <div class="event-table-container">
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Catégorie</th>
                        <th>Image</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Aucun événement.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr data-status="<?php echo $event['etat']; ?>">
                                <td><?php echo htmlspecialchars($event['titre']); ?></td>
                                <td class="text-ellipsis">
                                    <?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...
                                </td>
                                <td class="text-nowrap">
                                    <?php echo date('d/m/Y H:i', strtotime($event['date_event'])); ?>
                                </td>
                                <td class="text-nowrap">
                                    <?php echo htmlspecialchars($event['lieu']); ?>
                                </td>
                                <td class="text-nowrap">
                                    <?php echo htmlspecialchars($event['categorie_nom']); ?>
                                </td>
                                <td>
                                    <div class="event-image-container">
                                        <img src="../images/<?php echo htmlspecialchars($event['image']); ?>" alt="Image" class="event-image">
                                    </div>
                                </td>
                                <td>
                                    <span class="event-status-badge <?php echo $event['etat'] == 1 ? 'event-status-pending' : ($event['etat'] == 2 ? 'event-status-approved' : ($event['etat'] == 3 ? 'event-status-completed' : 'event-status-rejected')); ?>">
                                        <?php
                                        switch ($event['etat']) {
                                            case 1:
                                                echo '<i class="fas fa-clock"></i> En attente';
                                                break;
                                            case 2:
                                                echo '<i class="fas fa-check"></i> Accepté';
                                                break;
                                            case 3:
                                                echo '<i class="fas fa-flag-checkered"></i> Terminé';
                                                break;
                                            default:
                                                echo '<i class="fas fa-times"></i> Refusé';
                                                break;
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                        <select name="etat" class="event-status-select">
                                            <option value="1" <?php echo $event['etat'] == 1 ? 'selected' : ''; ?>>En attente</option>
                                            <option value="2" <?php echo $event['etat'] == 2 ? 'selected' : ''; ?>>Accepté</option>
                                            <option value="3" <?php echo $event['etat'] == 3 ? 'selected' : ''; ?>>Terminé</option>
                                            <option value="0" <?php echo $event['etat'] == 0 ? 'selected' : ''; ?>>Refusé</option>
                                        </select>
                                        <button type="submit" name="update_status" class="event-update-btn">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtrage des événements
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>