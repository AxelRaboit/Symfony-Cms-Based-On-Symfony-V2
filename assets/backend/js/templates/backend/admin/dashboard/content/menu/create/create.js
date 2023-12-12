const formSubmitButton = document.getElementById("form-create-menu-js");
const submitFormButton = document.getElementById("submit-button-create-menu-js");

submitFormButton.addEventListener('click', function (event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

