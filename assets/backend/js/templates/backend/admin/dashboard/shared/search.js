/**
 * Attaches a click event to an element.
 * @param element - The DOM element to attach the event.
 * @param callback - The callback function to execute on click.
 */
export function handleClickEvent(element, callback) {
    element.addEventListener('click', function (event) {
        callback(event);
    });
}

/**
 * Attaches an input event to an element.
 * @param element - The DOM element to attach the event.
 * @param callback - The callback function to execute on input.
 */
export function handleInputEvent(element, callback) {
    element.addEventListener('input', function (event) {
        callback(event);
    });
}

/**
 * Updates the suggestions list based on fetched data.
 * @param data - Data fetched from the database.
 * @param searchForm
 * @param suggestionsList - Suggestions list ul tag element.
 * @param searchFormInput - Search input tag element.
 */
export function updateSuggestionsList(data, searchForm, suggestionsList, searchFormInput) {
    suggestionsList.innerHTML = ''; // Clear all the current suggestions

    data.forEach(item => {
        const listItem = document.createElement('li');
        listItem.textContent = item.label;
        listItem.classList.add('p-2', 'text-gray-800', 'hover:bg-gray-100', 'hover:dark:bg-gray-100', 'hover:dark:text-gray-800', 'cursor-pointer', 'bg-white', 'dark:bg-gray-800', 'dark:text-white');

        listItem.addEventListener('click', () => {
            searchFormInput.value = item.label; // Update the input with the selected value
            suggestionsList.innerHTML = ''; // Clear suggestions
            suggestionsList.classList.add('hidden');
            searchForm.submit(); // Submit the form
        });

        suggestionsList.appendChild(listItem);
    });

    suggestionsList.classList.toggle('hidden', data.length === 0);
}

/**
 * Initializes the search page functionality.
 * @param config - Configuration object containing element IDs and selectors.
 */
export function initSearchPage(config) {
    const {
        searchFormId,
        containerResetFormButtonId,
        resetFormButtonId,
        searchFormInputId,
        suggestionsListId,
        fetchUrl
    } = config;

    const searchForm = document.getElementById(searchFormId);
    const containerResetFormButton = document.getElementById(containerResetFormButtonId);
    const resetFormButton = document.getElementById(resetFormButtonId);
    const searchFormInput = document.getElementById(searchFormInputId);
    const suggestionsList = document.getElementById(suggestionsListId);

    setupResetButton(resetFormButton, searchFormInput, containerResetFormButton, searchForm);
    setupSearchFormInput(searchFormInput, suggestionsList, searchForm, fetchUrl);
}

function setupResetButton(resetFormButton, searchFormInput, containerResetFormButton, searchForm) {
    handleClickEvent(resetFormButton, function () {
        searchFormInput.value = '';
        containerResetFormButton.classList.add('hidden');
        searchForm.submit();
    });
}

function setupSearchFormInput(searchFormInput, suggestionsList, searchForm, fetchUrl) {
    handleInputEvent(searchFormInput, function (event) {
        const searchTerm = event.target.value;

        if (searchTerm.length >= 1) { // Begin search only if search term is at least 1 characters long
            fetchSuggestions(searchTerm, suggestionsList, searchFormInput, searchForm, fetchUrl);
        } else {
            suggestionsList.innerHTML = ''; // Clear suggestions if search term is too short
            suggestionsList.classList.add('hidden');
        }
    });

    handleClickEvent(document, function (event) {
        // Hide suggestions if clicked outside
        if (!searchFormInput.contains(event.target) && !suggestionsList.contains(event.target)) {
            suggestionsList.classList.add('hidden');
        }
    });

    handleClickEvent(searchFormInput, function () {
        // Show suggestions when input is focused
        if (suggestionsList.children.length > 0) {
            suggestionsList.classList.remove('hidden');
        }
    });
}

function fetchSuggestions(searchTerm, suggestionsList, searchFormInput, searchForm, fetchUrl) {
    fetch(`${fetchUrl}?term=${searchTerm}`)
        .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok'))
        .then(data => {
            if (data && Array.isArray(data)) {
                updateSuggestionsList(
                    data,
                    searchForm,
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
}
