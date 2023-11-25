document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-backend-user');
    const searchFormInput = document.getElementById('search-backend-user-input-js');
    const containerResetFormButton = document.getElementById('container-reset-button-js');
    const resetFormButton = document.getElementById('refresh-backend-user-list-js');

    resetFormButton.addEventListener('click', function (e) {
        searchFormInput.value = '';
        containerResetFormButton.classList.add('hidden');
        searchForm.submit();
    });
});