import { initSearchPage } from "../../../shared/search";

document.addEventListener('DOMContentLoaded', function () {

    const deleteBackendPageLinks = document.querySelectorAll('.link-delete-page-type-js');

    /**
     * This function is used to display the confirmation modal when the user click on the delete button from the list
     */
    Array.from(deleteBackendPageLinks).forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;
            attachConfirmationToButton(deleteUrl, "Êtes-vous sûr de vouloir effectuer cette action ?");
        });
    });

    initSearchPage({
        searchFormId: 'search-page-type',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-page-type-list-js',
        searchFormInputId: 'search-page-type-input-js',
        suggestionsListId: 'search-page-type-suggestions',
        fetchUrl: '/backend/admin/content/page-type/ajax-search'
    });
});
