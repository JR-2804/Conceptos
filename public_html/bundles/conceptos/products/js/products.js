$(document).ready(function() {
  $("#filter-products-button").change(function() {
    $("#page").val(1);
  });

  $(".conceptos-primary-button.pagination-prev").click(function() {
    $("#page").val(parseInt($("#page").val()) - 1);
  });
  $(".conceptos-primary-button.pagination-next").click(function() {
    $("#page").val(parseInt($("#page").val()) + 1);
  });
});
