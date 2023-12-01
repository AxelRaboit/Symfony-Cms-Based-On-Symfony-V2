import { initSearchPage } from "../../shared/search";

document.addEventListener('DOMContentLoaded', function () {

    const deleteDataEnumLinks = document.querySelectorAll('.link-delete-data-enum-js');

    /**
     * This function is used to display the confirmation modal when the user click on the delete button from the list
     */
    Array.from(deleteDataEnumLinks).forEach(button => {
        const dataIsSystem = button.dataset.isSystem;
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;
            if (dataIsSystem === "false") {
                attachConfirmationToButton(deleteUrl, "Êtes-vous sûr de vouloir effectuer cette action ?");
            }
        });
    });

    initSearchPage({
        searchFormId: 'search-backend-data-enum',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-backend-data-enum-list-js',
        searchFormInputId: 'search-backend-data-enum-input-js',
        suggestionsListId: 'search-data-enum-suggestions',
        fetchUrl: '/backend/admin/advanced-data/data-enum/ajax-search'
    });
});
