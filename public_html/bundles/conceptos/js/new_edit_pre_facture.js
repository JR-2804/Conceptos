var pre_facture_product_template = '<tr class="product-row"><td><img src="/uploads/%0" class="img-responsive" width="50" height="50"></td><td>%1</td><td>%2</td><td>%3</td><td>%4</td><td><a class="btn btn-secondary btn-facture-product" data-code="%5"><i class="fa fa-euro"></i></a><a class="btn btn-secondary btn-edit-product" data-code="%6"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-product" data-code="%7"><i class="fa fa-remove"></i></a></td></tr>';
var pre_facture_card_template = '<tr class="card-row"><td>%1$</td><td>%2</td><td><a class="btn btn-secondary btn-facture-card" data-code="%3"><i class="fa fa-euro"></i></a><a class="btn btn-secondary btn-edit-card" data-code="%4"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-card" data-code="%5"><i class="fa fa-remove"></i></a></td></tr>';
var facture_template = '<tr class="facture-row"><td>%1</td><td><a class="btn btn-secondary btn-remove-facture" data-id="%2"><i class="fa fa-remove"></i></a></td></tr>';
var product_destiny_template = '<tr class="product-destiny-row"><td>%1</td><td><input type="number" value="%2"/></td></tr>';
var card_destiny_template = '<tr class="card-destiny-row"><td>%1</td><td><input type="number" value="%2"/></td></tr>';

var preFactureProductToEdit;
var preFactureCardToEdit;

var preFactureProducts = [];
var preFactureCards = [];
var factures = [];

var productsData;
var cardsData;
var currentFacturingProduct;
var currentFacturingCard;

