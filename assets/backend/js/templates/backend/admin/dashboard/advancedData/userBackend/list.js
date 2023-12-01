import { initSearchPage } from "../../shared/search";

document.addEventListener('DOMContentLoaded', function () {

    const deleteBackendUserLinks = document.querySelectorAll('.link-delete-backend-user-js');

    /**
     * This function is used to display the confirmation modal when the user click on the delete button from the list
     */
    Array.from(deleteBackendUserLinks).forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;
            attachConfirmationToButton(deleteUrl, "Êtes-vous sûr de vouloir effectuer cette action ?");
        });
    });

    initSearchPage({
        searchFormId: 'search-backend-user',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-backend-user-list-js',
        searchFormInputId: 'search-backend-user-input-js',
        suggestionsListId: 'search-backend-user-suggestions',
        fetchUrl: '/backend/admin/advanced-data/user-backend/ajax-search'
    });
});
