$(document).ready(function () {
  $(".accept-external-request-button").click(function () {
    var path = $(this).data("path");
    ajax(
      path,
      "POST",
      {},
      function (response) {
        if (response == -1) {
          error();
          return;
        }
        $(".external-request[data-id='" + response + "']").hide();
        success();
      },
      function () {
        error();
      }
    );
  });

  updateCounters();

  function success() {
    $.toast({
      text: "Orden de compra aceptada correctamente",
      showHideTransition: "fade",
      bgColor: "#c2b930",
      textColor: "#3f3c03",
      allowToastClose: true,
      hideAfter: 3000,
      stack: 5,
      textAlign: "center",
      position: "mid-center",
      icon: "success",
      heading: "Correcto",
    });
  }

  function error() {}

  function updateCounters() {
    $(".external-request").each(function () {
      var id = $(this).data("id");
      var days = $(this).data("remaining-days");
      var hours = $(this).data("remaining-hours");
      var minutes = $(this).data("remaining-minutes");
      var seconds = $(this).data("remaining-seconds");

      var countdownDate = new Date(
        0,
        0,
        0,
        days * 24 + hours,
        minutes,
        seconds
      );

      setInterval(function () {
        var distance = new Date(0, 0, 0, 72) - countdownDate;

        if (distance < 0) {
          return;
        }

        var remainingDays = Math.floor(distance / 1000 / 60 / 60 / 24);
        distance -= remainingDays * 1000 * 60 * 60 * 24;
        var remainingHours = Math.floor(distance / 1000 / 60 / 60);
        distance -= remainingHours * 1000 * 60 * 60;
        var remainingMinutes = Math.floor(distance / 1000 / 60);
        distance -= remainingMinutes * 1000 * 60;
        var remainingSeconds = Math.floor(distance / 1000);

        if (remainingDays > 0) {
          remainingHours += remainingDays * 24;
        }
        if (remainingDays < 10) {
          remainingDays = "0" + remainingDays;
        }
        if (remainingHours < 10) {
          remainingHours = "0" + remainingHours;
        }
        if (remainingMinutes < 10) {
          remainingMinutes = "0" + remainingMinutes;
        }
        if (remainingSeconds < 10) {
          remainingSeconds = "0" + remainingSeconds;
        }

        $(".external-request[data-id='" + id + "']").show();
        $(".external-request[data-id='" + id + "'] .remaining-time").text(
          "Este pedido expira en: " +
            remainingHours +
            ":" +
            remainingMinutes +
            ":" +
            remainingSeconds
        );
        countdownDate.setTime(countdownDate.getTime() + 1000);
      }, 1000);
    });
  }
});
