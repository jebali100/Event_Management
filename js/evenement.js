document.addEventListener('DOMContentLoaded', () => {
    const sortDate = document.getElementById('sortDate');
    const sortCategory = document.getElementById('sortCategory');
    const eventContainer = document.getElementById('eventsList');
    const eventCards = document.querySelectorAll('.event-card');

    // Vérifier si les éléments existent
    if (!sortDate || !sortCategory || !eventContainer) {
        console.error('Éléments manquants');
        return;
    }

    function updateEvents() {
        const selectedCategory = sortCategory.value;
        const sortOrder = sortDate.value;

        // Filtrer les cartes par catégorie
        const visibleCards = Array.from(eventCards).filter(card => {
            const cardCategoryId = card.getAttribute('data-category-id');
            return selectedCategory === 'all' || cardCategoryId === selectedCategory;
        });

        // Trier les cartes par date
        visibleCards.sort((a, b) => {
            const dateA = new Date(a.getAttribute('data-date'));
            const dateB = new Date(b.getAttribute('data-date'));
            return sortOrder === 'asc' ? dateA - dateB : dateB - dateA;
        });

        // Afficher les résultats
        eventContainer.innerHTML = '';
        if (visibleCards.length === 0) {
            eventContainer.innerHTML = '<p class="text-center">Aucun événement disponible.</p>';
        } else {
            visibleCards.forEach(card => eventContainer.appendChild(card));
        }
    }

    // Ajouter les écouteurs d'événements
    sortDate.addEventListener('change', updateEvents);
    sortCategory.addEventListener('change', updateEvents);

    // Initialiser
    updateEvents();
});