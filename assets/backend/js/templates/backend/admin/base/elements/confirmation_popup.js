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
