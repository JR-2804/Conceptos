$(document).ready(function() {
  if (window.screen.width > 1000) {
    $("#membership-form-short").remove();
  } else {
    $("#membership-form-large").remove();
  }
});
