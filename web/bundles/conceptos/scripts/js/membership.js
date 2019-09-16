$(document).ready(function() {
  if (window.screen.width > 1000) {
    $("#membership-header-short").remove();
  } else {
    $("#membership-header").remove();
  }
});
