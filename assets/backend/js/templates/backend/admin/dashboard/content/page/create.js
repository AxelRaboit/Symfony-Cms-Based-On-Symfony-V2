import { galleryMediaModal, closePopup } from './modal/gallery-media-modal.js';

const formSubmitButton = document.getElementById("form-create-page-js");
const submitFormButton = document.getElementById("submit-button-create-page-js");

// On click on the submit button, we display a confirmation popup
submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

// Gallery TEST
document.addEventListener('DOMContentLoaded', function() {
    const bannerButton = document.getElementById('button-select-banner-js');
    const thumbnailButton = document.getElementById('button-select-thumbnail-js');
    const galleryModal = document.getElementById('gallery-media-modal');
    const selectedBanner = document.getElementById('selected-banner');
    const selectedThumbnail = document.getElementById('selected-thumbnail');

    bannerButton.addEventListener('click', function(event) {
        event.preventDefault();
        galleryMediaModal();
        currentSelection = 'banner';
    });

    thumbnailButton.addEventListener('click', function(event) {
        event.preventDefault();
        galleryMediaModal();
        currentSelection = 'thumbnail';
    });

    galleryModal.addEventListener('click', function(event) {
        let imageDiv = event.target.closest('div[data-id]');
        if (imageDiv) {
            handleImageSelection(imageDiv);
        }
    });

    selectedBanner.addEventListener('click', function() {
        selectedBanner.classList.add('hidden');
        unselectImage(this,'selected-banner-image-id');
    });

    selectedThumbnail.addEventListener('click', function() {
        selectedThumbnail.classList.add('hidden');
        unselectImage(this,'selected-thumbnail-image-id');
    });
});

let currentSelection = '';

function handleImageSelection(imageDiv) {
    const BANNER = 'banner';
    const THUMBNAIL = 'thumbnail';
    const selectedImageId = imageDiv.getAttribute('data-id');
    const selectedBanner = document.getElementById('selected-banner');
    const selectedThumbnail = document.getElementById('selected-thumbnail');
    const imageUrl = imageDiv.querySelector('img').src;

    if (currentSelection === BANNER) {
        selectedBanner.classList.remove('hidden');
        document.getElementById('selected-banner-image-id').value = selectedImageId;
        createAndInsertImage(imageUrl, 'selected-banner');
    } else if (currentSelection === THUMBNAIL) {
        selectedThumbnail.classList.remove('hidden');
        document.getElementById('selected-thumbnail-image-id').value = selectedImageId;
        createAndInsertImage(imageUrl, 'selected-thumbnail');
    }

    closePopup();
}

function createAndInsertImage(imageUrl, divId) {
    const imgElement = document.createElement('img');
    imgElement.src = imageUrl;
    imgElement.alt = 'Selected Image';
    imgElement.classList.add('w-64', 'h-96', 'object-contain');

    const selectedDiv = document.getElementById(divId);
    selectedDiv.innerHTML = '';
    selectedDiv.appendChild(imgElement);
}

function unselectImage(elementDOM, elementId) {
    document.getElementById(elementId).value = '';
    elementDOM.innerHTML = '';
}
