$(document).ready(function() {
  $(".services-inward-button").click(function() {
    $(".services-inward-button").addClass(
      "conceptos-primary-button-badge-focused"
    );
    $(".services-obras-button").removeClass(
      "conceptos-primary-button-badge-focused"
    );

    $(".services-inward-info").addClass("d-flex");
    $(".services-inward-info").removeClass("d-none");
    $(".services-obras-info").addClass("d-none");
    $(".services-obras-info").removeClass("d-flex");
  });

  $(".services-obras-button").click(function() {
    $(".services-obras-button").addClass(
      "conceptos-primary-button-badge-focused"
    );
    $(".services-inward-button").removeClass(
      "conceptos-primary-button-badge-focused"
    );

    $(".services-obras-info").addClass("d-flex");
    $(".services-obras-info").removeClass("d-none");
    $(".services-inward-info").addClass("d-none");
    $(".services-inward-info").removeClass("d-flex");
  });

  $(".services-inward-button").click();
});
