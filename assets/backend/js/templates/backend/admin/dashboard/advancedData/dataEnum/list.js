import { initSearchPage } from "../../shared/search";
import { initDeletionFromList } from "../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-data-enum-js', "Êtes-vous sûr de vouloir effectuer cette action ?")

    initSearchPage({
        searchFormId: 'search-backend-data-enum',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-backend-data-enum-list-js',
        searchFormInputId: 'search-backend-data-enum-input-js',
        suggestionsListId: 'search-data-enum-suggestions',
        fetchUrl: '/backend/admin/advanced-data/data-enum/ajax-search'
    });
});
