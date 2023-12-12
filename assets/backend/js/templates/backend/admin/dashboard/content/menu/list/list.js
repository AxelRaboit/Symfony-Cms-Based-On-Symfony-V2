import { initSearchPage } from "../../../shared/search";
import { initDeletionFromList } from "../../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-menu-js', "Êtes-vous sûr de vouloir effectuer cette action ?");

    initSearchPage({
        searchFormId: 'search-menu',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-menu-list-js',
        searchFormInputId: 'search-menu-input-js',
        suggestionsListId: 'search-menu-suggestions',
        fetchUrl: '/backend/admin/content/menu/ajax-search'
    });
});
