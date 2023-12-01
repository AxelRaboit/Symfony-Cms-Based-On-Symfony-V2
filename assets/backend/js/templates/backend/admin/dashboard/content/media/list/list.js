import { importMediaModal } from '../modal/import-media-modal.js';

const buttonCreateMediaImage = document.getElementById('create-media-js');
buttonCreateMediaImage.addEventListener('click', function (event) {
    event.preventDefault();

    importMediaModal();
});

