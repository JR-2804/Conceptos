$(document).ready(function () {
    if ($('#flash-messages').length > 0) {
        setTimeout(function () {
            $('#flash-messages').hide('slow');
        }, 5000);
    }
});
