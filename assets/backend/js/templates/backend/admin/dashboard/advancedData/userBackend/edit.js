const editPasswordButton = document.getElementById("button-edit-password-js");
const containerPasswordFields = document.getElementById("container-password-js");
const formSubmitButton = document.getElementById("form-edit-backend-user-js");
const submitFormButton = document.getElementById("submit-button-edit-backend-user-js");

// On click on the submit button, we display a confirmation popup
submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette modification ?");
});

editPasswordButton.addEventListener("click", function () {
    containerPasswordFields.classList.toggle("hidden");

    if(containerPasswordFields.classList.contains("hidden")) {
        editPasswordButton.textContent = "Modifier le mot de passe";
    } else {
        editPasswordButton.textContent = "Cacher le mot de passe";
    }
});