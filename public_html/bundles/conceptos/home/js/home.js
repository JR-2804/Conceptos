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
});
