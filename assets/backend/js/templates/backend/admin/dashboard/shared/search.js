/**
 * @param element
 * @param callback
 */
export function handleClickEvent(element, callback) {
    element.addEventListener('click', function (event) {
        callback(event);
    });
}

/**
 * @param element
 * @param callback
 */
export function handleInputEvent(element, callback) {
    element.addEventListener('input', function (event) {
        callback(event);
    });
}

/**
 * This function is used to display the results of the search in the suggestions list
 * @param data (Data fetched from the database)
 * @param formId (Id of the search form)
 * @param suggestionsList (Suggestions list ul tag element)
 * @param searchFormInput (Search input tag element)
 */
export function updateSuggestionsList(
    data,
    formId,
    suggestionsList,
    searchFormInput,
) {
    const searchForm = document.getElementById(formId);

    suggestionsList.innerHTML = ''; // Delete all the current suggestions

    data.forEach(item => {
        const listItem = document.createElement('li');
        listItem.textContent = item.label;
        listItem.classList.add('p-2', 'text-gray-800', 'hover:bg-gray-100', 'hover:dark:bg-gray-100', 'hover:dark:text-gray-800', 'cursor-pointer', 'bg-white', 'dark:bg-gray-800', 'dark:text-white');

        listItem.addEventListener('click', () => {
            searchFormInput.value = item.label; // Update the input with the selected value
            suggestionsList.innerHTML = ''; // Delete suggestions
            suggestionsList.classList.add('hidden');

            // Submit the form when a suggestion is clicked
            searchForm.submit();
        });

        suggestionsList.appendChild(listItem);
    });

    if (data.length > 0) {
        suggestionsList.classList.remove('hidden');
    } else {
        suggestionsList.classList.add('hidden');
    }
}