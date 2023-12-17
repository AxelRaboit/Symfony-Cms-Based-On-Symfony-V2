import { initSearchPage } from "../../shared/search";
import { initDeletionFromList } from "../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-website-js', "Êtes-vous sûr de vouloir effectuer cette action ?")

    initSearchPage({
        searchFormId: 'search-backend-website',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-backend-website-list-js',
        searchFormInputId: 'search-backend-website-input-js',
        suggestionsListId: 'search-website-suggestions',
        fetchUrl: '/backend/admin/advanced-data/website/ajax-search'
    });
});
