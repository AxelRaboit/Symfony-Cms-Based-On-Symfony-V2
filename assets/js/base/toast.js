var toastLive = document.getElementById('liveToast');

if (toastLive) {
    toastLive.classList.add('show');

    if (toastLive.classList.contains('show')) {
        setTimeout(function () {
            toastLive.classList.remove('show');
        }, 10000);
    }
}
