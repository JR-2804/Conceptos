$(document).ready(function() {
  $(".product-card-clickable").click(function() {
    window.location = $(this).data("path");
  });
});
