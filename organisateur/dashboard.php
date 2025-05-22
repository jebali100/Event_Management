<?php
session_start();
if (!isset($_SESSION['organisateur'])) {
    header('Location: ../login_organisateur.php');
    exit;
}
require_once '../functions/organisateur.php';
require_once '../functions/evenement.php';
require_once '../functions/categorie.php';

$organisateur = new Organisateur();
$evenement = new Evenement();
$categorie = new Categorie();
$organisateur_id = is_array($_SESSION['organisateur']) ? $_SESSION['organisateur']['id'] : $_SESSION['organisateur'];

// Validate organizer ID
$organisateur_data = $organisateur->getById($organisateur_id);
if (!$organisateur_data) {
    session_destroy();
    header('Location: ../login_organisateur.php?error=organisateur_non_trouve');
    exit;
}

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../login_organisateur.php?logout=success');
    exit;
}

$events = $organisateur->getEventsByOrganisateurId($organisateur_id);
$categories = $categorie->getAll();
$message = '';

// Handle status messages from redirects
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'add_success':
            $message = '<script>showSuccessAlert("Événement ajouté avec succès !");</script>';
            break;
        case 'add_error':
            $message = '<script>showErrorAlert("Erreur lors de l\'ajout de l\'événement.");</script>';
            break;
        case 'edit_success':
            $message = '<script>showSuccessAlert("Événement modifié avec succès !");</script>';
            break;
        case 'edit_error':
            $message = '<script>showErrorAlert("Erreur lors de la modification de l\'événement.");</script>';
            break;
        case 'delete_success':
            $message = '<script>showSuccessAlert("Événement supprimé avec succès !");</script>';
            break;
        case 'delete_error':
            $message = '<script>showErrorAlert("Erreur lors de la suppression de l\'événement.");</script>';
            break;
        case 'image_error':
            $message = '<script>showErrorAlert("Erreur lors du téléchargement de l\'image.");</script>';
            break;
        case 'image_invalid':
            $message = '<script>showErrorAlert("Image invalide ou trop volumineuse.");</script>';
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_evenement'])) {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $date_event = $_POST['date_event'];
        $lieu = $_POST['lieu'];
        $id_categorie = $_POST['id_categorie'];
        $image = $_FILES['image'];

        $target_dir = "../images/";
        $image_name = basename($image['name']);
        $target_file = $target_dir . uniqid() . '_' . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types) && $image['size'] <= 5000000) {
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                $image_path = basename($target_file);
                if ($evenement->create($titre, $description, $date_event, $lieu, $image_path, $id_categorie, $organisateur_id)) {
                    header('Location: dashboard.php?status=add_success');
                    exit;
                } else {
                    header('Location: dashboard.php?status=add_error');
                    exit;
                }
            } else {
                header('Location: dashboard.php?status=image_error');
                exit;
            }
        } else {
            header('Location: dashboard.php?status=image_invalid');
            exit;
        }
    } elseif (isset($_POST['edit_evenement'])) {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $date_event = $_POST['date_event'];
        $lieu = $_POST['lieu'];
        $id_categorie = $_POST['id_categorie'];
        $image = $_FILES['image'];

        $image_path = $_POST['existing_image'];
        if ($image['size'] > 0) {
            $target_dir = "../images/";
            $image_name = basename($image['name']);
            $target_file = $target_dir . uniqid() . '_' . $image_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowed_types) && $image['size'] <= 5000000) {
                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    $image_path = basename($target_file);
                    @unlink($target_dir . $_POST['existing_image']);
                } else {
                    header('Location: dashboard.php?status=image_error');
                    exit;
                }
            } else {
                header('Location: dashboard.php?status=image_invalid');
                exit;
            }
        }

        if ($evenement->update($id, $titre, $description, $date_event, $lieu, $image_path, $id_categorie)) {
            header('Location: dashboard.php?status=edit_success');
            exit;
        } else {
            header('Location: dashboard.php?status=edit_error');
            exit;
        }
    } elseif (isset($_POST['delete_evenement'])) {
        $id = $_POST['id'];
        $image = $_POST['image'];
        if ($evenement->delete($id)) {
            @unlink("../images/" . $image);
            header('Location: dashboard.php?status=delete_success');
            exit;
        } else {
            header('Location: dashboard.php?status=delete_error');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Organisateur</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/navbar.css" rel="stylesheet">
    <link href="../css/footer.css" rel="stylesheet">
    <link href="../css/dashboard.css" rel="stylesheet">
    <script src="../js/dashboardorganisateur.js" defer></script>
</head>
<body>
    <div class="container my-5">
        <div class="top-bar">
            <h1 class="dashboard-title">Tableau de bord</h1>
            <div class="buttons-container">
                <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#addEvenementModal">
                    <i class="fas fa-plus"></i>
                </button>
                <form method="POST" style="display:inline;">
                    <button type="submit" name="logout" class="btn btn-danger btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        <?php echo $message; ?>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th class="description-cell">Description</th>
                        <th class="date-cell">Date</th>
                        <th class="category-cell">Catégorie</th>
                        <th class="date-cell">Lieu</th>
                        <th class="image-cell">Image</th>
                        <th class="status-cell">Statut</th>
                        <th class="action-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Aucun événement créé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['titre']); ?></td>
                                <td class="description-cell">
                                    <span class="text-ellipsis">
                                        <?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...
                                    </span>
                                </td>
                                <td class="date-cell">
                                    <span class="text-nowrap">
                                        <?php echo date('d/m/Y H:i', strtotime($event['date_event'])); ?>
                                    </span>
                                </td>
                                <td class="category-cell">
                                    <span class="text-nowrap">
                                        <?php echo htmlspecialchars($event['categorie_nom']); ?>
                                    </span>
                                </td>
                                <td class="date-cell">
                                    <span class="text-nowrap">
                                        <?php echo htmlspecialchars($event['lieu']); ?>
                                    </span>
                                </td>
                                <td class="image-cell">
                                    <div class="image-container">
                                        <img src="../images/<?php echo htmlspecialchars($event['image']); ?>" alt="Image" class="event-image">
                                    </div>
                                </td>
                                <td class="status-cell">
                                    <span class="status-badge">
                                        <?php
                                        if ($event['etat'] == 1) {
                                            echo '<i class="fas fa-clock"></i> En attente';
                                        } elseif ($event['etat'] == 2) {
                                            echo '<i class="fas fa-check"></i> Accepté';
                                        } else {
                                            echo '<i class="fas fa-times"></i> Refusé';
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-buttons">
                                        <button class="btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#editEvenementModal"
                                            data-id="<?php echo $event['id']; ?>"
                                            data-titre="<?php echo htmlspecialchars($event['titre']); ?>"
                                            data-description="<?php echo htmlspecialchars($event['description']); ?>"
                                            data-date="<?php echo $event['date_event']; ?>"
                                            data-lieu="<?php echo htmlspecialchars($event['lieu']); ?>"
                                            data-categorie="<?php echo $event['id_categorie']; ?>"
                                            data-image="<?php echo htmlspecialchars($event['image']); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($event['image']); ?>">
                                            <button type="submit" name="delete_evenement" class="btn-icon btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajout Événement -->
    <div class="modal fade" id="addEvenementModal" tabindex="-1" aria-labelledby="addEvenementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEvenementModalLabel">Ajouter un événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" name="titre" id="titre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="date_event" class="form-label">Date et heure</label>
                            <input type="datetime-local" name="date_event" id="date_event" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="lieu" class="form-label">Lieu</label>
                            <input type="text" name="lieu" id="lieu" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_categorie" class="form-label">Catégorie</label>
                            <select name="id_categorie" id="id_categorie" class="form-control" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="add_evenement" class="btn btn-custom">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modification Événement -->
    <div class="modal fade" id="editEvenementModal" tabindex="-1" aria-labelledby="editEvenementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEvenementModalLabel">Modifier l'événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <input type="hidden" name="existing_image" id="edit_existing_image">
                        <div class="mb-3">
                            <label for="edit_titre" class="form-label">Titre</label>
                            <input type="text" name="titre" id="edit_titre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_date_event" class="form-label">Date et heure</label>
                            <input type="datetime-local" name="date_event" id="edit_date_event" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_lieu" class="form-label">Lieu</label>
                            <input type="text" name="lieu" id="edit_lieu" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_id_categorie" class="form-label">Catégorie</label>
                            <select name="id_categorie" id="edit_id_categorie" class="form-control" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nom']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Image</label>
                            <input type="file" name="image" id="edit_image" class="form-control" accept="image/*">
                            <small>Laisser vide pour conserver l'image existante.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="edit_evenement" class="btn btn-custom">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>