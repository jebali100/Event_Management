function showSuccessAlert(message) {
    alert('Succès : ' + message);
}

function showErrorAlert(message) {
    alert('Erreur : ' + message);
}

function confirmDelete() {
    return confirm('Voulez-vous vraiment supprimer cet événement ?');
}

document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_titre').value = this.dataset.titre;
            document.getElementById('edit_description').value = this.dataset.description;
            document.getElementById('edit_date_event').value = this.dataset.date;
            document.getElementById('edit_lieu').value = this.dataset.lieu;
            document.getElementById('edit_id_categorie').value = this.dataset.categorie;
            document.getElementById('edit_existing_image').value = this.dataset.image;
        });
    });
});