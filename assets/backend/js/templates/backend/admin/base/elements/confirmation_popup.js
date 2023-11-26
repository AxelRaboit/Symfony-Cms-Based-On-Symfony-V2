// Using "windows." to make the function available in the global scope
window.attachConfirmationToForm = function(formId, confirmationMessage) {
    const form = document.getElementById(formId);
    if (!form) {
        console.error(`Form with id "${formId}" not found`);
        return;
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        openPopup('Confirmation', confirmationMessage, function() {
            form.submit();
        });
    });
};

function openPopup(title, message, onConfirm) {
    const popup = document.getElementById('confirmation-popup');
    const titleElement = document.getElementById('confirmation-popup-title');
    const messageElement = document.getElementById('confirmation-popup-message');
    const cancelButton = document.getElementById('confirmation-popup-cancel');
    const confirmButton = document.getElementById('confirmation-popup-confirm');

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
    const popup = document.getElementById('confirmation-popup');
    popup.classList.add('hidden');
}
