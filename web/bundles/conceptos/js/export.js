$(document).ready(function () {

    var path = $('.progress').data('path');

    $('.current-element').text('Pantallas de la aplicación');
    ajax(path + '/app', 'POST', {}, function (response) {
        $('.app').css('color', 'green');
        $('.app .loading').addClass('hidden');
        $('.app .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 12.5);
        $('.progress-bar').css('width', '12.5%');
        exportColors(path, response.fileName);
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
});

function exportColors(path, fileName) {
    $('.current-element').text('Colores');
    var pathColor = path + '/color?file=' + fileName;
    ajax(pathColor, 'POST', {}, function (response) {
        $('.colors').css('color', 'green');
        $('.colors .loading').addClass('hidden');
        $('.colors .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 25);
        $('.progress-bar').css('width', '25%');
        exportMaterials(path, fileName);
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}

function exportMaterials(path, fileName) {
    $('.current-element').text('Materiales');
    var pathMaterial = path + '/material?file=' + fileName;
    ajax(pathMaterial, 'POST', {}, function (response) {
        $('.materials').css('color', 'green');
        $('.materials .loading').addClass('hidden');
        $('.materials .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 37.5);
        $('.progress-bar').css('width', '37.5%');
        exportCategories(path, fileName);
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}

function exportCategories(path, fileName) {
    $('.current-element').text('Categories');
    var pathCategory = path + '/category?file=' + fileName;
    ajax(pathCategory, 'POST', {}, function (response) {
        $('.categories').css('color', 'green');
        $('.categories .loading').addClass('hidden');
        $('.categories .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 50);
        $('.progress-bar').css('width', '50%');
        exportProducts(path, fileName, 0);
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}

function exportProducts(path, fileName, page) {
    $('.current-element').text('Productos');


    var pathProducts = path + '/product?file=' + fileName + '&page=' + page;
    ajax(pathProducts, 'POST', {}, function (response) {
        if (!response.last) {
            page++;
            exportProducts(path, fileName, page);
        } else {
            $('.products').css('color', 'green');
            $('.products .loading').addClass('hidden');
            $('.products .done').removeClass('hidden');
            $('.progress-bar').prop('aria-valuenow', 62.5);
            $('.progress-bar').css('width', '62.5%');
            exportOffers(path, fileName);
        }
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}

function exportOffers(path, fileName) {
    $('.current-element').text('Ofertas');
    var pathOffers = path + '/offer?file=' + fileName;
    ajax(pathOffers, 'POST', {}, function (response) {
        $('.offers').css('color', 'green');
        $('.offers .loading').addClass('hidden');
        $('.offers .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 75);
        $('.progress-bar').css('width', '75%');
        exportPhones(path, fileName);
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}
function exportPhones(path, fileName) {
    $('.current-element').text('Teléfonos');
    var pathPhones = path + '/phone?file=' + fileName;
    ajax(pathPhones, 'POST', {}, function (response) {
        $('.phones').css('color', 'green');
        $('.phones .loading').addClass('hidden');
        $('.phones .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 85.5);
        $('.progress-bar').css('width', '85.5%');
        exportConfig(path, fileName);
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}

function exportConfig(path, fileName) {
    $('.current-element').text('Configuración General');
    var pathConfig = path + '/config?file=' + fileName;
    ajax(pathConfig, 'POST', {}, function (response) {
        $('.config').css('color', 'green');
        $('.config .loading').addClass('hidden');
        $('.config .done').removeClass('hidden');
        $('.progress-bar').prop('aria-valuenow', 100);
        $('.progress-bar').css('width', '100%');
        window.location = $('.progress').data('list');
    }, function (error) {
        alert('Ha ocurrido un error exportando la base de datos');
    });
}
