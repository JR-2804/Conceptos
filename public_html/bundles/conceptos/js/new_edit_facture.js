var facture_product_template = '<tr class="product-row"><td><img src="/uploads/%0" class="img-responsive" width="50" height="50"></td><td>%1</td><td>%2</td><td>%3</td><td><a class="btn btn-secondary btn-edit-product" data-code="%4"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-product" data-code="%5"><i class="fa fa-remove"></i></a></td></tr>';
var facture_card_template = '<tr class="card-row"><td>%1$</td><td>%2</td><td><a class="btn btn-secondary btn-edit-card" data-code="%3"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-card" data-code="%4"><i class="fa fa-remove"></i></a></td></tr>';

var factureProductToEdit;
var factureCardToEdit;

var factureProducts = [];
var factureCards = [];

$(document).ready(function() {
  $("#client, #product, #card").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true,
    tags: true,
    maximumSelectionLength: 1,
  });

  $("#add-product").click(function() {
    var product = $("#product").val();
    var count = $("#product-count").val();
    var airplaneFurniture = $("#product-airplane-forniture").prop("checked");
    if (product && count && count > 0) {
      var productData = product[0].split("--");
      var exists = false;
      factureProducts.forEach(function(factureProduct) {
        if (factureProduct.product == productData[0]) {
          exists = true;
          factureProduct.count = parseInt(count);
          factureProduct.airplaneFurniture = airplaneFurniture;
        }
      });
      if (!exists) {
        factureProducts.push({
          product: parseInt(productData[0]),
          code: productData[1],
          image: productData[2],
          count: parseInt(count),
          airplaneFurniture: airplaneFurniture,
        });
      }
      populateFactureProducts();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#edit-product").click(function() {
    var product = $("#product").val();
    var count = $("#product-count").val();
    var airplaneFurniture = $("#product-airplane-forniture").prop("checked");
    if (product && count && count > 0) {
      var productData = product[0].split("--");
      var tmpFactureProducts = [];
      factureProducts.forEach(function(factureProduct) {
        if (factureProduct.product != factureProductToEdit.product) {
          tmpFactureProducts.push(factureProduct);
        }
      });
      factureProducts = tmpFactureProducts;

      factureProducts.push({
        product: parseInt(productData[0]),
        code: productData[1],
        image: productData[2],
        count: parseInt(count),
        airplaneFurniture: airplaneFurniture,
      });

      populateFactureProducts();
      $("#add-product").show();
      $("#edit-product").hide();
      $("#cancel-edit-product").hide();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#cancel-edit-product").click(function() {
    $("#product").val([]).trigger("change");
    $("#product-count").val("");
    $("#product-airplane-forniture").prop("checked", false);

    $("#add-product").show();
    $("#edit-product").hide();
    $("#cancel-edit-product").hide();
  });

  $("#add-card").click(function() {
    var card = $("#card").val();
    var count = $("#card-count").val();
    if (card && count && count > 0) {
      var exists = false;
      factureCards.forEach(function(factureCard) {
        if (factureCard.card == card) {
          exists = true;
          factureCard.count += parseInt(count);
        }
      });
      if (!exists) {
        factureCards.push({
          card: card[0],
          count: parseInt(count),
        });
      }
      populateFactureCards();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#edit-card").click(function() {
    var card = $("#card").val();
    var count = $("#card-count").val();
    if (card && count && count > 0) {
      var tmpFactureCards = [];
      factureCards.forEach(function(factureCard) {
        if (factureCard.card != factureCardToEdit.card) {
          tmpFactureCards.push(factureCard);
        }
      });
      factureCards = tmpFactureCards;

      factureCards.push({
        card: card[0],
        count: parseInt(count),
      });

      populateFactureCards();
      $("#add-card").show();
      $("#edit-card").hide();
      $("#cancel-edit-card").hide();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#cancel-edit-card").click(function() {
    $("#card").val([]).trigger("change");
    $("#card-count").val("");

    $("#add-card").show();
    $("#edit-card").hide();
    $("#cancel-edit-card").hide();
  });

  $('form[name="facture"]').submit(function(e) {
    if (!validForm()) {
      e.preventDefault();
    } else {
      $("#facture_date").val($("#date").val());
      $("#facture_client").val(JSON.stringify($("#client").val()));
      $("#facture_transportCost").val($("#transportCost").val());
      $("#facture_discount").val($("#discount").val());
      $("#facture_firstClientDiscount").val($("#firstClientDiscount").val());
      $("#facture_finalPrice").val($("#finalPrice").val());
      $("#facture_factureProducts").val(JSON.stringify(factureProducts));
      $("#facture_factureCards").val(JSON.stringify(factureCards));
    }
  });

  init();
  populateFactureProducts();
  populateFactureCards();
});

function init() {
  if ($("#facture_date").val()) {
    $("#date").val(JSON.parse($("#facture_date").val()).date.substring(0, 10));
    $("#client").val(JSON.parse($("#facture_client").val())).trigger("change");
    $("#transportCost").val($("#facture_transportCost").val());
    $("#discount").val($("#facture_discount").val());
    $("#firstClientDiscount").val($("#facture_firstClientDiscount").val());
    $("#finalPrice").val($("#facture_finalPrice").val());

    factureProducts = JSON.parse($("#facture_factureProducts").val());
    factureCards = JSON.parse($("#facture_factureCards").val());
    factureCards.forEach(function(factureCard) {
      factureCard.card = factureCard.price;
    });
  }
}

function validForm() {
  var valid = true;
  var date = $("#date");
  if (!$(date).val()) {
    addRemoveErrorClass(date, true);
    valid = false;
  } else {
    addRemoveErrorClass(date, false);
  }
  var client = $("#client");
  if (!$(client).val()) {
    addRemoveErrorClass(client, true);
    valid = false;
  } else {
    addRemoveErrorClass(client, false);
  }
  var transportCost = $("#transportCost");
  if (!$(transportCost).val()) {
    addRemoveErrorClass(transportCost, true);
    valid = false;
  } else {
    addRemoveErrorClass(transportCost, false);
  }
  var discount = $("#discount");
  if (!$(discount).val()) {
    addRemoveErrorClass(discount, true);
    valid = false;
  } else {
    addRemoveErrorClass(discount, false);
  }
  var firstClientDiscount = $("#firstClientDiscount");
  if (!$(firstClientDiscount).val()) {
    addRemoveErrorClass(firstClientDiscount, true);
    valid = false;
  } else {
    addRemoveErrorClass(firstClientDiscount, false);
  }
  var finalPrice = $("#finalPrice");
  if (!$(finalPrice).val()) {
    addRemoveErrorClass(finalPrice, true);
    valid = false;
  } else {
    addRemoveErrorClass(finalPrice, false);
  }
  return valid;
}

function addRemoveErrorClass(input, add) {
  if (add) {
    if (!$(input).parent().hasClass("has-error")) {
      $(input).parent().addClass("has-error");
    }
  } else {
    $(input).parent().removeClass("has-error");
  }
}

function populateFactureProducts() {
  $("#product").val([]).trigger("change");
  $("#product-count").val("");
  $("#product-airplane-forniture").prop("checked", false);

  $(".product-row").remove();
  if (factureProducts) {
    factureProducts.forEach(function(productFacture) {
      var airplaneFurnitureReplacement = "No";
      if (productFacture.airplaneFurniture) {
        airplaneFurnitureReplacement = "Si";
      }

      var template = facture_product_template
        .substring(-1)
        .replace("%0", productFacture.image)
        .replace("%1", productFacture.code)
        .replace("%2", productFacture.count)
        .replace("%3", airplaneFurnitureReplacement)
        .replace("%4", productFacture.product)
        .replace("%5", productFacture.product);

      $(".product-rows").append(template);
    });
    $(".btn-remove-product").click(function() {
      var code = $(this).data("code");
      var tmpProducts = [];
      factureProducts.forEach(function(product) {
        if (product.product != code) {
          tmpProducts.push(product);
        }
      });
      factureProducts = tmpProducts;
      populateFactureProducts();
    });
    $(".btn-edit-product").click(function() {
      $("#add-product").hide();
      $("#edit-product").show();
      $("#cancel-edit-product").show();
      var code = $(this).data("code");
      factureProducts.forEach(function(factureProduct) {
        if (factureProduct.product == code) {
          factureProductToEdit = factureProduct;
        }
      });
      $("#product").val([factureProductToEdit.product + "--" + factureProductToEdit.code + "--" + factureProductToEdit.image]).trigger("change");
      $("#product-count").val(factureProductToEdit.count);
      $("#product-airplane-forniture").prop("checked", factureProductToEdit.airplaneFurniture);
    });
  }
}

function populateFactureCards() {
  $("#card").val([]).trigger("change");
  $("#card-count").val("");

  $(".card-row").remove();
  if (factureCards) {
    factureCards.forEach(function(cardFacture) {
      var template = facture_card_template
        .substring(-1)
        .replace("%1", cardFacture.card)
        .replace("%2", cardFacture.count)
        .replace("%3", cardFacture.card)
        .replace("%4", cardFacture.card);

      $(".card-rows").append(template);
    });
    $(".btn-remove-card").click(function() {
      var code = $(this).data("code");
      var tmpCards = [];
      factureCards.forEach(function(card) {
        if (card.card != code) {
          tmpCards.push(card);
        }
      });
      factureCards = tmpCards;
      populateFactureCards();
    });
    $(".btn-edit-card").click(function() {
      $("#add-card").hide();
      $("#edit-card").show();
      $("#cancel-edit-card").show();
      var code = $(this).data("code");
      factureCards.forEach(function(factureCard) {
        if (factureCard.card == code) {
          factureCardToEdit = factureCard;
        }
      });
      $("#card").val([factureCardToEdit.card]).trigger("change");
      $("#card-count").val(factureCardToEdit.count);
      $("#card-airplane-forniture").prop("checked", factureCardToEdit.airplaneFurniture);
    });
  }
}
