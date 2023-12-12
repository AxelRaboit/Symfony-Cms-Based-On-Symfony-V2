const formSubmitButton = document.getElementById("form-edit-menu-js");
const submitFormButton = document.getElementById("submit-button-edit-menu-js");

submitFormButton.addEventListener('click', function (event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

