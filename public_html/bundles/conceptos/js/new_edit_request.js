var request_product_template = '<tr class="product-row"><td><img src="/uploads/%0" class="img-responsive" width="50" height="50"></td><td>%1</td><td>%2</td><td>%3</td><td>%4</td><td><a class="btn btn-secondary btn-prefacture-product" data-code="%5"><i class="fa fa-dollar"></i></a><a class="btn btn-secondary btn-facture-product" data-code="%6"><i class="fa fa-euro"></i></a><a class="btn btn-secondary btn-edit-product" data-code="%7"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-product" data-code="%8"><i class="fa fa-remove"></i></a></td></tr>';
var request_card_template = '<tr class="card-row"><td>%1$</td><td>%2</td><td><a class="btn btn-secondary btn-prefacture-card" data-code="%3"><i class="fa fa-dollar"></i></a><a class="btn btn-secondary btn-facture-card" data-code="%4"><i class="fa fa-euro"></i></a><a class="btn btn-secondary btn-edit-card" data-code="%5"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-card" data-code="%6"><i class="fa fa-remove"></i></a></td></tr>';
var prefacture_template = '<tr class="prefacture-row"><td>%1</td><td><a class="btn btn-secondary btn-remove-prefacture" data-id="%2"><i class="fa fa-remove"></i></a><a class="btn btn-secondary btn-view-prefacture" href="/admin/?action=show&entity=PreFacture&id=%3"><i class="fa fa-eye"></i></a></td></tr>';
var facture_template = '<tr class="facture-row"><td>%1</td><td><a class="btn btn-secondary btn-remove-facture" data-id="%2"><i class="fa fa-remove"></i></a><a class="btn btn-secondary btn-view-facture" href="/admin/?action=show&entity=Facture&id=%3"><i class="fa fa-eye"></i></a></td></tr>';
var product_destiny_template = '<tr class="product-destiny-row"><td>%1</td><td><input type="number" value="%2"/></td></tr>';
var card_destiny_template = '<tr class="card-destiny-row"><td>%1</td><td><input type="number" value="%2"/></td></tr>';

var requestProductToEdit;
var requestCardToEdit;

var requestProducts = [];
var requestCards = [];
var prefactures = [];
var factures = [];

var productsData;
var cardsData;
var currentFacturingProduct;
var currentFacturingCard;

