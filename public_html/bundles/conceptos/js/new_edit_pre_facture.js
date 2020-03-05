var pre_facture_product_template =
  '<tr class="product-row"><td><img src="/uploads/%0" class="img-responsive" width="50" height="50"></td><td>%1</td><td>%2</td><td>%3</td></tr>';

var preFactureProducts = [];

$(document).ready(function() {
  $('form[name="pre_facture"]').submit(function() {
    $("#pre_facture_preFactureProducts").val(
      JSON.stringify(preFactureProducts)
    );
  });

  init();
  populatePreFactureProducts();
});

function init() {
  preFactureProducts = JSON.parse($("#pre_facture_preFactureProducts").val());
}

function populatePreFactureProducts() {
  $(".product-row").remove();
  if (preFactureProducts) {
    preFactureProducts.forEach(function(productPreFacture) {
      var template = pre_facture_product_template
        .substring(-1)
        .replace("%0", productPreFacture.image)
        .replace("%1", productPreFacture.code)
        .replace("%2", productPreFacture.count)
        .replace("%3", "asd");

      $(".product-rows").append(template);
    });
  }
}
