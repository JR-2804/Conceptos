var updatingProductId = undefined;
var memberNumber = undefined;
var transportCost = 5;
var paymentType = undefined;
var paymentCurrency = undefined;
var homeCollect = false;

$(document).ready(function() {
  $(".cart-quantity-up").click(function(e) {
    e.preventDefault();
    var product = $(this).data("product");
    var input = $('.cart-quantity-input[data-product="' + product + '"]');
    if (input.length > 0) {
      var newVal = parseInt($(input).val());
      newVal += 1;
      $(input).val(newVal);
      $(input).trigger("change");
    }
  });

  $(".cart-quantity-down").click(function(e) {
    e.preventDefault();
    var product = $(this).data("product");
    var input = $('.cart-quantity-input[data-product="' + product + '"]');
    if (input.length > 0) {
      var newVal = parseInt($(input).val());
      if (newVal >= 2) {
        newVal -= 1;
      }
      $(input).val(newVal);
      $(input).trigger("change");
    }
  });

  $(".cart-quantity-input").change(function() {
    var count = $(this).val();
    if (count <= 0) {
      $(this).val(1);
    }

    recalculateAllPrices();
  });

  $("#modal-confirm").on("show.bs.modal", function() {
    $(".modal-title").removeClass("hidden animated");
    $('.close[data-dismiss="modal"]').removeClass("hidden animated");
    $(".modal-body *").removeClass("hidden animated");
    $(".modal-footer *").removeClass("hidden animated");
  });

  $("#modal-term").on("show.bs.modal", function() {
    $(".modal-title").removeClass("hidden animated");
    $('.close[data-dismiss="modal"]').removeClass("hidden animated");
    $(".modal-body *").removeClass("hidden animated");
  });

  $(".cart-quantity-delete").click(function(e) {
    e.preventDefault();
    var path = $(this).data("path");
    var product = $(this).data("product");
    ajax(
      path,
      "POST",
      {},
      function(response) {
        var count = response.count;
        if (count == 0) {
          window.location = $("#site-path").val();
        }
        $(".badge-shop-cart").text(count);
        $('.shop-cart .row[data-product="' + product + '"]').remove();

        tempProducts = [];
        products.forEach(p => {
          if (p.id != product) tempProducts.push(p);
        });
        products = tempProducts;

        recalculateAllPrices();
        $.toast({
          text: "Producto eliminado del carrito correctamente",
          showHideTransition: "fade",
          bgColor: "#f7ed4a",
          textColor: "#3f3c03",
          allowToastClose: true,
          hideAfter: 3000,
          stack: 5,
          textAlign: "center",
          position: "mid-center",
          icon: "success",
          heading: "Correcto"
        });
      },
      function(error) {
        $.toast({
          text: "Ha ocurrido un error eliminando el producto del carrito",
          showHideTransition: "fade",
          bgColor: "#f7ed4a",
          textColor: "#3f3c03",
          allowToastClose: true,
          hideAfter: 3000,
          stack: 5,
          textAlign: "center",
          position: "mid-center",
          icon: "error",
          heading: "Error"
        });
      }
    );
  });

  $(".delivery-type select").change(function() {
    var deliveryType = $(this).val();
    var updatingProductId = $(this).data("product");
    var product = products.find(function(p) {
      return p.id == updatingProductId;
    });

    product.deliveryType = deliveryType;

    var isAriplaneForniture = false;
    var isAriplaneMattress = false;
    if (deliveryType == 2) {
      isAriplaneForniture = true;
      isAriplaneMattress = true;
    }

    ajax(
      $(this).data("path"),
      "POST",
      {
        weight: product.weight,
        ikeaPrice: product.ikeaPrice,
        isFurniture: product.isFurniture,
        isMattress: product.isMattress,
        isFragile: product.isFragile,
        isAriplaneForniture: isAriplaneForniture,
        isAriplaneMattress: isAriplaneMattress,
        isOversize: product.isOversize,
        isTableware: product.isTableware,
        isLamp: product.isLamp,
        numberOfPackages: product.numberOfPackages,
        isFaucet: product.isFaucet,
        isGrill: product.isGrill,
        isShelf: product.isShelf,
        isDesk: product.isDesk,
        isBookcase: product.isBookcase,
        isComoda: product.isComoda,
        isRepisa: product.isRepisa
      },
      function(response) {
        var calculatedPrice = Number(response);
        products.find(function(p) {
          return p.id == updatingProductId;
        }).price = calculatedPrice;
        $(
          '.total-price-product[data-product="' + updatingProductId + '"]'
        ).data("price", calculatedPrice);
        recalculateAllPrices();
      },
      function() {
        alert("Ha ocurrido un error calculando el precio del producto");
      }
    );
  });

  $("#payment-type").change(function() {
    recalculateAllPrices();
  });

  $("#payment-currency").change(function() {
    recalculateAllPrices();
  });

  $(".shop-cart-membership-checkbox").change(function() {
    if ($(this).prop("checked")) {
      $("#shop-cart-membership-number").show();
      $("#shop-cart-membership-number-check").show();
    } else {
      $("#shop-cart-membership-number-check").hide();
      $("#shop-cart-membership-number").hide();
      $("#shop-cart-membership-number").val("");
      $("#shop-cart-membership-number").trigger("change");
      memberNumber = undefined;
      recalculateAllPrices();
    }
  });

  $(".home-collect-checkbox").change(function() {
    homeCollect = $(this).prop("checked");
    recalculateAllPrices();
  });

  $("#shop-cart-membership-number-check").click(function() {
    var membershipNumber = $("#shop-cart-membership-number").val();
    if (membershipNumber) {
      ajax(
        "/app_dev.php/validate-membership-number/" + membershipNumber,
        "POST",
        {},
        function(response) {
          var isValid = Boolean(response);
          if (isValid) {
            $("#shop-cart-membership-number").removeClass("error-form-control");
            $("#shop-cart-membership-number").addClass("success-form-control");
            memberNumber = membershipNumber;
          } else {
            $("#shop-cart-membership-number").removeClass(
              "success-form-control"
            );
            $("#shop-cart-membership-number").addClass("error-form-control");
            memberNumber = undefined;
          }

          recalculateAllPrices();
        },
        function() {
          memberNumber = undefined;
          recalculateAllPrices();
          alert("Ha ocurrido un error validando el número de miembro");
        }
      );
    } else {
      memberNumber = undefined;
      recalculateAllPrices();
    }
  });

  $("#type-select").change(function() {
    if ($(this).val() === "facture") {
      $("#prefactures-select").show();
    } else {
      $("#prefactures-select").hide();
    }
  });

  $("#send-request-button").click(function(e) {
    if (
      !$("#check_out_name").val() ||
      !$("#check_out_email").val() ||
      !$("#check_out_address").val() ||
      (!$("#check_out_phone").val() && !$("#check_out_movil").val()) ||
      !$("#termsAndConditions").prop("checked") ||
      !$("#privacyPolicy").prop("checked")
    ) {
      alert("Rellene todos los campos");
      e.preventDefault();
    } else {
      $("#memberNumber").val(memberNumber);
      $("#transportCost").val(transportCost);
      $("#paymentType").val(paymentType);
      $("#paymentCurrency").val(paymentCurrency);
      $("#products").val(JSON.stringify(products));

      $("#check_out_type").val($("#type-select").val());
      $("#check_out_ignoreTransport").val(
        $("#ignoreTransport").prop("checked")
      );

      if ($("#type-select").val() === "facture") {
        $("#check_out_prefacture").val($("#prefactures-select").val());
      }
    }
  });

  recalculateAllPrices();
});

