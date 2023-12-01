import { initSearchPage } from "../../../shared/search";
import { initDeletionFromList } from "../../../../base/elements/modal/confirmation-modal";

document.addEventListener('DOMContentLoaded', function () {

    initDeletionFromList('.link-delete-page-page-type-js', "Êtes-vous sûr de vouloir effectuer cette action ?")

    const pageTypeId = document.querySelector('[data-page-type-id]').dataset.pageTypeId;

    initSearchPage({
        searchFormId: 'search-page-page-type',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-page-page-type-list-js',
        searchFormInputId: 'search-page-page-type-input-js',
        suggestionsListId: 'search-page-page-type-suggestions',
        fetchUrl: `/backend/admin/content/page/page-type/${pageTypeId}/ajax-search`
    });
});
