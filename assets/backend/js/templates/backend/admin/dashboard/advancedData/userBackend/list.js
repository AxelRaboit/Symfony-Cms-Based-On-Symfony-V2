import {handleClickEvent, handleInputEvent, updateSuggestionsList} from "../../shared/search";

document.addEventListener('DOMContentLoaded', function () {

    // Variables
    const searchForm = document.getElementById('search-backend-user');
    const containerResetFormButton = document.getElementById('container-reset-button-js');
    const resetFormButton = document.getElementById('refresh-backend-user-list-js');
    const searchFormInput = document.getElementById('search-backend-user-input-js');
    const suggestionsList = document.getElementById('search-backend-user-suggestions');
    const deleteBackendUserLinks = document.querySelectorAll('.link-delete-backend-user-js');

    // This function is used to display the confirmation popup when the user click on the delete button from the list
    Array.from(deleteBackendUserLinks).forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const deleteUrl = this.href;
            attachConfirmationToButton(deleteUrl, "Êtes-vous sûr de vouloir supprimer ?");
        });
    });

    // This function is used to display the reset button when the user click on it, it will reset the search form and display all users
    // This button is called "Afficher tous les utilisateurs"
    handleClickEvent(resetFormButton, function () {
        searchFormInput.value = '';
        containerResetFormButton.classList.add('hidden');
        searchForm.submit();
    });

    // Check if the click is outside the suggestions list and the search input and hide the suggestions list if it is the case
    handleClickEvent(document, function (event) {
        if (!searchFormInput.contains(event.target) && !suggestionsList.contains(event.target)) {
            suggestionsList.classList.add('hidden');
        }
    });

    // When we click on the search input, we display the current suggestions list
    handleClickEvent(document, function (event) {
        if (searchFormInput.contains(event.target)) {
            suggestionsList.classList.remove('hidden');
        }
    });

    // This function is used to fetch the users from the database and display them in the suggestions list according to the search term
    handleInputEvent(searchFormInput, function (event) {
        const searchTerm = event.target.value;

        if (searchTerm.length >= 1) { // Begin search only if search term is at least 1 characters long
            fetch(`/backend/admin/advanced-data/user-backend/ajax-search?term=${searchTerm}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && Array.isArray(data)) {
                        updateSuggestionsList(
                            data,
                            'search-backend-user',
                            suggestionsList,
                            searchFormInput
                        );
                    } else {
                        console.error('Invalid data format received:', data);
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        } else {
            suggestionsList.innerHTML = ''; // Delete suggestions if search term is too short
            suggestionsList.classList.add('hidden');
        }
    });
});