function persistProductCount(product, count) {
  var path = $("#persist-count").val() + "/" + product + "/" + count;
  ajax(path, "POST", {}, function(response) {
    var count = response.count;
    $(".badge-shop-cart").text(count);
  });
}

function CheckIfCanPerformHomeCollect() {
  var allInStore = true;

  products.forEach(product => {
    if (!product.type) {
      var count = $(
        '.cart-quantity-input[data-product="' + product.id + '"]'
      ).val();
      if (parseInt(count) > parseInt(product.storeCount || 0)) {
        allInStore = false;
      }
    }
  });

  if (!allInStore) {
    $("#home-collect").show();
  } else {
    $("#home-collect").hide();
    homeCollect = false;
    transportCost = 5;
  }
}

function recalculateAllPrices() {
  paymentType = $("#payment-type").val();
  paymentCurrency = $("#payment-currency").val();

  CheckIfCanPerformHomeCollect();

  var totalPrice = 0;
  var cucExtra = 0;
  $(".total-price-product").each(function(i, input) {
    var productId = $(input).data("product");
    var price = $(input).data("price");
    var offer = $(input).data("offer");
    var offerOnlyForMembers = $(input).data("offer-members");
    var count = $(
      '.cart-quantity-input[data-product="' + productId + '"]'
    ).val();

    if (offer) {
      if (offerOnlyForMembers && memberNumber) {
        price = offer;
      } else if (!offerOnlyForMembers) {
        price = offer;
      }
    }

    if (paymentCurrency == "cuc") {
      var productCucExtra = Math.ceil(price * 0.2);
      cucExtra += productCucExtra;
      price += productCucExtra;
    }

    totalPrice += count * price;

    var subtotal = Math.ceil(count * price);
    $(
      '.total-price-product[data-product="' + productId + '"] .product-count'
    ).text(count + " X");
    $(
      '.total-price-product[data-product="' + productId + '"] .product-price'
    ).text("$" + price.toFixed(2));
    $(
      '.total-price-product[data-product="' + productId + '"] .product-subtotal'
    ).text("$" + subtotal.toFixed(2));

    products.forEach(function(product) {
      if (product.id == productId) {
        if (product.count != count) {
          product.count = count;

          var uuid = $(
            '.cart-quantity-up[data-product="' + product.id + '"]'
          ).data("uuid");
          persistProductCount(uuid, count);
        }
      }
    });
  });

  if (paymentType == "two-steps") {
    var twoStepExtra = Math.ceil(totalPrice * 0.2);
    $("#two-steps").text("$" + twoStepExtra.toFixed(2));
    $(".two-steps-row").show();
    totalPrice += twoStepExtra;
  } else {
    $(".two-steps-row").hide();
  }

  if (paymentCurrency == "cuc") {
    $("#cuc-extra").text("$" + cucExtra.toFixed(2));
    $(".cuc-extra-row").show();
  } else {
    $(".cuc-extra-row").hide();
  }

  if (memberNumber) {
    var discount = Math.floor(totalPrice * 0.1).toFixed(2);
    $("#membership-discount").text("$" + discount);
    $(".membership-discount-row").show();
    totalPrice -= discount;
  } else {
    $(".membership-discount-row").hide();
  }

  if (homeCollect) {
    transportCost = 10;
  } else {
    transportCost = 5;
  }
  $("#transport-cost").text("$" + transportCost.toFixed(2));
  totalPrice += transportCost;

  totalPrice = Number(totalPrice).toFixed(2);
  $(".total-price").data("total-price", totalPrice);
  $(".total-price").text("$" + totalPrice);
}
