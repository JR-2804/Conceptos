$(document).ready(function () {
});

function init() {
    $('#request-main').val(data.main);
    $('#request-secondary').val(data.secondary);
}

function generateData() {
    var data = {
        main: $('#request-main').val(),
        secondary: $('#request-secondary').val()
    };
    return data;
}

function validateSubmitData() {
    return true;
}
