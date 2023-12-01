import { initSearchPage } from "../../../shared/search";
import { initDeletionFromList } from "../../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-page-type-js', "Êtes-vous sûr de vouloir effectuer cette action ?");

    initSearchPage({
        searchFormId: 'search-page-type',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-page-type-list-js',
        searchFormInputId: 'search-page-type-input-js',
        suggestionsListId: 'search-page-type-suggestions',
        fetchUrl: '/backend/admin/content/page-type/ajax-search'
    });
});
