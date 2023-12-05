const formSubmitButton = document.getElementById("form-edit-page-type-js");
const submitFormButton = document.getElementById("submit-button-edit-page-type-js");

submitFormButton.addEventListener('click', function (event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

