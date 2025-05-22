<?php
// Pas de session_start() ici, géré par les pages principales
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            color: white;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 210px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Panneau Admin</h4>
        <a href="organisateurs.php"><i class="fas fa-users me-2"></i>Organisateurs</a>
        <a href="evenements.php"><i class="fas fa-calendar-alt me-2"></i>Événements</a>
        <a href="categories.php"><i class="fas fa-tags me-2"></i>Catégories</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a>
    </div>
    <div class="content">