$(document).ready(function() {
  $("#name").val($("#product_name").val());
  $("#priority").val($("#product_priority").val());
  $("#code").val($("#product_code").val());
  $("#item").val($("#product_item").val());
  $("#description").val($("#product_description").val());
  $("#description").val($("#product_description").val());
  $("#price").val($("#product_price").val());
  $("#weight").val($("#product_weight").val());
  $("#shipping-limit").val($("#product_shippingLimit").val());
  $("#ikea-price").val($("#product_ikeaPrice").val());
  if (
    $("#product_isFurniture").val() == "1" ||
    $("#product_isFurniture").val() == 1 ||
    $("#product_isFurniture").val() == "true" ||
    $("#product_isFurniture").val()
  ) {
    $("#is-furniture").prop("checked", true);
  }
  if (
    $("#product_isMattress").val() == "1" ||
    $("#product_isMattress").val() == 1 ||
    $("#product_isMattress").val() == "true" ||
    $("#product_isMattress").val()
  ) {
    $("#is-mattress").prop("checked", true);
  }
  $("#calculate-price").val($("#product_calculatePrice").val());
  var categories = JSON.parse($("#product_category").val());
  $("#category")
    .val(categories)
    .trigger("change");
  $("#popular").prop("checked", $("#product_popular").val() == 1);
  $("#recent").prop("checked", $("#product_recent").val() == 1);
  var inStore = $("#product_inStore").val() == 1;
  $("#in-store").prop("checked", inStore);
  $("#store-count").val($("#product_countStore").val());
  if (inStore) {
    $(".store-count-content").show();
  }
  var colorDB = $("#product_color").val();
  if (colorDB) {
    $("#color")
      .val([colorDB])
      .trigger("change");
  }
  var materialDB = $("#product_material").val();
  if (materialDB) {
    $("#material")
      .val([materialDB])
      .trigger("change");
  }
  data.image = JSON.parse($("#product_image").val());
  data.images = JSON.parse($("#product_images").val());
  $("#category-favorite")
    .val(JSON.parse($("#product_favoritesCategories").val()))
    .trigger("change");
  $("#is-highlight").prop("checked", $("#product_isHighlight").val() == 1);
  var highlightImages = $("#product_highlightImages").val();
  if (highlightImages) {
    data.highlightImages = JSON.parse(highlightImages);
  }
  $("#is-airplane-furniture").prop(
    "checked",
    $("#product_isAriplaneForniture").val() == 1
  );
  $("#is-airplane-mattress").prop(
    "checked",
    $("#product_isAriplaneMattress").val() == 1
  );
  $("#is-fragile").prop("checked", $("#product_isFragile").val() == 1);
  $("#is-oversize").prop("checked", $("#product_isOversize").val() == 1);
  $("#is-tableware").prop("checked", $("#product_isTableware").val() == 1);
  $("#is-lamp").prop("checked", $("#product_isLamp").val() == 1);
  $("#is-faucet").prop("checked", $("#product_isFaucet").val() == 1);
  $("#is-grill").prop("checked", $("#product_isGrill").val() == 1);
  $("#is-shelf").prop("checked", $("#product_isShelf").val() == 1);
  $("#is-desk").prop("checked", $("#product_isDesk").val() == 1);
  $("#is-bookcase").prop("checked", $("#product_isBookcase").val() == 1);
  $("#is-comoda").prop("checked", $("#product_isComoda").val() == 1);
  $("#is-repisa").prop("checked", $("#product_isRepisa").val() == 1);
  $("#number-of-packages").val($("#product_numberOfPackages").val());
});
