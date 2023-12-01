// Confirmation message before submiting the form
// Using "windows." to make the function available in the global scope
window.attachConfirmationToForm = function(form, confirmationMessage) {
    openPopup('Confirmation', confirmationMessage, function() {
        form.submit();
    });
};

window.attachConfirmationToButton = function(url, confirmationMessage) {
    openPopup('Confirmation', confirmationMessage, function() {
        window.location.href = url;
    });
};

/**
 * @param {string} deleteLinksSelector
 * @param {string} confirmationMessage
 */
export function initDeletionFromList(deleteLinksSelector, confirmationMessage) {
    const deleteButtons = document.querySelectorAll(deleteLinksSelector);

    Array.from(deleteButtons).forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;
            attachConfirmationToButton(deleteUrl, confirmationMessage);
        });
    });
}

function openPopup(title, message, onConfirm) {
    const popup = document.getElementById('confirmation-modal');
    const titleElement = document.getElementById('confirmation-modal-title');
    const messageElement = document.getElementById('confirmation-modal-message');
    const cancelButton = document.getElementById('confirmation-modal-cancel');
    const confirmButton = document.getElementById('confirmation-modal-confirm');

    titleElement.textContent = title;
    messageElement.textContent = message;

    cancelButton.addEventListener('click', closePopup);

    confirmButton.addEventListener('click', function() {
        closePopup();
        if (onConfirm) onConfirm();
    });

    popup.classList.remove('hidden');
}

function closePopup() {
    const popup = document.getElementById('confirmation-modal');
    popup.classList.add('hidden');
}