$(document).ready(function() {
  $("#client, #product, #card, #prefacture, #facture, #modal-prefactures, #modal-factures").select2({
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
      requestProducts.forEach(function(requestProduct) {
        if (requestProduct.product == productData[0]) {
          exists = true;
          requestProduct.count = parseInt(count);
          requestProduct.airplaneFurniture = airplaneFurniture;
          requestProduct.airplaneMattress = airplaneMattress;
        }
      });
      if (!exists) {
        requestProducts.push({
          product: parseInt(productData[0]),
          code: productData[1],
          image: productData[2],
          count: parseInt(count),
          airplaneFurniture: airplaneFurniture,
          airplaneMattress: airplaneMattress,
        });
      }
      populateRequestProducts();
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
      var tmpRequestProducts = [];
      requestProducts.forEach(function(requestProduct) {
        if (requestProduct.product != requestProductToEdit.product) {
          tmpRequestProducts.push(requestProduct);
        }
      });
      requestProducts = tmpRequestProducts;

      requestProducts.push({
        product: parseInt(productData[0]),
        code: productData[1],
        image: productData[2],
        count: parseInt(count),
        airplaneFurniture: airplaneFurniture,
        airplaneMattress: airplaneMattress,
        price: requestProductToEdit.price,
      });

      populateRequestProducts();
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
      requestCards.forEach(function(requestCard) {
        if (requestCard.card == card) {
          exists = true;
          requestCard.count += parseInt(count);
        }
      });
      if (!exists) {
        requestCards.push({
          card: card[0],
          count: parseInt(count),
        });
      }
      populateRequestCards();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#edit-card").click(function() {
    var card = $("#card").val();
    var count = $("#card-count").val();
    if (card && count && count > 0) {
      var tmpRequestCards = [];
      requestCards.forEach(function(requestCard) {
        if (requestCard.card != requestCardToEdit.card) {
          tmpRequestCards.push(requestCard);
        }
      });
      requestCards = tmpRequestCards;

      requestCards.push({
        card: card[0],
        count: parseInt(count),
      });

      populateRequestCards();
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

  $("#add-prefacture").click(function() {
    var prefacture = $("#prefacture").val();
    if (prefacture) {
      var prefactureData = prefacture[0].split("--");
      var exists = false;
      prefactures.forEach(function(p) {
        if (p.id == prefactureData[0]) {
          exists = true;
        }
      });
      if (!exists) {
        prefactures.push({
          id: parseInt(prefactureData[0]),
          date: prefactureData[1],
        });
      }
      populatePrefactures();
    } else {
      alert("Inserte los datos correctamente");
    }
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

  $("#modal-prefactures-accept").click(function() {
    if ($("#modal-prefactures").val() && $("#modal-prefactures-count").val()) {
      if (currentFacturingProduct) {
        var count = parseInt($("#modal-prefactures-count").val());
        if (count > productsData[currentFacturingProduct].data.count) {
          alert("La cantidad a prefacturar no puede ser superior a la cantidad actual del producto");
          return;
        }
        productsData[currentFacturingProduct].prefactures[$("#modal-prefactures").val()[0].replace("--", "-")] += count;
        productsData[currentFacturingProduct].request -= count;
      }
      if (currentFacturingCard) {
        var count = parseInt($("#modal-prefactures-count").val());
        if (count > cardsData[currentFacturingCard].data.count) {
          alert("La cantidad a prefacturar no puede ser superior a la cantidad actual de la tarjeta");
          return;
        }
        cardsData[currentFacturingCard].prefactures[$("#modal-prefactures").val()[0].replace("--", "-")] += count;
        cardsData[currentFacturingCard].request -= count;
      }

      ajax(
        $(this).data("path"),
        "POST",
        {
          productsData: JSON.stringify(productsData),
          cardsData: JSON.stringify(cardsData),
        },
        function() {
          $("#modal-prefactures").val([]).trigger("change");
          $("#modal-prefactures-count").val("");
          $("#prefactureModal").modal("hide");

          if (currentFacturingProduct) {
            requestProducts.forEach(function(productRequest) {
              if (productRequest.product == currentFacturingProduct) {
                productRequest.count = productRequest.count - count;
              }
            });
            populateRequestProducts();
          }
          if (currentFacturingCard) {
            requestCards.forEach(function(cardRequest) {
              if (cardRequest.card == currentFacturingCard) {
                cardRequest.count = cardRequest.count - count;
              }
            });
            populateRequestCards();
          }

          currentFacturingProduct = undefined;
          currentFacturingCard = undefined;
        },
        function() {
          alert("Ocurrió un error realizando la prefactura");
          $("#modal-prefactures").val([]).trigger("change");
          $("#modal-prefactures-count").val("");
          $("#prefactureModal").modal("hide");
          currentFacturingProduct = undefined;
          currentFacturingCard = undefined;
        },
      );
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
          return;
        }
        productsData[currentFacturingProduct].factures[$("#modal-factures").val()[0].replace("--", "-")] += count;
        productsData[currentFacturingProduct].request -= count;
      }
      if (currentFacturingCard) {
        var count = parseInt($("#modal-factures-count").val());
        if (count > cardsData[currentFacturingCard].data.count) {
          alert("La cantidad a facturar no puede ser superior a la cantidad actual de la tarjeta");
          return;
        }
        cardsData[currentFacturingCard].factures[$("#modal-factures").val()[0].replace("--", "-")] += count;
        cardsData[currentFacturingCard].request -= count;
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
            requestProducts.forEach(function(productRequest) {
              if (productRequest.product == currentFacturingProduct) {
                productRequest.count = productRequest.count - count;
              }
            });
            populateRequestProducts();
          }
          if (currentFacturingCard) {
            requestCards.forEach(function(cardRequest) {
              if (cardRequest.card == currentFacturingCard) {
                cardRequest.count = cardRequest.count - count;
              }
            });
            populateRequestCards();
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

  $("#calculate-price-button").click(function() {
    var finalPrice = 0;
    var transportCost = $("#transportCost").val();

    requestProducts.forEach(function(requestProduct) {
      if (requestProduct.offerPrice) {
        finalPrice += requestProduct.offerPrice * requestProduct.count;
      } else {
        if (requestProduct.airplaneFurniture) {
          finalPrice += requestProduct.price * requestProduct.count;
        }
        else if(requestProduct.airplaneMattress) {
          finalPrice += requestProduct.price * requestProduct.count;
        }
        else {
          finalPrice += requestProduct.price * requestProduct.count;
        }
      }
    });
    requestCards.forEach(function(requestCard) {
      finalPrice += requestCard.price * requestCard.count;
    });

    var membershipDiscount = 0;
    var firstClientDiscount = 0;
    if ($("#calculate-price-button").data("member-number")) {
      membershipDiscount = Math.floor(finalPrice * 0.1);
    }
    else if($("#calculate-price-button").data("first-client")) {
      firstClientDiscount = Math.floor(finalPrice * 0.05);
    }

    finalPrice += parseFloat(transportCost);
    finalPrice -= parseFloat(membershipDiscount);
    finalPrice -= parseFloat(firstClientDiscount);
    $("#finalPrice").val(finalPrice);
    $("#discount").val(membershipDiscount)
    $("#firstClientDiscount").val(firstClientDiscount)
  });

  $('form[name="request"]').submit(function(e) {
    if (!validForm()) {
      e.preventDefault();
    } else {
      $("#request_date").val($("#date").val());
      $("#request_client").val(JSON.stringify($("#client").val()));
      $("#request_transportCost").val($("#transportCost").val());
      $("#request_discount").val($("#discount").val());
      $("#request_firstClientDiscount").val($("#firstClientDiscount").val());
      $("#request_finalPrice").val($("#finalPrice").val());
      $("#request_requestProducts").val(JSON.stringify(requestProducts));
      $("#request_requestCards").val(JSON.stringify(requestCards));
      $("#request_preFactures").val(JSON.stringify(prefactures));
      $("#request_factures").val(JSON.stringify(factures));
    }
  });

  init();
  populateRequestProducts();
  populateRequestCards();
  populatePrefactures();
  populateFactures();
});

function init() {
  if ($("#request_date").val()) {
    $("#date").val(JSON.parse($("#request_date").val()).date.substring(0, 10));
    $("#client").val(JSON.parse($("#request_client").val())).trigger("change");
    $("#transportCost").val($("#request_transportCost").val());
    $("#discount").val($("#request_discount").val());
    $("#firstClientDiscount").val($("#request_firstClientDiscount").val());
    $("#finalPrice").val($("#request_finalPrice").val());

    requestProducts = JSON.parse($("#request_requestProducts").val());
    requestCards = JSON.parse($("#request_requestCards").val());
    requestCards.forEach(function(requestCard) {
      requestCard.card = requestCard.price;
    });
    prefactures = JSON.parse($("#request_preFactures").val());
    factures = JSON.parse($("#request_factures").val());
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

function populateRequestProducts() {
  $("#product").val([]).trigger("change");
  $("#product-count").val("");
  $("#product-airplane-forniture").prop("checked", false);
  $("#product-airplane-mattress").prop("checked", false);

  $(".product-row").remove();
  if (requestProducts) {
    requestProducts.forEach(function(productRequest) {
      var airplaneFurnitureReplacement = "No";
      if (productRequest.airplaneFurniture) {
        airplaneFurnitureReplacement = "Si";
      }
      var airplaneMattressReplacement = "No";
      if (productRequest.airplaneMattress) {
        airplaneMattressReplacement = "Si";
      }

      var template = request_product_template
        .substring(-1)
        .replace("%0", productRequest.image)
        .replace("%1", productRequest.code)
        .replace("%2", productRequest.count)
        .replace("%3", airplaneFurnitureReplacement)
        .replace("%4", airplaneMattressReplacement)
        .replace("%5", productRequest.product)
        .replace("%6", productRequest.product)
        .replace("%7", productRequest.product)
        .replace("%8", productRequest.product);

      $(".product-rows").append(template);
    });
    if (!$("#factureModal").data("action")) {
      $(".btn-prefacture-product").hide();
      $(".btn-facture-product").hide();
    }

    $(".btn-remove-product").click(function() {
      var code = $(this).data("code");
      var tmpProducts = [];
      requestProducts.forEach(function(product) {
        if (product.product != code) {
          tmpProducts.push(product);
        }
      });
      requestProducts = tmpProducts;
      populateRequestProducts();
    });
    $(".btn-edit-product").click(function() {
      $("#add-product").hide();
      $("#edit-product").show();
      $("#cancel-edit-product").show();
      var code = $(this).data("code");
      requestProducts.forEach(function(requestProduct) {
        if (requestProduct.product == code) {
          requestProductToEdit = requestProduct;
        }
      });
      $("#product").val([requestProductToEdit.product + "--" + requestProductToEdit.code + "--" + requestProductToEdit.image]).trigger("change");
      $("#product-count").val(requestProductToEdit.count);
      $("#product-airplane-forniture").prop("checked", requestProductToEdit.airplaneFurniture);
      $("#product-airplane-mattress").prop("checked", requestProductToEdit.airplaneMattress);
    });
    $(".btn-prefacture-product").click(function() {
      var productId = $(this).data("code");
      ajax(
        $("#prefactureModal").data("path"),
        "POST",
        {},
        function(response) {
          productsData = JSON.parse(response.productsData);
          cardsData = JSON.parse(response.cardsData);
          var productCode = productsData[productId].data.code;
          var productImage = productsData[productId].data.image;

          $(".product-destiny-row").remove();
          $("#prefactureModal").modal("show");
          $("#prefactureModal img.modal-header-image").show();
          $("#prefactureModal img.modal-header-image").prop("src", "/uploads/" + productImage);
          $("#prefactureModal .modal-title").text("Prefacturar Producto (" + productCode + ")");

          currentFacturingProduct = productId;
        }
      );
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

function populateRequestCards() {
  $("#card").val([]).trigger("change");
  $("#card-count").val("");

  $(".card-row").remove();
  if (requestCards) {
    requestCards.forEach(function(cardRequest) {
      var template = request_card_template
        .substring(-1)
        .replace("%1", cardRequest.card)
        .replace("%2", cardRequest.count)
        .replace("%3", cardRequest.card)
        .replace("%4", cardRequest.card)
        .replace("%5", cardRequest.card)
        .replace("%6", cardRequest.card);

      $(".card-rows").append(template);
    });
    if (!$("#factureModal").data("action")) {
      $(".btn-prefacture-card").hide();
      $(".btn-facture-card").hide();
    }

    $(".btn-remove-card").click(function() {
      var code = $(this).data("code");
      var tmpCards = [];
      requestCards.forEach(function(card) {
        if (card.card != code) {
          tmpCards.push(card);
        }
      });
      requestCards = tmpCards;
      populateRequestCards();
    });
    $(".btn-edit-card").click(function() {
      $("#add-card").hide();
      $("#edit-card").show();
      $("#cancel-edit-card").show();
      var code = $(this).data("code");
      requestCards.forEach(function(requestCard) {
        if (requestCard.card == code) {
          requestCardToEdit = requestCard;
        }
      });
      $("#card").val([requestCardToEdit.card]).trigger("change");
      $("#card-count").val(requestCardToEdit.count);
    });
    $(".btn-prefacture-card").click(function() {
      var cardPrice = $(this).data("code");
      ajax(
        $("#prefactureModal").data("path"),
        "POST",
        {},
        function(response) {
          productsData = JSON.parse(response.productsData);
          cardsData = JSON.parse(response.cardsData);

          $(".card-destiny-row").remove();
          $("#prefactureModal").modal("show");
          $("#prefactureModal img.modal-header-image").hide();
          $("#prefactureModal .modal-title").text("Prefacturar Tarjera (" + cardPrice + ")");

          currentFacturingCard = cardPrice;
        }
      );
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

function populatePrefactures() {
  $("#prefacture").val([]).trigger("change");

  $(".prefacture-row").remove();
  if (prefactures) {
    prefactures.forEach(function(prefacture) {
      var template = prefacture_template
        .substring(-1)
        .replace("%1", prefacture.id + '-' + prefacture.date)
        .replace("%2", prefacture.id)
        .replace("%3", prefacture.id);

      $(".prefacture-rows").append(template);
    });
    $(".btn-remove-prefacture").click(function() {
      var id = $(this).data("id");
      var tmpPrefactures = [];
      prefactures.forEach(function(prefacture) {
        if (prefacture.id != id) {
          tmpPrefactures.push(prefacture);
        }
      });
      prefactures = tmpPrefactures;
      populatePrefactures();
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
        .replace("%3", facture.id);

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
