$(document).ready(function() {
  $(".carousel-indicators li").click(function() {
    $(".carousel").carousel($(this).data("slide-to") - 1);
  });
});
