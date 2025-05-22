function validateRegisterForm() {
    var nom = document.getElementById('nom').value;
    var email = document.getElementById('email').value;
    var mot_de_passe = document.getElementById('mot_de_passe').value;
    var telephone = document.getElementById('telephone').value;

    if (nom === '') {
        alert("Le nom est requis");
        return false;
    }
    if (email === '') {
        alert("L'email est requis");
        return false;
    }
    if (mot_de_passe === '') {
        alert("Le mot de passe est requis");
        return false;
    }
    if (telephone === '') {
        alert("Le numéro de téléphone est requis");
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('registerForm');
    if (form) {
        form.onsubmit = validateRegisterForm;
    }
});
