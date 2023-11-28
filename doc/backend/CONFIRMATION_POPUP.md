# USAGE OF CONFIRMATION POPUP

## FROM A FORM

### IN THE TWIG TEMPLATE

#### 1. Define an id for the form
```html
{{ form_start(form, {'attr': {'id': 'form-edit-user-backend-js'}}) }}
```

#### 2. Define an id for the submit button
```html
<button type="submit"
        id="submit-button-edit-user-backend-js"
        class="w-full text-xs px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
    Modifier
</button>
```

### IN THE JAVASCRIPT FILE

#### 1. Use the function attachConfirmationToForm
```js
const formSubmitButton = document.getElementById("form-edit-user-backend-js");
const submitFormButton = document.getElementById("submit-button-edit-user-backend-js");

submitFormButton.addEventListener('click', function(event) {
    event.preventDefault();
    attachConfirmationToForm(formSubmitButton, "Êtes-vous sûr de vouloir effectuer cette modification ?");
});
```

## FROM A LINK (WHICH IS IN A LIST (LOOP))

### IN THE TWIG TEMPLATE

#### 1. Define a class for the link
```html
<a href="{{ path('app_backend_user_backend_delete', {'id': user.id}) }}"
    class="link-delete-backend-user-js font-medium text-white dark:text-white rounded p-2 bg-red-600 hover:bg-red-500 my-1"><i
    class="fa-solid fa-trash"></i>
</a>
```

### IN THE JAVASCRIPT FILE

#### 1. Use the function attachConfirmationToLink
```js
const deleteBackendUserLinks = document.querySelectorAll('.link-delete-backend-user-js');

Array.from(deleteBackendUserLinks).forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const deleteUrl = this.href;
        attachConfirmationToButton(deleteUrl, "Êtes-vous sûr de vouloir supprimer ?");
    });
});
```