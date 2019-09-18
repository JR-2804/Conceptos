$(document).ready(function() {
  var description = $(".description-content").text();
  var newDescription = description.substring(
    0,
    description.indexOf("Materiales")
  );
  newDescription +=
    "<br>" +
    description.substring(
      description.indexOf("Materiales"),
      description.indexOf("Dimensiones")
    );
  newDescription +=
    "<br>" +
    description.substring(
      description.indexOf("Dimensiones"),
      description.length
    );
  $(".description-content").text("");
  $(".description-content").append(newDescription);

  $("#product-images-nav-carousel .prev-button").click(function() {
    $("#product-images-nav-carousel").carousel("prev");
  });
  $("#product-images-nav-carousel .next-button").click(function() {
    $("#product-images-nav-carousel").carousel("next");
  });
});
