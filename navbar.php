<?php
// Pas de session_start() ici, géré par les pages principales
?>
    <nav class="site-navbar">
        <div class="navbar-content">
            <div class="nav-left">
                <a href="index.php" class="brand">
                    <i class="fas fa-calendar-alt"></i>
                    EventHub
                </a>
            </div>
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="index.php" class="nav-link">Accueil</a></li>
                    <li><a href="evenement.php" class="nav-link">Événements</a></li>
                    <?php 
                    // Vérifier le type de connexion
                    $isParticipant = isset($_SESSION['participant']);
                    $isOrganisateur = isset($_SESSION['organisateur']);
                    $isAdmin = isset($_SESSION['admin']);
                    
                    if ($isParticipant || $isOrganisateur || $isAdmin): ?>
                        <?php if ($isParticipant): ?>
                            <li><a href="profile.php" class="nav-link">Profil</a></li>
                        <?php elseif ($isOrganisateur): ?>
                            <li><a href="organisateur/dashboard.php" class="nav-link">Tableau de bord</a></li>
                        <?php elseif ($isAdmin): ?>
                            <li><a href="admin/organisateurs.php" class="nav-link">Tableau de bord</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="nav-right">
                <div class="auth-buttons">
                    <?php if (!isset($_SESSION['participant'])): ?>
                        <a href="login.php" class="btn btn-outline">Se connecter</a>
                        <a href="register.php" class="btn btn-primary">S'inscrire</a>
                    <?php else: ?>
                        <div class="user-menu">
                            <button class="user-btn" type="button">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($_SESSION['participant']['nom']); ?>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown">
                                <a href="profile.php" class="dropdown-item">
                                    <i class="fas fa-user"></i>Mon profil
                                </a>
                                <a href="logout.php" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>Déconnexion
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <script src="js/navbar.js"></script>