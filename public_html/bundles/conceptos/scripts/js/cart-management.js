var updatingProductId = undefined;
var memberNumber = undefined;
var transportCost = 5;
var paymentType = undefined;
var paymentCurrency = undefined;
var homeCollect = false;

$(document).ready(function() {
  function getProductIdByElement(element) {
    return $(element)
      .parents(".shop-cart-product")
      .data("product");
  }

  function getCalculatePricePathByProductId(productId) {
    return $('.shop-cart-product[data-product="' + productId + '"]').data(
      "calculate-price-path"
    );
  }

  function getRemovePathByElement(element) {
    return $(element)
      .parents(".shop-cart-product")
      .data("remove-path");
  }

  function GetQuantityElement(element) {
    var productId = $(element)
      .parents(".shop-cart-product")
      .data("product");
    return $(
      '.shop-cart-product[data-product="' +
        productId +
        '"] .conceptos-product-info[data-quantity]'
    );
  }

  function getProductQuantityByElement(element) {
    var quantityElement = GetQuantityElement(element);
    return parseInt(quantityElement.text().substring(1));
  }

  function getProductPriceByProductId(productId) {
    return $(
      '.shop-cart-product[data-product="' +
        productId +
        '"] .conceptos-product-info[data-price]'
    ).data("price");
  }

  function setProductPriceByProductId(productId, price) {
    var element = $(
      '.shop-cart-product[data-product="' +
        productId +
        '"] .conceptos-product-info[data-price]'
    );
    element.data("price", price);
    element.text("$" + price.toFixed(2));
  }

  function getProductQuantityByProductId(productId) {
    var quantityElement = $(
      '.shop-cart-product[data-product="' +
        productId +
        '"] .conceptos-product-info[data-quantity]'
    );
    return parseInt(quantityElement.text().substring(1));
  }

  function getProductUuid(productId) {
    return $(".shop-cart-product[data-product='" + productId + "']").data(
      "uuid"
    );
  }

  function updateProductQuantity(element, quantity) {
    var quantityElement = GetQuantityElement(element);
    quantityElement.text("x" + quantity);

    var productCount = Number($("#conceptos-shop-cart-count").text()) + 1;
    $("#conceptos-shop-cart-count").text(productCount);
    $(".shop-cart-products-count").text(productCount);
  }

  function UpdateProductDeliveryType(productId, deliveryType) {
    var product = products.find(function(p) {
      return p.id == productId;
    });

    product.deliveryType = deliveryType;

    var isAirplaneFurniture = false;
    var isAirplaneMattress = false;
    if (deliveryType == 2) {
      isAirplaneFurniture = true;
      isAirplaneMattress = true;
    }

    ajax(
      getCalculatePricePathByProductId(productId),
      "POST",
      {
        weight: product.weight,
        ikeaPrice: product.ikeaPrice,
        isFurniture: product.isFurniture,
        isMattress: product.isMattress,
        isFragile: product.isFragile,
        isAriplaneForniture: isAirplaneFurniture,
        isAriplaneMattress: isAirplaneMattress,
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
          return p.id == productId;
        }).price = calculatedPrice;
        setProductPriceByProductId(productId, calculatedPrice);
        recalculateAllPrices();
      },
      function() {
        alert("Ha ocurrido un error calculando el precio del producto");
      }
    );
  }

  function CheckIfCanPerformHomeCollect() {
    var allInStore = true;

    products.forEach(product => {
      if (!product.type) {
        var count = getProductQuantityByProductId(product.id);
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

  function PersistCountIfNecessary(productId, count) {
    products.forEach(function(product) {
      if (product.id == productId) {
        if (product.count != count) {
          product.count = count;

          var uuid = getProductUuid(product.id);
          var path = $("#persist-count-path").val() + "/" + uuid + "/" + count;
          ajax(path, "POST", {}, function(response) {
            var count = response.count;
            $(".badge-shop-cart").text(count);
          });
        }
      }
    });
  }

  function DisableDeliveryIcon(selector) {
    $(selector).hide();
    $(selector + "-disabled").show();
  }

  function UpdateDeliveryIconsState() {
    products.forEach(function(product) {
      var selector = '.shop-cart-product[data-product="' + product.id + '"]';
      if (product.offerExists) {
        DisableDeliveryIcon(selector + " .airplane-delivery");
        DisableDeliveryIcon(selector + " .ship-delivery");
      } else if (!product.isAirplaneFurniture && !product.isAirplaneMattress) {
        DisableDeliveryIcon(selector + " .airplane-delivery");
      }
    });
  }

  function recalculateAllPrices() {
    paymentType = $("#payment-type").val();
    paymentCurrency = $("#payment-currency").val();

    CheckIfCanPerformHomeCollect();

    var totalPriceBase = 0;
    products.forEach(function(product) {
      var productId = product.id;
      var count = getProductQuantityByProductId(productId);
      var price = getProductPriceByProductId(productId);

      totalPriceBase += count * price;
      PersistCountIfNecessary(productId, count);
    });

    var totalPrice = totalPriceBase;

    var membershipDiscountSection = $(".shop-cart-membership-discount");
    if (memberNumber) {
      var membershipDiscount = Math.ceil(totalPriceBase * 0.1);
      totalPrice -= membershipDiscount;
      membershipDiscountSection.parent().removeClass("d-none");
      membershipDiscountSection.parent().addClass("d-flex");
      membershipDiscountSection.text(membershipDiscount.toFixed(2));
    } else {
      membershipDiscountSection.parent().removeClass("d-flex");
      membershipDiscountSection.parent().addClass("d-none");
    }

    var paymentTypeExtraSection = $(".shop-cart-payment-type-extra");
    if (paymentType == "two-steps") {
      var paymentTypeExtra = Math.ceil(totalPriceBase * 0.1);
      totalPrice += paymentTypeExtra;
      $(".shop-cart-payment-type").text("2 PLAZOS");
      paymentTypeExtraSection.parent().removeClass("d-none");
      paymentTypeExtraSection.parent().addClass("d-flex");
      paymentTypeExtraSection.text(paymentTypeExtra.toFixed(2));
    } else {
      $(".shop-cart-payment-type").text("1 PLAZO");
      paymentTypeExtraSection.parent().removeClass("d-flex");
      paymentTypeExtraSection.parent().addClass("d-none");
    }

    var currencyExtraSection = $(".shop-cart-currency-extra");
    if (paymentCurrency == "cuc") {
      var currencyExtra = Math.ceil(totalPriceBase * 0.2);
      totalPrice += currencyExtra;
      $(".shop-cart-currency").text("CUC");
      currencyExtraSection.parent().removeClass("d-none");
      currencyExtraSection.parent().addClass("d-flex");
      currencyExtraSection.text(currencyExtra.toFixed(2));
    } else {
      $(".shop-cart-currency").text("USD");
      currencyExtraSection.parent().removeClass("d-flex");
      currencyExtraSection.parent().addClass("d-none");
    }

    if (homeCollect) {
      transportCost = 10;
    } else {
      transportCost = 5;
    }

    $(".shop-cart-transport-cost").text("$" + transportCost.toFixed(2));
    totalPrice += transportCost;

    totalPrice = Number(totalPrice).toFixed(2);
    $(".shop-cart-total-price").text("$" + totalPrice);
  }

  function ValidateContactInfo() {
    var result = true;
    if (!$("#check_out_name").val()) {
      $("#check_out_name").removeClass("conceptos-success-input");
      $("#check_out_name").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#check_out_name").removeClass("conceptos-error-input");
      $("#check_out_name").addClass("conceptos-success-input");
    }

    if (!$("#check_out_email").val()) {
      $("#check_out_email").removeClass("conceptos-success-input");
      $("#check_out_email").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#check_out_email").removeClass("conceptos-error-input");
      $("#check_out_email").addClass("conceptos-success-input");
    }

    if (!$("#check_out_address").val()) {
      $("#check_out_address").removeClass("conceptos-success-input");
      $("#check_out_address").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#check_out_address").removeClass("conceptos-error-input");
      $("#check_out_address").addClass("conceptos-success-input");
    }

    if (!$("#check_out_phone").val() && !$("#check_out_movil").val()) {
      $("#check_out_phone").removeClass("conceptos-success-input");
      $("#check_out_phone").addClass("conceptos-error-input");
      $("#check_out_movil").removeClass("conceptos-success-input");
      $("#check_out_movil").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#check_out_phone").removeClass("conceptos-error-input");
      $("#check_out_phone").addClass("conceptos-success-input");
      $("#check_out_movil").removeClass("conceptos-error-input");
      $("#check_out_movil").addClass("conceptos-success-input");
    }

    if (!$("#termsAndConditions").prop("checked")) {
      $("#termsAndConditions").removeClass("conceptos-success-input");
      $("#termsAndConditions").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#termsAndConditions").removeClass("conceptos-error-input");
      $("#termsAndConditions").addClass("conceptos-success-input");
    }

    if (!$("#privacyPolicy").prop("checked")) {
      $("#privacyPolicy").removeClass("conceptos-success-input");
      $("#privacyPolicy").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#privacyPolicy").removeClass("conceptos-error-input");
      $("#privacyPolicy").addClass("conceptos-success-input");
    }

    return result;
  }

  $(".conceptos-add-to-cart-icon").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var url = $(this).data("path");
    ajax(
      url,
      "POST",
      {},
      function(response) {
        $("#conceptos-shop-cart-count").text(response.count);
        $.toast({
          text: "Producto añadido al carrito correctamente",
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
      function() {
        $.toast({
          text: "Ha ocurrido un error añadiendo el producto al carrito",
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

  $(".cart-quantity-up").click(function() {
    var quantity = getProductQuantityByElement(this);
    quantity++;
    updateProductQuantity(this, quantity);
    recalculateAllPrices();
  });

  $(".cart-quantity-down").click(function() {
    var quantity = getProductQuantityByElement(this);
    if (quantity >= 2) {
      quantity--;
      updateProductQuantity(this, quantity);
      recalculateAllPrices();
    }
  });

  $(".shop-cart-trash").click(function() {
    var path = getRemovePathByElement(this);
    var product = getProductIdByElement(this);
    ajax(
      path,
      "POST",
      {},
      function(response) {
        var count = response.count;
        if (count == 0) {
          window.location = $("#site-path").val();
        }
        $("#conceptos-shop-cart-count").text(count);
        $(".shop-cart-products-count").text(count);
        $('.shop-cart-product[data-product="' + product + '"]').remove();

        tempProducts = [];
        products.forEach(p => {
          if (p.id != product) {
            tempProducts.push(p);
          }
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
      function() {
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

  $(".ship-delivery").click(function() {
    var productId = getProductIdByElement(this);
    UpdateProductDeliveryType(productId, 1);
  });

  $(".airplane-delivery").click(function() {
    var productId = getProductIdByElement(this);
    UpdateProductDeliveryType(productId, 2);
  });

  $("#payment-type").change(function() {
    recalculateAllPrices();
  });

  $("#payment-currency").change(function() {
    recalculateAllPrices();
  });

  $("#shop-cart-member-number").change(function() {
    memberNumber = $(this).val();
    if (memberNumber) {
      ajax(
        "/app_dev.php/validate-membership-number/" + memberNumber,
        "POST",
        {},
        function(response) {
          var isValid = Boolean(response);
          if (isValid) {
            $("#shop-cart-member-number").removeClass("conceptos-error-input");
            $("#shop-cart-member-number").addClass("conceptos-success-input");
          } else {
            memberNumber = undefined;
            $("#shop-cart-member-number").removeClass(
              "conceptos-success-input"
            );
            $("#shop-cart-member-number").addClass("conceptos-error-input");
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

  $("#shop-cart-send-request-button").click(function(e) {
    if (ValidateContactInfo()) {
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
    } else {
      e.preventDefault();
    }
  });

  UpdateDeliveryIconsState();
  recalculateAllPrices();
});
