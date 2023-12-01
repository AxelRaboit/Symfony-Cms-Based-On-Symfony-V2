import { initSearchPage } from "../../../shared/search";
import { initDeletionFromList } from "../../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-page-js', "Êtes-vous sûr de vouloir effectuer cette action ?")

    initSearchPage({
        searchFormId: 'search-page',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-page-list-js',
        searchFormInputId: 'search-page-input-js',
        suggestionsListId: 'search-page-suggestions',
        fetchUrl: '/backend/admin/content/page/ajax-search'
    });
});
