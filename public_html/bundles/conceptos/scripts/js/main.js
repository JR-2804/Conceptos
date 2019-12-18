function onTopDropdownClick(obj) {
  if ($(obj).data("clicked") == "true") {
    location.href = $(obj).attr("href");
  }
}

function onBottomDropdownClick(obj) {
  location.href = $(obj).attr("href");
}

function onDropdownMouseEnter(obj) {
  $(obj).data("clicked", "true");
}

function onDropdownMouseLeave(obj) {
  $(obj).data("clicked", "false");
}

$(document).ready(function() {
  $("#social-networks").mouseenter(function() {
    $("#social-networks").removeClass("sn-opacity");
  });
  $("#social-networks").mouseleave(function() {
    $("#social-networks").addClass("sn-opacity");
  });

  $(".badge-custom").mouseenter(function(e) {
    hideBadges(e.toElement);
    $(e.toElement).show();

    var text = $(e.toElement)
      .text()
      .trim();
    if (text == "O") {
      $(e.toElement).text("Oferta");
    }
    if (text == "N") {
      $(e.toElement).text("Novedad");
    }
    if (text == "A") {
      $(e.toElement).text("Almacén");
    }
  });
  $(".badge-custom").mouseleave(function(e) {
    var text = $(e.fromElement)
      .text()
      .trim();
    if (text == "Oferta") {
      $(e.fromElement).text("O");
    }
    if (text == "Novedad") {
      $(e.fromElement).text("N");
    }
    if (text == "Almacén") {
      $(e.fromElement).text("A");
    }

    showBadges(e.fromElement);
  });

  $("#send-mail-button").click(function(e) {
    if (
      !$("#email_name").val() ||
      !$("#email_lastName").val() ||
      !$("#email_email").val() ||
      !$("#email_phone").val() ||
      !$("#email_text").val()
    ) {
      alert("Rellene todos los campos");
      e.preventDefault();
    }
  });

  $("#request-membership-button").click(function(e) {
    if (
      !$("#membership_request_firstName").val() ||
      !$("#membership_request_lastName").val() ||
      !$("#membership_request_email").val() ||
      !$("#membership_request_phone").val()
    ) {
      alert("Rellene todos los campos");
      e.preventDefault();
    }
  });

  $(".gift-cart-button").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    $("#gift-card-modal-toggle").click();

    ajax(
      $(this).data("path"),
      "POST",
      {},
      function(response) {
        var count = response.count;
        var title = "Tarjeta añadida al carrito correctamente";
        if (response.exist) {
          title = "La tarjeta seleccionada ya está en su carrito de compras";
        }
        $(".badge-shop-cart").text(count);
        $.toast({
          text: title,
          showHideTransition: "fade",
          bgColor: "#f7ed4a",
          textColor: "#3f3c03",
          allowToastClose: true,
          hideAfter: 3000,
          stack: 5,
          textAlign: "center",
          position: "mid-center",
          icon: "success",
          heading: "Correcto"
        });
      },
      function() {
        $.toast({
          text: "Ha ocurrido un error añadiendo la tarjeta al carrito",
          showHideTransition: "fade",
          bgColor: "#f7ed4a",
          textColor: "#3f3c03",
          allowToastClose: true,
          hideAfter: 3000,
          stack: 5,
          textAlign: "center",
          position: "mid-center",
          icon: "error",
          heading: "Error"
        });
      }
    );
  });

  $("#loginModal label").addClass("collapse");

  $(".register-checkbox input").change(function() {
    if ($(this).prop("checked")) {
      $("#login-form").addClass("collapse");
      $("#register-form").removeClass("collapse");

      FlagRegisterFieldsAsNonRequired();
    } else {
      $("#login-form").removeClass("collapse");
      $("#register-form").addClass("collapse");

      FlagRegisterFieldsAsNonRequired();
    }
  });

  function hideBadges(element) {
    $(element)
      .parent()
      .children("span.badge-custom")
      .hide();
  }

  function showBadges(element) {
    $(element)
      .parent()
      .children("span.badge-custom")
      .show();
  }

  function FlagRegisterFieldsAsNonRequired() {
    $("#fos_user_registration_form_first_name").attr("required", false);
    $("#fos_user_registration_form_last_name").attr("required", false);
    $("#fos_user_registration_form_mobile_number").attr("required", false);
    $("#fos_user_registration_form_home_number").attr("required", false);
    $("#fos_user_registration_form_address").attr("required", false);
  }
});
