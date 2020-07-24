function onTopDropdownClick(obj) {
  if ($(obj).data('clicked') == 'true') {
    location.href = $(obj).attr('href');
  }
}

function onDropdownMouseEnter(obj) {
  $(obj).data('clicked', 'true');
}

function onDropdownMouseLeave(obj) {
  $(obj).data('clicked', 'false');
}

$(document).ready(function () {
  $('.badge-custom').mouseenter(function (e) {
    hideBadges(e.toElement);
    $(e.toElement).show();

    var text = $(e.toElement).text().trim();
    if (text == 'O') {
      $(e.toElement).text('Oferta');
    }
    if (text == 'N') {
      $(e.toElement).text('Novedad');
    }
    if (text == 'A') {
      $(e.toElement).text('Almacén');
    }
  });
  $('.badge-custom').mouseleave(function (e) {
    var text = $(e.fromElement).text().trim();
    if (text == 'Oferta') {
      $(e.fromElement).text('O');
    }
    if (text == 'Novedad') {
      $(e.fromElement).text('N');
    }
    if (text == 'Almacén') {
      $(e.fromElement).text('A');
    }

    showBadges(e.fromElement);
  });

  $('#send-mail-button').click(function (e) {
    if (
      !$('#email_name').val() ||
      !$('#email_email').val() ||
      !$('#email_phone').val() ||
      !$('#email_text').val()
    ) {
      alert('Rellene todos los campos');
      e.preventDefault();
    }
  });

  $('#request-membership-button').click(function (e) {
    if (
      !$('#membership_request_firstName').val() ||
      !$('#membership_request_lastName').val() ||
      !$('#membership_request_email').val() ||
      !$('#membership_request_phone').val()
    ) {
      alert('Rellene todos los campos');
      e.preventDefault();
    }
  });

  $('#loginModal label').addClass('collapse');

  $('.conceptos-navbar-icon.conceptos-user-icon').click(function () {
    $('#loginModal #login-info').text('');
    $('#loginModal #login-info').hide();
  });

  $('.register-checkbox').click(function () {
    if ($(this).prop('checked')) {
      $('#login-form').hide();
      $('#register-form').show();

      FlagRegisterFieldsAsNonRequired();
    } else {
      $('#login-form').show();
      $('#register-form').hide();

      FlagRegisterFieldsAsNonRequired();
    }
  });

  function hideBadges(element) {
    $(element).parent().children('span.badge-custom').hide();
  }

  function showBadges(element) {
    $(element).parent().children('span.badge-custom').show();
  }

  function FlagRegisterFieldsAsNonRequired() {
    $('#fos_user_registration_form_first_name').attr('required', false);
    $('#fos_user_registration_form_last_name').attr('required', false);
    $('#fos_user_registration_form_mobile_number').attr('required', false);
    $('#fos_user_registration_form_home_number').attr('required', false);
    $('#fos_user_registration_form_address').attr('required', false);
  }
});
