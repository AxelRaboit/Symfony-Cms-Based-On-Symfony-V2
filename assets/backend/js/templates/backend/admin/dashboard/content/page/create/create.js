import { initGallery } from '../modal/gallery-media-modal.js';

const formSubmitButton = document.getElementById("form-create-page-js");
const submitFormButton = document.getElementById("submit-button-create-page-js");

submitFormButton.addEventListener('click', function (event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

initGallery();
