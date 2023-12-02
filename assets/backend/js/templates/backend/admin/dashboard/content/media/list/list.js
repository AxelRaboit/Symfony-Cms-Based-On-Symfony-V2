import { importMediaModal } from '../modal/import-media-modal.js';
import {initDeletionFromList} from "../../../../base/elements/modal/confirmation-modal";

initDeletionFromList('.link-delete-media-js', "Êtes-vous sûr de vouloir effectuer cette action ?")

const buttonCreateMediaImage = document.getElementById('create-media-js');

buttonCreateMediaImage.addEventListener('click', function (event) {
    event.preventDefault();

    importMediaModal();
});

