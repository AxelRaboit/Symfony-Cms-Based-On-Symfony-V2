document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-backend-user');
    const searchFormInput = document.getElementById('search-backend-user-input-js');
    const containerResetFormButton = document.getElementById('container-reset-button-js');
    const resetFormButton = document.getElementById('refresh-backend-user-list-js');

    resetFormButton.addEventListener('click', function (e) {
        searchFormInput.value = '';
        containerResetFormButton.classList.add('hidden');
        searchForm.submit();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-backend-user-input-js');
    const suggestionsList = document.getElementById('search-suggestions');

    searchInput.addEventListener('input', function (e) {
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

    function updateSuggestionsList(users) {
        suggestionsList.innerHTML = ''; // Delete all the current suggestions

        users.forEach(user => {
            const listItem = document.createElement('li');
            listItem.textContent = user.label;
            listItem.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer');

            listItem.addEventListener('click', () => {
                searchInput.value = user.label; // Update the input with the selected value
                suggestionsList.innerHTML = ''; // Delete suggestions
                suggestionsList.classList.add('hidden');
                // We can also trigger a search or any other action here
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

