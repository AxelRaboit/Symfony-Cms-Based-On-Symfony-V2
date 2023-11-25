document.addEventListener('DOMContentLoaded', function () {
    const searchFormInput = document.getElementById('search-backend-user-input-js');
    const searchFormButton = document.getElementById('search-backend-user-submit-button-js');
    const containerResetFormButton = document.getElementById('container-reset-button-js');
    const resetFormButton = document.getElementById('reset-search-backend-user-submit-button-js');

        searchFormInput.value = '';

        searchFormInput.addEventListener('input', function (e) {
            if (e.target.value.length > 0) {
                containerResetFormButton.classList.remove('hidden');
            } else {
                containerResetFormButton.classList.add('hidden');
            }
        });

    resetFormButton.addEventListener('click', function (e) {
        searchFormInput.value = '';
        containerResetFormButton.classList.add('hidden');
    });
});