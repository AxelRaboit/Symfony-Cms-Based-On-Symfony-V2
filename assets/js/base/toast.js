document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('.close-toast-button').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.toast').remove();
        });
    });

    document.querySelectorAll('.toast').forEach(toast => {
        setTimeout(function () {
            toast.remove();
        }, 10000); // 10 secondes
    });
});
