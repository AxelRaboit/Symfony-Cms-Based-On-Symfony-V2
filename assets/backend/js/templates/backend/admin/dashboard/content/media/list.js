import { importMediaModal } from '../../../base/elements/modal/import-media-modal.js';

// Create image
const buttonCreateMediaImage = document.getElementById('create-media-js');

buttonCreateMediaImage.addEventListener('click', function (event) {
    event.preventDefault();

    importMediaModal();
});

