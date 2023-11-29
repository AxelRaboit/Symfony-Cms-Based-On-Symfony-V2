// Confirmation message before submiting the form
// Using "windows." to make the function available in the global scope
export function importMediaModal() {
    openPopup();
};

function openPopup() {
    const popup = document.getElementById('create-media-modal');
    const cancelButton = document.getElementById('create-media-modal-cancel');
    cancelButton.addEventListener('click', closePopup);
    popup.classList.remove('hidden');
}

function closePopup() {
    const popup = document.getElementById('create-media-modal');
    popup.classList.add('hidden');
}