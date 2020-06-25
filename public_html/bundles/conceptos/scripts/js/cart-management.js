var updatingProductId = undefined;
var memberNumber = undefined;
var transportCost = 5;
var homeDelivery = undefined;
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
        '"] .cart-quantity-input'
    );
  }

  function getProductQuantityByElement(element) {
    return GetQuantityElement(element).data("quantity");
  }

  function getStoreCountByElement(element) {
    return GetQuantityElement(element).data("store-count");
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
    return $(
      '.shop-cart-product[data-product="' +
        productId +
        '"] .cart-quantity-input'
    ).data("quantity");
  }

  function getProductWeightByProductId(productId) {
    return $('.shop-cart-product[data-product="' + productId + '"]').data(
      "weight"
    );
  }

  function getProductIkeaPriceByProductId(productId) {
    return $('.shop-cart-product[data-product="' + productId + '"]').data(
      "ikea-price"
    );
  }

  function getProductUuid(productId) {
    return $(".shop-cart-product[data-product='" + productId + "']").data(
      "uuid"
    );
  }

  function UpdateProductQuantity(element, quantity, productCount) {
    var quantityElement = GetQuantityElement(element);
    quantityElement.data("quantity", quantity);
    quantityElement.val(quantity);

    if (productCount > 99) {
      $("#conceptos-shop-cart-count").text("+99");
    } else {
      $("#conceptos-shop-cart-count").text(productCount);
    }
    $("#conceptos-shop-cart-count").data("count", productCount);
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
            UpdateProductsSummary(response.html);
            CreateCartSummaryActions();
            var count = response.count;
            $(".badge-shop-cart").text(count);
          });
        }
      }
    });
  }

  function HideBothIcons(selector) {
    $(selector + " .delivery-text").hide();
    $(selector + " .airplane-delivery").hide();
    $(selector + " .ship-delivery").hide();
  }

  function DisplayOnlyAirplaneDelivery(selector) {
    $(selector + " .ship-delivery").hide();
    $(selector + " .airplane-delivery").addClass("airplane-delivery-focused");
  }

  function DisplayOnlyShipDelivery(selector) {
    $(selector + " .airplane-delivery").hide();
    $(selector + " .ship-delivery").addClass("ship-delivery-focused");
  }

  function UpdateDeliveryIconsState() {
    products.forEach(function(product) {
      var selector = '.shop-cart-product[data-product="' + product.id + '"]';
      if (product.type) {
        HideBothIcons(selector);
      } else if (product.offerExists) {
        HideBothIcons(selector);
        if (product.storeCount && product.storeCount > 0) {
          $(selector + " .conceptos-in-store-product-badge").show();
        }
      } else if (product.storeCount && product.storeCount > 0) {
        HideBothIcons(selector);
        $(selector + " .conceptos-in-store-product-badge").show();
      } else if (!product.isFurniture && !product.isMattress) {
        DisplayOnlyAirplaneDelivery(selector);
      } else if (
        (product.isFurniture && !product.isAirplaneFurniture) ||
        (product.isMattress && !product.isAirplaneMattress)
      ) {
        DisplayOnlyShipDelivery(selector);
      } else {
        $(selector + " .ship-delivery").addClass("ship-delivery-focused");
      }
    });
  }

  function recalculateAllPrices() {
    if ($("#yes").prop("checked")) {
      homeDelivery = "yes";
    } else {
      homeDelivery = "no";
    }
    if ($("#cuc").prop("checked")) {
      paymentCurrency = "cuc";
    } else {
      paymentCurrency = "usd";
    }
    if ($("#two-steps").prop("checked")) {
      paymentType = "two-steps";
    } else {
      paymentType = "total";
    }

    CheckIfCanPerformHomeCollect();

    var totalWeight = 0;
    var totalIkeaPriceWithTaxes = 0;
    var totalPriceBase = 0;
    products.forEach(function(product) {
      var productId = product.id;
      var count = getProductQuantityByProductId(productId);
      var price = getProductPriceByProductId(productId);
      var weight = getProductWeightByProductId(productId);
      var ikeaPrice = getProductIkeaPriceByProductId(productId);

      totalWeight += count * weight;
      totalIkeaPriceWithTaxes += count * ikeaPrice;
      totalPriceBase += count * price;
      PersistCountIfNecessary(productId, count);
    });
    totalIkeaPriceWithTaxes += totalIkeaPriceWithTaxes * 0.07;

    var totalPrice = totalPriceBase;

    var membershipDiscountSection = $(".shop-cart-membership-discount");
    var membershipBalanceDiscountSection = $(".shop-cart-balance-discount");
    if (memberNumber) {
      var membershipDiscount = Math.ceil(totalPriceBase * 0.1);
      var membershipBalanceDiscount = Math.ceil(
        membershipBalanceDiscountSection.data("balance")
      );
      totalPrice -= membershipDiscount;
      totalPrice -= membershipBalanceDiscount;

      membershipDiscountSection.parent().removeClass("d-none");
      membershipDiscountSection.parent().addClass("d-flex");
      membershipDiscountSection.text("$" + membershipDiscount.toFixed(2));

      if (membershipBalanceDiscount > 0) {
        membershipBalanceDiscountSection.parent().removeClass("d-none");
        membershipBalanceDiscountSection.parent().addClass("d-flex");
        membershipBalanceDiscountSection.text(
          "$" + membershipBalanceDiscount.toFixed(2)
        );
      }
    } else {
      membershipDiscountSection.parent().removeClass("d-flex");
      membershipDiscountSection.parent().addClass("d-none");

      membershipBalanceDiscountSection.parent().removeClass("d-flex");
      membershipBalanceDiscountSection.parent().addClass("d-none");
    }

    var paymentTypeExtraSection = $(".shop-cart-payment-type-extra");
    var firstPaymentSection = $(".first-payment");
    if (paymentType == "two-steps") {
      var paymentTypeExtra = Math.ceil(totalPriceBase * 0.1);
      totalPrice += paymentTypeExtra;
      $(".shop-cart-payment-type").text("2 PLAZOS");

      firstPaymentSection.parent().removeClass("d-none");
      firstPaymentSection.parent().addClass("d-flex");

      paymentTypeExtraSection.parent().removeClass("d-none");
      paymentTypeExtraSection.parent().addClass("d-flex");
      paymentTypeExtraSection.text("$" + paymentTypeExtra.toFixed(2));
    } else {
      $(".shop-cart-payment-type").text("1 PLAZO");

      firstPaymentSection.parent().removeClass("d-flex");
      firstPaymentSection.parent().addClass("d-none");

      paymentTypeExtraSection.parent().removeClass("d-flex");
      paymentTypeExtraSection.parent().addClass("d-none");
    }

    var currencyExtraSection = $(".shop-cart-currency-extra");
    if (paymentCurrency == "cuc") {
      var currencyExtra = Math.ceil(totalPriceBase * 0.15);
      totalPrice += currencyExtra;
      $(".shop-cart-currency").text("CUC");
      currencyExtraSection.parent().removeClass("d-none");
      currencyExtraSection.parent().addClass("d-flex");
      currencyExtraSection.text("$" + currencyExtra.toFixed(2));
    } else {
      $(".shop-cart-currency").text("USD");
      currencyExtraSection.parent().removeClass("d-flex");
      currencyExtraSection.parent().addClass("d-none");
    }

    var bagsSection = $(".shop-cart-bag-cost");
    var numberOfBags = bagsSection.data("bags");
    if (numberOfBags > 0) {
      var bagsExtra = numberOfBags * 7;
      totalPrice += bagsExtra;
      bagsSection.parent().removeClass("d-none");
      bagsSection.parent().addClass("d-flex");
      bagsSection.text("$" + bagsExtra.toFixed(2));
    } else {
      bagsSection.parent().removeClass("d-flex");
      bagsSection.parent().addClass("d-none");
    }

    if (homeDelivery == "yes") {
      transportCost = 10;
    } else {
      transportCost = 5;
    }

    if (totalPrice < 0) {
      totalPrice = 0;
    }

    $(".shop-cart-total-weight").text(Number(totalWeight).toFixed(2) + " Kg");
    $(".shop-cart-ikea-price").text(
      "$" + Number(totalIkeaPriceWithTaxes).toFixed(2)
    );
    $(".shop-cart-total-price").text("$" + Number(totalPriceBase).toFixed(2));
    $(".shop-cart-transport-cost").text("$" + transportCost.toFixed(2));
    totalPrice += transportCost;
    $(".shop-cart-total-price-with-extra").text(
      "$" + Number(totalPrice).toFixed(2)
    );
    firstPaymentSection.text("$" + Math.ceil(totalPrice / 1.8).toFixed(2));

    MoveBagsBadge();
  }

  function DisplayMembershipSuccess() {
    $("#shop-cart-member-number").addClass("conceptos-success-input");
    $("#shop-cart-member-number").removeClass("conceptos-error-input");

    $(".conceptos-request-success-icon").show();
    $(".conceptos-request-error-icon").hide();
    $(".conceptos-request-loading-icon").hide();
  }

  function DisplayMembershipError() {
    $("#shop-cart-member-number").addClass("conceptos-error-input");
    $("#shop-cart-member-number").removeClass("conceptos-success-input");

    $(".conceptos-request-success-icon").hide();
    $(".conceptos-request-error-icon").show();
    $(".conceptos-request-loading-icon").hide();
  }

  function ValidateContactInfo() {
    var result = true;
    if (!$("#check_out_firstName").val()) {
      $("#check_out_firstName").removeClass("conceptos-success-input");
      $("#check_out_firstName").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#check_out_firstName").removeClass("conceptos-error-input");
      $("#check_out_firstName").addClass("conceptos-success-input");
    }

    if (!$("#check_out_lastName").val()) {
      $("#check_out_lastName").removeClass("conceptos-success-input");
      $("#check_out_lastName").addClass("conceptos-error-input");
      result = false;
    } else {
      $("#check_out_lastName").removeClass("conceptos-error-input");
      $("#check_out_lastName").addClass("conceptos-success-input");
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

  function CreateCartSummaryActions() {
    $(".cart-quantity-up").click(function() {
      var quantity = getProductQuantityByElement(this);
      var storeCount = getStoreCountByElement(this);
      if (!storeCount || quantity < storeCount) {
        quantity++;
        var productCount =
          Number($("#conceptos-shop-cart-count").data("count")) + 1;
        UpdateProductQuantity(this, quantity, productCount);
        recalculateAllPrices();
      }
    });

    $(".cart-quantity-down").click(function() {
      var quantity = getProductQuantityByElement(this);
      if (quantity >= 2) {
        quantity--;
        var productCount =
          Number($("#conceptos-shop-cart-count").data("count")) - 1;
        UpdateProductQuantity(this, quantity, productCount);
        recalculateAllPrices();
      }
    });

    $(".cart-quantity-input").change(function() {
      var oldQuantity = $(this).data("quantity");
      var newQuantity = $(this).val();
      var productCount =
        Number($("#conceptos-shop-cart-count").data("count")) +
        (newQuantity - oldQuantity);
      UpdateProductQuantity(this, newQuantity, productCount);
      recalculateAllPrices();
    });

    $(".shop-cart-trash").click(function() {
      var path = getRemovePathByElement(this);
      var product = getProductIdByElement(this);
      ajax(
        path,
        "POST",
        {},
        function(response) {
          if (response.count > 99) {
            $("#conceptos-shop-cart-count").text("+99");
          } else {
            $("#conceptos-shop-cart-count").text(response.count);
          }
          $("#conceptos-shop-cart-count").data("count", response.count);
          $(".shop-cart-products-count").text(response.count);
          products = response.shopCartProducts;
          $('.shop-cart-product[data-product="' + product + '"]').remove();
          recalculateAllPrices();

          UpdateProductsSummary(response.html);
          CreateCartSummaryActions();
          $.toast({
            text: "Producto eliminado del carrito correctamente",
            showHideTransition: "fade",
            bgColor: "#c2b930",
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
            bgColor: "#c2b930",
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

    $(".add-bag-icon").click(function() {
      var url = $(this).data("path");
      ajax(
        url,
        "POST",
        {},
        function(response) {
          UpdateProductsSummary(response.html);
          CreateCartSummaryActions();
          $.toast({
            text: "Bolsas añadidas al carrito correctamente",
            showHideTransition: "fade",
            bgColor: "#c2b930",
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
            text: "Ha ocurrido un error añadiendo las bolsas al carrito",
            showHideTransition: "fade",
            bgColor: "#c2b930",
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

    $(".remove-bag-icon").click(function() {
      var url = $(this).data("path");
      ajax(
        url,
        "POST",
        {},
        function(response) {
          UpdateProductsSummary(response.html);
          CreateCartSummaryActions();
          $.toast({
            text: "Bolsas eliminadas del carrito correctamente",
            showHideTransition: "fade",
            bgColor: "#c2b930",
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
            text: "Ha ocurrido un error eliminando las bolsas del carrito",
            showHideTransition: "fade",
            bgColor: "#c2b930",
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
  }

  function UpdateProductsSummary(html) {
    $("#products-summary")
      .children()
      .remove();
    $("#products-summary").append(html);

    $("#products-summary-shop-cart")
      .children()
      .remove();
    $("#products-summary-shop-cart").append(html);

    MoveBagsBadge();
  }

  function MoveBagsBadge() {
    $("#cartPreviewModal .conceptos-badge")
      .children()
      .remove("#shop-cart-bags-badge");
    $("#cartPreviewModal #shop-cart-bags-badge").prependTo(
      "#cartPreviewModal .conceptos-badge"
    );

    $("form .conceptos-badge")
      .children()
      .remove("#shop-cart-bags-badge");
    $("form #shop-cart-bags-badge").prependTo("form .conceptos-badge");
  }

  function OnCartIconCLick(e) {
    e.preventDefault();
    e.stopPropagation();

    var authenticated = $("#user-authenticated").val();
    if (authenticated) {
      var url = $(this).data("path");
      ajax(
        url,
        "POST",
        {},
        function(response) {
          if (response.count > 99) {
            $("#conceptos-shop-cart-count").text("+99");
          } else {
            $("#conceptos-shop-cart-count").text(response.count);
          }
          $("#conceptos-shop-cart-count").data("count", response.count);
          $(".shop-cart-products-count").text(response.count);
          products = response.shopCartProducts;
          UpdateProductsSummary(response.html);
          CreateCartSummaryActions();
          recalculateAllPrices();
          $.toast({
            text: "Producto añadido al carrito correctamente",
            showHideTransition: "fade",
            bgColor: "#c2b930",
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
            bgColor: "#c2b930",
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
    } else {
      $("#loginModal #login-info").text(
        "Necesita estar registrado para poder comprar"
      );
      $("#loginModal").modal();
    }
  }

  // ==================================================

  let containerBlog = document.querySelector(".post-single");
  if (containerBlog !== null) {
    containerBlog.addEventListener("DOMNodeInserted", e => {
      e.preventDefault();
      e.stopPropagation();

      let cartIcon = e.target.querySelector(".conceptos-add-to-cart-icon");
      if (cartIcon) {
        cartIcon.addEventListener("click", OnCartIconCLick);
      }
    });
  }

  // ==================================================

  $(".conceptos-add-to-cart-icon").click(OnCartIconCLick);

  $(".conceptos-add-gift-card-to-cart-button").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    $("#gift-card-modal-toggle").click();

    ajax(
      $(this).data("path"),
      "POST",
      {},
      function(response) {
        var title = "Tarjeta añadida al carrito correctamente";
        if (response.exist) {
          title = "La tarjeta seleccionada ya está en su carrito de compras";
        }
        if (response.count > 99) {
          $("#conceptos-shop-cart-count").text("+99");
        } else {
          $("#conceptos-shop-cart-count").text(response.count);
        }
        $("#conceptos-shop-cart-count").data("count", response.count);
        products = response.shopCartProducts;
        UpdateProductsSummary(response.html);
        CreateCartSummaryActions();
        recalculateAllPrices();
        $.toast({
          text: title,
          showHideTransition: "fade",
          bgColor: "#c2b930",
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
          text: "Ha ocurrido un error añadiendo la tarjeta al carrito",
          showHideTransition: "fade",
          bgColor: "#c2b930",
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
    if ($(this).hasClass("ship-delivery-focused")) {
      return;
    }

    var productId = getProductIdByElement(this);
    $(this).addClass("ship-delivery-focused");
    $(
      '.shop-cart-product[data-product="' + productId + '"] .airplane-delivery'
    ).removeClass("airplane-delivery-focused");

    UpdateProductDeliveryType(productId, 1);
  });

  $(".airplane-delivery").click(function() {
    if ($(this).hasClass("airplane-delivery-focused")) {
      return;
    }

    var productId = getProductIdByElement(this);
    $(this).addClass("airplane-delivery-focused");
    $(
      '.shop-cart-product[data-product="' + productId + '"] .ship-delivery'
    ).removeClass("ship-delivery-focused");

    UpdateProductDeliveryType(productId, 2);
  });

  $("#home-delivery").change(function() {
    recalculateAllPrices();
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
            DisplayMembershipSuccess();
          } else {
            memberNumber = undefined;
            DisplayMembershipError();
          }
          recalculateAllPrices();
        },
        function() {
          DisplayMembershipError();
          memberNumber = undefined;
          recalculateAllPrices();
          alert("Ha ocurrido un error validando el número de miembro");
        }
      );
    } else {
      DisplayMembershipError();
      memberNumber = undefined;
      recalculateAllPrices();
    }
  });

  $(".home-collect-checkbox").change(function() {
    homeCollect = $(this).prop("checked");
    recalculateAllPrices();
  });

  $("#type-select").change(function() {
    if ($(this).val() === "facture") {
      $("#request-select").show();
      $("#prefactures-select").show();
      $("#budget").hide();
      $("#payment").hide();
      $("#date").hide();
    } else if ($(this).val() === "prefacture") {
      $("#request-select").show();
      $("#prefactures-select").hide();
      $("#budget").hide();
      $("#payment").hide();
      $("#date").hide();
    } else if ($(this).val() === "external-request") {
      $("#request-select").hide();
      $("#prefactures-select").hide();
      $("#budget").show();
      $("#payment").show();
      $("#date").show();
    } else {
      $("#request-select").hide();
      $("#prefactures-select").hide();
      $("#budget").hide();
      $("#payment").hide();
      $("#date").hide();
    }
  });

  $(".combo-product-toggle").click(function() {
    var uuid = $(this).data("uuid");
    $(".shop-cart-product[data-uuid='" + uuid + "']").toggleClass("opened");
  });

  $("form[name='check_out']").submit(function(e) {
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
        $("#check_out_request").val($("#request-select").val());
        $("#check_out_prefacture").val($("#prefactures-select").val());
      }
      if ($("#type-select").val() === "prefacture") {
        $("#check_out_request").val($("#request-select").val());
      }
      if ($("#type-select").val() === "external-request") {
        var budget = $("#budget input").val();
        var payment = $("#payment input").val();
        var date = $("#date input").val();
        if (!budget || !payment || !date) {
          e.preventDefault();
        } else {
          $("#check_out_budget").val(budget);
          $("#check_out_payment").val(payment);
          $("#check_out_date").val(date);
        }
      }
    } else {
      e.preventDefault();
    }
  });

  CreateCartSummaryActions();
  UpdateDeliveryIconsState();
  recalculateAllPrices();
});
