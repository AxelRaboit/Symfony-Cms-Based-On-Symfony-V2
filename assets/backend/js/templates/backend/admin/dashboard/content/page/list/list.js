import { initSearchPage } from "../../../shared/search";

document.addEventListener('DOMContentLoaded', function () {
    initSearchPage({
        searchFormId: 'search-page',
        containerResetFormButtonId: 'container-reset-button-js',
        resetFormButtonId: 'refresh-page-list-js',
        searchFormInputId: 'search-page-input-js',
        suggestionsListId: 'search-page-suggestions',
        deleteBackendPageLinksSelector: '.link-delete-page-js'
    });
});
