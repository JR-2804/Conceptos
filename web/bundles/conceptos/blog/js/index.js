$(document).ready(function () {
    $('#term_search').on('search change keyup', function () {
        var text = $(this).val();
        $('.post').highlite({
            text: text
        });
    });
    if ($('#term_search').val()) {
        $('#term_search').trigger('change');
    }
});
