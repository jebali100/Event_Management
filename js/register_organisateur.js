function validateRegisterOrganisateurForm() {
    var nom = document.getElementById('nom').value;
    var email = document.getElementById('email').value;
    var telephone = document.getElementById('telephone').value;
    var adresse = document.getElementById('adresse').value;
    var organisation = document.getElementById('organisation').value;
    var mot_de_passe = document.getElementById('mot_de_passe').value;

    if (nom === '') {
        alert("Le nom est requis");
        return false;
    }
    if (email === '') {
        alert("L'email est requis");
        return false;
    }
    if (telephone === '') {
        alert("Le téléphone est requis");
        return false;
    }
    if (adresse === '') {
        alert("L'adresse est requise");
        return false;
    }
    if (organisation === '') {
        alert("Le nom de l'organisation est requis");
        return false;
    }
    if (mot_de_passe === '') {
        alert("Le mot de passe est requis");
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('registerOrganisateurForm');
    if (form) {
        form.onsubmit = validateRegisterOrganisateurForm;
    }
});
