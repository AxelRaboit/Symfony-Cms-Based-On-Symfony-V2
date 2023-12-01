import { initSearchPage } from "../../shared/search";
import { initDeletionFromList } from "../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-backend-user-js', "Êtes-vous sûr de vouloir effectuer cette action ?")

    initSearchPage({
        searchFormId: 'search-backend-user',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-backend-user-list-js',
        searchFormInputId: 'search-backend-user-input-js',
        suggestionsListId: 'search-backend-user-suggestions',
        fetchUrl: '/backend/admin/advanced-data/user-backend/ajax-search'
    });
});
