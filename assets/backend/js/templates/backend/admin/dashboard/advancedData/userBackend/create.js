const formSubmitButton = document.getElementById("form-create-user-backend-js");
const submitFormButton = document.getElementById("submit-button-create-user-backend-js");

// On click on the submit button, we display a confirmation popup
submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});