const formSubmitButton = document.getElementById("form-create-data-enum-js");
const submitFormButton = document.getElementById("submit-button-create-data-enum-js");

// On click on the submit button, we display a confirmation popup
submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});