$(document).ready(function() {
  $("#client, #product, #card, #facture, #modal-prefactures, #modal-factures").select2({
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
    var airplaneMattress = $("#product-airplane-mattress").prop("checked");
    if (product && count && count > 0) {
      var productData = product[0].split("--");
      var exists = false;
      preFactureProducts.forEach(function(preFactureProduct) {
        if (preFactureProduct.product == productData[0]) {
          exists = true;
          preFactureProduct.count = parseInt(count);
          preFactureProduct.airplaneFurniture = airplaneFurniture;
          preFactureProduct.airplaneMattress = airplaneMattress;
        }
      });
      if (!exists) {
        preFactureProducts.push({
          product: parseInt(productData[0]),
          code: productData[1],
          image: productData[2],
          count: parseInt(count),
          airplaneFurniture: airplaneFurniture,
          airplaneMattress: airplaneMattress,
        });
      }
      populatePreFactureProducts();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#edit-product").click(function() {
    var product = $("#product").val();
    var count = $("#product-count").val();
    var airplaneFurniture = $("#product-airplane-forniture").prop("checked");
    var airplaneMattress = $("#product-airplane-mattress").prop("checked");
    if (product && count && count > 0) {
      var productData = product[0].split("--");
      var tmpPreFactureProducts = [];
      preFactureProducts.forEach(function(preFactureProduct) {
        if (preFactureProduct.product != preFactureProductToEdit.product) {
          tmpPreFactureProducts.push(preFactureProduct);
        }
      });
      preFactureProducts = tmpPreFactureProducts;

      preFactureProducts.push({
        product: parseInt(productData[0]),
        code: productData[1],
        image: productData[2],
        count: parseInt(count),
        airplaneFurniture: airplaneFurniture,
        airplaneMattress: airplaneMattress,
      });

      populatePreFactureProducts();
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
    $("#product-airplane-mattress").prop("checked", false);

    $("#add-product").show();
    $("#edit-product").hide();
    $("#cancel-edit-product").hide();
  });

  $("#add-card").click(function() {
    var card = $("#card").val();
    var count = $("#card-count").val();
    if (card && count && count > 0) {
      var exists = false;
      preFactureCards.forEach(function(preFactureCard) {
        if (preFactureCard.card == card) {
          exists = true;
          preFactureCard.count += parseInt(count);
        }
      });
      if (!exists) {
        preFactureCards.push({
          card: card[0],
          count: parseInt(count),
        });
      }
      populatePreFactureCards();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#edit-card").click(function() {
    var card = $("#card").val();
    var count = $("#card-count").val();
    if (card && count && count > 0) {
      var tmpPreFactureCards = [];
      preFactureCards.forEach(function(preFactureCard) {
        if (preFactureCard.card != preFactureCardToEdit.card) {
          tmpPreFactureCards.push(preFactureCard);
        }
      });
      preFactureCards = tmpPreFactureCards;

      preFactureCards.push({
        card: card[0],
        count: parseInt(count),
      });

      populatePreFactureCards();
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

  $("#add-facture").click(function() {
    var facture = $("#facture").val();
    if (facture) {
      var factureData = facture[0].split("--");
      var exists = false;
      factures.forEach(function(p) {
        if (p.id == factureData[0]) {
          exists = true;
        }
      });
      if (!exists) {
        factures.push({
          id: parseInt(factureData[0]),
          date: factureData[1],
        });
      }
      populateFactures();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#modal-factures-accept").click(function() {
    if ($("#modal-factures").val() && $("#modal-factures-count").val()) {
      if (currentFacturingProduct) {
        var count = parseInt($("#modal-factures-count").val());
        if (count > productsData[currentFacturingProduct].data.count) {
          alert("La cantidad a facturar no puede ser superior a la cantidad actual del producto");
        }
        productsData[currentFacturingProduct].factures[$("#modal-factures").val()[0].replace("--", "-")] += count;
        productsData[currentFacturingProduct].prefacture -= count;
      }
      if (currentFacturingCard) {
        var count = parseInt($("#modal-factures-count").val());
        if (count > cardsData[currentFacturingCard].data.count) {
          alert("La cantidad a facturar no puede ser superior a la cantidad actual de la tarjeta");
        }
        cardsData[currentFacturingCard].factures[$("#modal-factures").val()[0].replace("--", "-")] += count;
        cardsData[currentFacturingCard].prefacture -= count;
      }

      ajax(
        $(this).data("path"),
        "POST",
        {
          productsData: JSON.stringify(productsData),
          cardsData: JSON.stringify(cardsData),
        },
        function() {
          $("#modal-factures").val([]).trigger("change");
          $("#modal-factures-count").val("");
          $("#factureModal").modal("hide");

          if (currentFacturingProduct) {
            preFactureProducts.forEach(function(productPrefacture) {
              if (productPrefacture.product == currentFacturingProduct) {
                productPrefacture.count = productPrefacture.count - count;
              }
            });
            populatePreFactureProducts();
          }
          if (currentFacturingCard) {
            preFactureCards.forEach(function(cardPrefacture) {
              if (cardPrefacture.card == currentFacturingCard) {
                cardPrefacture.count = cardPrefacture.count - count;
              }
            });
            populatePreFactureCards();
          }

          currentFacturingProduct = undefined;
          currentFacturingCard = undefined;
        },
        function() {
          alert("Ocurrió un error realizando la factura");
          $("#modal-factures").val([]).trigger("change");
          $("#modal-factures-count").val("");
          $("#factureModal").modal("hide");
          currentFacturingProduct = undefined;
          currentFacturingCard = undefined;
        },
      );
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $('form[name="pre_facture"]').submit(function(e) {
    if (!validForm()) {
      e.preventDefault();
    } else {
      $("#pre_facture_date").val($("#date").val());
      $("#pre_facture_client").val(JSON.stringify($("#client").val()));
      $("#pre_facture_transportCost").val($("#transportCost").val());
      $("#pre_facture_discount").val($("#discount").val());
      $("#pre_facture_firstClientDiscount").val($("#firstClientDiscount").val());
      $("#pre_facture_finalPrice").val($("#finalPrice").val());
      $("#pre_facture_preFactureProducts").val(JSON.stringify(preFactureProducts));
      $("#pre_facture_preFactureCards").val(JSON.stringify(preFactureCards));
      $("#pre_facture_factures").val(JSON.stringify(factures));
    }
  });

  init();
  populatePreFactureProducts();
  populatePreFactureCards();
  populateFactures();
});

function init() {
  if ($("#pre_facture_date").val()) {
    $("#date").val(JSON.parse($("#pre_facture_date").val()).date.substring(0, 10));
    $("#client").val(JSON.parse($("#pre_facture_client").val())).trigger("change");
    $("#transportCost").val($("#pre_facture_transportCost").val());
    $("#discount").val($("#pre_facture_discount").val());
    $("#firstClientDiscount").val($("#pre_facture_firstClientDiscount").val());
    $("#finalPrice").val($("#pre_facture_finalPrice").val());

    preFactureProducts = JSON.parse($("#pre_facture_preFactureProducts").val());
    preFactureCards = JSON.parse($("#pre_facture_preFactureCards").val());
    preFactureCards.forEach(function(preFactureCard) {
      preFactureCard.card = preFactureCard.price;
    });
    factures = JSON.parse($("#pre_facture_factures").val());
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

function populatePreFactureProducts() {
  $("#product").val([]).trigger("change");
  $("#product-count").val("");
  $("#product-airplane-forniture").prop("checked", false);
  $("#product-airplane-mattress").prop("checked", false);

  $(".product-row").remove();
  if (preFactureProducts) {
    preFactureProducts.forEach(function(productPreFacture) {
      var airplaneFurnitureReplacement = "No";
      if (productPreFacture.airplaneFurniture) {
        airplaneFurnitureReplacement = "Si";
      }
      var airplaneMattressReplacement = "No";
      if (productPreFacture.airplaneMattress) {
        airplaneMattressReplacement = "Si";
      }

      var template = pre_facture_product_template
        .substring(-1)
        .replace("%0", productPreFacture.image)
        .replace("%1", productPreFacture.code)
        .replace("%2", productPreFacture.count)
        .replace("%3", airplaneFurnitureReplacement)
        .replace("%4", airplaneMattressReplacement)
        .replace("%5", productPreFacture.product)
        .replace("%6", productPreFacture.product)
        .replace("%7", productPreFacture.product);

      $(".product-rows").append(template);
    });
    if (!$("#factureModal").data("action")) {
      $(".btn-prefacture-product").hide();
      $(".btn-facture-product").hide();
    }

    $(".btn-remove-product").click(function() {
      var code = $(this).data("code");
      var tmpProducts = [];
      preFactureProducts.forEach(function(product) {
        if (product.product != code) {
          tmpProducts.push(product);
        }
      });
      preFactureProducts = tmpProducts;
      populatePreFactureProducts();
    });
    $(".btn-edit-product").click(function() {
      $("#add-product").hide();
      $("#edit-product").show();
      $("#cancel-edit-product").show();
      var code = $(this).data("code");
      preFactureProducts.forEach(function(preFactureProduct) {
        if (preFactureProduct.product == code) {
          preFactureProductToEdit = preFactureProduct;
        }
      });
      $("#product").val([preFactureProductToEdit.product + "--" + preFactureProductToEdit.code + "--" + preFactureProductToEdit.image]).trigger("change");
      $("#product-count").val(preFactureProductToEdit.count);
      $("#product-airplane-forniture").prop("checked", preFactureProductToEdit.airplaneFurniture);
      $("#product-airplane-mattress").prop("checked", preFactureProductToEdit.airplaneMattress);
    });
    $(".btn-facture-product").click(function() {
      var productId = $(this).data("code");
      ajax(
        $("#factureModal").data("path"),
        "POST",
        {},
        function(response) {
          productsData = JSON.parse(response.productsData);
          cardsData = JSON.parse(response.cardsData);
          var productCode = productsData[productId].data.code;
          var productImage = productsData[productId].data.image;

          $(".product-destiny-row").remove();
          $("#factureModal").modal("show");
          $("#factureModal img.modal-header-image").show();
          $("#factureModal img.modal-header-image").prop("src", "/uploads/" + productImage);
          $("#factureModal .modal-title").text("Facturar Producto (" + productCode + ")");

          currentFacturingProduct = productId;
        }
      );
    });
  }
}

function populatePreFactureCards() {
  $("#card").val([]).trigger("change");
  $("#card-count").val("");

  $(".card-row").remove();
  if (preFactureCards) {
    preFactureCards.forEach(function(cardPreFacture) {
      var template = pre_facture_card_template
        .substring(-1)
        .replace("%1", cardPreFacture.card)
        .replace("%2", cardPreFacture.count)
        .replace("%3", cardPreFacture.card)
        .replace("%4", cardPreFacture.card)
        .replace("%5", cardPreFacture.card);

      $(".card-rows").append(template);
    });
    if (!$("#factureModal").data("action")) {
      $(".btn-prefacture-card").hide();
      $(".btn-facture-card").hide();
    }

    $(".btn-remove-card").click(function() {
      var code = $(this).data("code");
      var tmpCards = [];
      preFactureCards.forEach(function(card) {
        if (card.card != code) {
          tmpCards.push(card);
        }
      });
      preFactureCards = tmpCards;
      populatePreFactureCards();
    });
    $(".btn-edit-card").click(function() {
      $("#add-card").hide();
      $("#edit-card").show();
      $("#cancel-edit-card").show();
      var code = $(this).data("code");
      preFactureCards.forEach(function(preFactureCard) {
        if (preFactureCard.card == code) {
          preFactureCardToEdit = preFactureCard;
        }
      });
      $("#card").val([preFactureCardToEdit.card]).trigger("change");
      $("#card-count").val(preFactureCardToEdit.count);
    });
    $(".btn-facture-card").click(function() {
      var cardPrice = $(this).data("code");
      ajax(
        $("#factureModal").data("path"),
        "POST",
        {},
        function(response) {
          productsData = JSON.parse(response.productsData);
          cardsData = JSON.parse(response.cardsData);

          $(".card-destiny-row").remove();
          $("#factureModal").modal("show");
          $("#factureModal img.modal-header-image").hide();
          $("#factureModal .modal-title").text("Facturar Tarjeta (" + cardPrice + ")");

          currentFacturingCard = cardPrice;
        }
      );
    });
  }
}

function populateFactures() {
  $("#facture").val([]).trigger("change");

  $(".facture-row").remove();
  if (factures) {
    factures.forEach(function(facture) {
      var template = facture_template
        .substring(-1)
        .replace("%1", facture.id + '-' + facture.date)
        .replace("%2", facture.id)

      $(".facture-rows").append(template);
    });
    $(".btn-remove-facture").click(function() {
      var id = $(this).data("id");
      var tmpFactures = [];
      factures.forEach(function(facture) {
        if (facture.id != id) {
          tmpFactures.push(facture);
        }
      });
      factures = tmpFactures;
      populateFactures();
    });
  }
}
