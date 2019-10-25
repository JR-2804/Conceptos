$(document).ready(function() {
  $(".carousel-in-store").carousel();

  $(".control-brands.carousel-control-prev").click(function() {
    $("#carousel-brands").carousel("prev");
  });
  $(".control-brands.carousel-control-next").click(function() {
    $("#carousel-brands").carousel("next");
  });

  $(".control-populars.control-prev").click(function() {
    $("#carousel-populars").carousel("prev");
  });
  $(".control-populars.control-next").click(function() {
    $("#carousel-populars").carousel("next");
  });
});
