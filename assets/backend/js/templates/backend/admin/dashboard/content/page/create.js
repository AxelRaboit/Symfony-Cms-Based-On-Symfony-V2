const formSubmitButton = document.getElementById("form-create-page-js");
const submitFormButton = document.getElementById("submit-button-create-page-js");

// On click on the submit button, we display a confirmation popup
submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});