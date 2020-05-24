$(document).ready(function() {
  $(".accept-external-request-button").click(function() {
    var path = $(this).data("path");
    ajax(
      path,
      "POST",
      {},
      function(response) {
        if (response == -1) {
          error();
          return;
        }
        $(".external-request[data-id='" + response + "']").hide();
        success();
      },
      function() {
        error();
      }
    );
  });

  updateCounters();

  function success() {
    $.toast({
      text: "Pedido externo aceptado correctamente",
      showHideTransition: "fade",
      bgColor: "#c2b930",
      textColor: "#3f3c03",
      allowToastClose: true,
      hideAfter: 3000,
      stack: 5,
      textAlign: "center",
      position: "mid-center",
      icon: "success",
      heading: "Correcto"
    });
  }

  function error() {}

  function updateCounters() {
    $(".external-request").each(function() {
      var id = $(this).data("id");
      var days = $(this).data("remaining-days");
      var hours = $(this).data("remaining-hours");
      var minutes = $(this).data("remaining-minutes");

      var remaining =
        new Date(0, 0, 0, 72) - new Date(0, 0, 0, days * 24 + hours, minutes);

      if (remaining < 0) {
        return;
      }

      var remainingDays = Math.floor(remaining / 1000 / 60 / 60 / 24);
      remaining -= remainingDays * 1000 * 60 * 60 * 24;
      var remainingHours = Math.floor(remaining / 1000 / 60 / 60);

      var text = "Restante: ";
      if (remainingDays > 0) {
        text += remainingDays + "d ";
      }
      text += remainingHours + "h";

      $(".external-request[data-id='" + id + "']").show();
      $(".external-request[data-id='" + id + "'] .remaining-time").text(text);
    });
  }
});
