$(document).ready(function() {
  $(".carousel-in-store").carousel();

  if (window.screen.width > 1000) {
    $("#popular-products-section-short").remove();
  } else {
    $("#popular-products-section").remove();
  }

  $(".control-brands.carousel-control-prev").click(function() {
    $("#carousel-brands").carousel("prev");
  });
  $(".control-brands.carousel-control-next").click(function() {
    $("#carousel-brands").carousel("next");
  });

  $(".control-populars.control-prev").click(function() {
    $("#carousel-populars").carousel("prev");
    $("#carousel-populars-short").carousel("prev");
  });
  $(".control-populars.control-next").click(function() {
    $("#carousel-populars").carousel("next");
    $("#carousel-populars-short").carousel("next");
  });

  ConfigureOfferCountdown();
});

function ConfigureOfferCountdown() {
  offerCountDown = document.getElementById("offer-countdown");
  if (!offerCountDown) {
    return;
  }

  countDownDate = new Date(offerCountDown.dataset.endDate).getTime();
  setInterval(function() {
    var distance = countDownDate - Date.now();
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    var text = "La oferta expiró";
    if (distance >= 0) {
      var daysSection = "";
      if (days > 0) {
        daysSection = days + "d ";
      }
      var hoursSection = "";
      if (hours > 0) {
        hoursSection = hours + "h ";
      }
      var minutesSection = "";
      if (minutes > 0) {
        minutesSection = minutes + "m ";
      }
      var secondsSection = "";
      if (seconds > 0) {
        secondsSection = seconds + "s ";
      }
      text = daysSection + hoursSection + minutesSection + secondsSection;
    }
    document.getElementById("offer-countdown").innerHTML = text;
    document.getElementById("offer-countdown-short").innerHTML = text;
  }, 1000);
}
