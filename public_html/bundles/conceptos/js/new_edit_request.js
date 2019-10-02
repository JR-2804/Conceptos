var request_product_template = '<tr class="product-row"><td><img src="/uploads/%0" class="img-responsive" width="50" height="50"></td><td>%1</td><td>%2</td><td>%3</td><td><a class="btn btn-secondary btn-edit-product" data-code="%4"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-product" data-code="%5"><i class="fa fa-remove"></i></a></td></tr>';
var request_card_template = '<tr class="card-row"><td>%1$</td><td>%2</td><td><a class="btn btn-secondary btn-edit-card" data-code="%3"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-card" data-code="%4"><i class="fa fa-remove"></i></a></td></tr>';

var requestProductToEdit;
var requestCardToEdit;

var requestProducts = [];
var requestCards = [];

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
      requestProducts.forEach(function(requestProduct) {
        if (requestProduct.product == productData[0]) {
          exists = true;
          requestProduct.count = parseInt(count);
          requestProduct.airplaneFurniture = airplaneFurniture;
        }
      });
      if (!exists) {
        requestProducts.push({
          product: parseInt(productData[0]),
          code: productData[1],
          image: productData[2],
          count: parseInt(count),
          airplaneFurniture: airplaneFurniture,
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
    }
  });

  init();
  populateRequestProducts();
  populateRequestCards();
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

  $(".product-row").remove();
  if (requestProducts) {
    requestProducts.forEach(function(productRequest) {
      var airplaneFurnitureReplacement = "No";
      if (productRequest.airplaneFurniture) {
        airplaneFurnitureReplacement = "Si";
      }

      var template = request_product_template
        .substring(-1)
        .replace("%0", productRequest.image)
        .replace("%1", productRequest.code)
        .replace("%2", productRequest.count)
        .replace("%3", airplaneFurnitureReplacement)
        .replace("%4", productRequest.product)
        .replace("%5", productRequest.product);

      $(".product-rows").append(template);
    });
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
        .replace("%4", cardRequest.card);

      $(".card-rows").append(template);
    });
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
      $("#card-airplane-forniture").prop("checked", requestCardToEdit.airplaneFurniture);
    });
  }
}
