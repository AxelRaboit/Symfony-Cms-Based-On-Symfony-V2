import {galleryMediaModal, closePopup, initGallery} from '../modal/gallery-media-modal.js';

const formSubmitButton = document.getElementById("form-edit-page-js");
const submitFormButton = document.getElementById("submit-button-edit-page-js");

// On click on the submit button, we display a confirmation popup
submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

initGallery();