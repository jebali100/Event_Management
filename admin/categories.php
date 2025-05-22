<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../functions/categorie.php';

$categorie = new Categorie();
$message = '';

// Gestion de l'ajout
if (isset($_POST['add_categorie'])) {
    $nom = $_POST['nom'];
    $icone = $_POST['icone'];
    if ($categorie->add($nom, $icone)) {
        $message = '<div class="message message-success">Catégorie ajoutée avec succès.</div>';
    } else {
        $message = '<div class="message message-error">Erreur lors de l\'ajout de la catégorie.</div>';
    }
}

// Gestion de la modification
if (isset($_POST['update_categorie'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $icone = $_POST['icone'];
    if ($categorie->update($id, $nom, $icone)) {
        $message = '<div class="message message-success">Catégorie modifiée avec succès.</div>';
    } else {
        $message = '<div class="message message-error">Erreur lors de la modification de la catégorie.</div>';
    }
}

// Gestion de la suppression
if (isset($_POST['delete_categorie'])) {
    $id = $_POST['id'];
    if ($categorie->delete($id)) {
        $message = '<div class="message message-success">Catégorie supprimée avec succès.</div>';
    } else {
        $message = '<div class="message message-error">Erreur lors de la suppression de la catégorie.</div>';
    }
}

$categories = $categorie->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Catégories - Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/admin-categories.css">
</head>
<body>
    <div class="wrapper">
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
        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
                <div class="category-container">
                    <div class="page-header">
                        <h2 class="category-title">Gestion des catégories</h2>
                        <?php echo $message; ?>
                    </div>
                    
                    <!-- Formulaire d'ajout -->
                    <div class="category-form">
                        <form method="POST">
                            <div class="form-group">
                                <input type="text" name="nom" class="form-control" placeholder="Nom de la catégorie" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="icone" class="form-control" placeholder="Classe FontAwesome (ex: fa-music)" required>
                            </div>
                            <button type="submit" name="add_categorie" class="btn-add">
                                <i class="fas fa-plus"></i> Ajouter
                            </button>
                        </form>
                    </div>

                    <!-- Tableau des catégories -->
                    <div class="table-responsive">
                        <table class="category-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Icône</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cat['nom']); ?></td>
                                        <td>
                                            <div class="icon-preview">
                                                <i class="category-icon fas <?php echo htmlspecialchars($cat['icone']); ?>"></i>
                                                <span class="icon-class"><?php echo htmlspecialchars($cat['icone']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- Bouton Modifier -->
                                            <button type="button" class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" 
                                                    data-id="<?php echo $cat['id']; ?>" 
                                                    data-nom="<?php echo htmlspecialchars($cat['nom']); ?>" 
                                                    data-icone="<?php echo htmlspecialchars($cat['icone']); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <!-- Bouton Supprimer -->
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                <button type="submit" name="delete_categorie" class="action-btn btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de modification -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" id="edit_nom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icône</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="edit_icone" name="icone" required>
                                <button class="btn btn-outline-secondary" type="button" id="iconPreviewBtn">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="update_categorie" class="btn btn-primary">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialiser le modal
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        
        // Gestion des données du modal
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const nom = this.dataset.nom;
                const icone = this.dataset.icone;
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nom').value = nom;
                document.getElementById('edit_icone').value = icone;
            });
        });

        // Prévisualisation de l'icône
        document.getElementById('iconPreviewBtn').addEventListener('click', function() {
            const iconInput = document.getElementById('edit_icone');
            const iconPreview = document.createElement('span');
            iconPreview.innerHTML = `<i class="fas ${iconInput.value}"></i>`;
            alert(iconPreview.innerHTML);
        });
    </script>

</body>
</html>