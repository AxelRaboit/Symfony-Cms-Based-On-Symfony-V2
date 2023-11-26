document.addEventListener('DOMContentLoaded', function () {

    // Variables
    const searchForm = document.getElementById('search-backend-user');
    const containerResetFormButton = document.getElementById('container-reset-button-js');
    const resetFormButton = document.getElementById('refresh-backend-user-list-js');
    const searchFormInput = document.getElementById('search-backend-user-input-js');
    const suggestionsList = document.getElementById('search-suggestions');

    // This function is used to display the reset button when the user click on it, it will reset the search form and display all users
    // This button is called "Afficher tous les utilisateurs"
    resetFormButton.addEventListener('click', function () {
        searchFormInput.value = '';
        containerResetFormButton.classList.add('hidden');
        searchForm.submit();
    });

    // Check if the click is outside the suggestions list and the search input and hide the suggestions list if it is the case
    document.addEventListener('click', function (event) {
        if (!searchFormInput.contains(event.target) && !suggestionsList.contains(event.target)) {
            suggestionsList.classList.add('hidden');
        }
    });

    // This function is used to fetch the users from the database and display them in the suggestions list according to the search term
    searchFormInput.addEventListener('input', function (e) {
        const searchTerm = e.target.value;

        if (searchTerm.length >= 2) { // Begin search only if search term is at least 2 characters long
            fetch(`/backend/admin/user/backend/ajax-search?term=${searchTerm}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && Array.isArray(data)) {
                        updateSuggestionsList(data);
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

    // This function is used to display the results of the search in the suggestions list
    function updateSuggestionsList(users) {
        const searchForm = document.getElementById('search-backend-user');

        suggestionsList.innerHTML = ''; // Delete all the current suggestions

        users.forEach(user => {
            const listItem = document.createElement('li');
            listItem.textContent = user.label;
            listItem.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer');

            listItem.addEventListener('click', () => {
                searchFormInput.value = user.label; // Update the input with the selected value
                suggestionsList.innerHTML = ''; // Delete suggestions
                suggestionsList.classList.add('hidden');

                // Submit the form when a suggestion is clicked
                searchForm.submit();
            });

            suggestionsList.appendChild(listItem);
        });

        if (users.length > 0) {
            suggestionsList.classList.remove('hidden');
        } else {
            suggestionsList.classList.add('hidden');
        }
    }
});
