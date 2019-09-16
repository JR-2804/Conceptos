$(document).ready(function () {
    $('.btn-print').click(function () {
        $.print();
    });

    $('.sharetastic').sharetastic({
        services: {
            twitter: false,
            pinterest: false,
            linkedin: false,
            googleplus: false,
            tumblr: false,
            email: false,
            whatsapp: false,
            print: false
        }
    });
});
