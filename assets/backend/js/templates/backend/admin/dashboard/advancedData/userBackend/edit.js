// Confirmation message before submiting the form
document.addEventListener("DOMContentLoaded", function () {
    attachConfirmationToForm("form-edit-user-backend-js", "Êtes-vous sûr de vouloir d'effectuer cette modification ?");
});


const editPasswordButton = document.getElementById("button-edit-password-js");
const containerPasswordFields = document.getElementById("container-password-js");

editPasswordButton.addEventListener("click", function () {
    containerPasswordFields.classList.toggle("hidden");

    if(containerPasswordFields.classList.contains("hidden")) {
        editPasswordButton.textContent = "Modifier le mot de passe";
    } else {
        editPasswordButton.textContent = "Cacher le mot de passe";
    }
});