import {galleryMediaModal, closePopup} from './modal/gallery-media-modal.js';

const formSubmitButton = document.getElementById("form-create-page-js");
const submitFormButton = document.getElementById("submit-button-create-page-js");

submitFormButton.addEventListener('click', function (event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette action ?");
});

// Gallery Media Modal

document.addEventListener('DOMContentLoaded', function () {
    const bannerButton = document.getElementById('button-select-banner-js');
    const thumbnailButton = document.getElementById('button-select-thumbnail-js');
    const galleryModal = document.getElementById('gallery-media-modal');
    const selectedBanner = document.getElementById('selected-banner');
    const selectedThumbnail = document.getElementById('selected-thumbnail');

    bannerButton.addEventListener('click', function (event) {
        event.preventDefault();
        galleryMediaModal();
        currentSelection = 'banner';
    });

    thumbnailButton.addEventListener('click', function (event) {
        event.preventDefault();
        galleryMediaModal();
        currentSelection = 'thumbnail';
    });

    galleryModal.addEventListener('click', function (event) {
        let imageDiv = event.target.closest('div[data-id]');
        if (imageDiv) {
            handleImageSelection(imageDiv);
        }
    });

    selectedBanner.addEventListener('click', function () {
        selectedBanner.classList.add('hidden');
        unselectImage(this, 'selected-banner-image-id');
    });

    selectedThumbnail.addEventListener('click', function () {
        selectedThumbnail.classList.add('hidden');
        unselectImage(this, 'selected-thumbnail-image-id');
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

// Gallery Media Modal - Load more images
document.addEventListener('DOMContentLoaded', function() {
    const galleryContainer = document.getElementById('gallery-container');
    const galleryLoadMoreButton = document.getElementById('gallery-load-more');

    if (galleryLoadMoreButton) {
        galleryLoadMoreButton.addEventListener('click', function(event) {
            const nextPage = event.target.getAttribute('data-next-page');
            fetch(`/backend/admin/content/page/gallery/ajax?page=${nextPage}`)
                .then(response => response.json())
                .then(data => {
                    data.images.forEach(image => {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'w-full overflow-hidden rounded-md flex justify-center items-center border border-gray-300';
                        imageDiv.setAttribute('data-id', image.id);

                        const img = document.createElement('img');
                        img.src = image.url;
                        img.alt = image.alt;
                        img.className = 'object-contain max-h-full max-w-full cursor-pointer';

                        imageDiv.appendChild(img);
                        galleryContainer.appendChild(imageDiv);
                    });

                    if (data.nextPage) {
                        event.target.setAttribute('data-next-page', data.nextPage);
                    } else {
                        event.target.remove();
                    }
                })
                .catch(error => console.error('Error loading more images:', error));
        });
    }
});



