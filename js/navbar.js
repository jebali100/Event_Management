document.addEventListener('DOMContentLoaded', function() {
    // Sélecteurs
    const userBtn = document.querySelector('.user-btn');
    const userDropdown = document.querySelector('.user-dropdown');
    const dropdownItems = document.querySelectorAll('.dropdown-item');

    // Fonction pour gérer le dropdown
    function toggleDropdown() {
        if (userDropdown.style.display === 'block') {
            userDropdown.style.display = 'none';
        } else {
            userDropdown.style.display = 'block';
        }
    }

    // Événement clic sur le bouton utilisateur
    if (userBtn) {
        userBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleDropdown();
        });
    }

    // Fermer le dropdown quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.style.display = 'none';
        }
    });

    // Empêcher la propagation de l'événement de clic sur les items du dropdown
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            // Fermer le dropdown après le clic sur un item
            userDropdown.style.display = 'none';
        });
    });
});
