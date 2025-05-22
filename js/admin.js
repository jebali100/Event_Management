function showSuccessAlert(message) {
    alert('Succès : ' + message);
}

function showErrorAlert(message) {
    alert('Erreur : ' + message);
}

document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const eventsTable = document.getElementById('eventsTable');
    const rows = eventsTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowStatus = row.getAttribute('data-status');

            if (selectedStatus === 'all' || rowStatus === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});