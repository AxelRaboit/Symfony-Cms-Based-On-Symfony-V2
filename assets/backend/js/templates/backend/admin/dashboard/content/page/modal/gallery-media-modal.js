export function galleryMediaModal() {
    openPopup();
}

function openPopup() {
    const popup = document.getElementById('gallery-media-modal');
    const cancelButton = document.getElementById('gallery-media-modal-cancel');
    cancelButton.addEventListener('click', closePopup);
    popup.classList.remove('hidden');
}

export function closePopup() {
    const popup = document.getElementById('gallery-media-modal');
    popup.classList.add('hidden');
}