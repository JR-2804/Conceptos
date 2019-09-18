var countLast = 0;
var visiblesLast = [];
var invisiblesLast = [];

var countStore = [];
var visiblesStore = [];
var invisiblesStore = [];
$(document).ready(function() {
  if (window.screen.width > 1000) {
    $("#in-store-short").remove();
    $("#lasted-short").remove();
  } else {
    $("#in-store-large").remove();
    $("#lasted-large").remove();
  }

  var inStoreSection = $("#in-store-section")[0];
  if (!inStoreSection) {
    return;
  }

  var lastedSection = $("#lasted-section")[0];
  if (!lastedSection) {
    return;
  }

  countLast = lastedSection.getAttribute("data-count");
  countStore = inStoreSection.getAttribute("data-count");

  $(".lasted-product.no-hidden-offer").each(function(_index, current) {
    visiblesLast.push($(current).data("index"));
  });

  $(".lasted-product.hidden-offer").each(function(_index, current) {
    invisiblesLast.push($(current).data("index"));
  });

  $(".btn-previous-last").click(function() {
    if (visiblesLast[0] === 0) {
      return;
    }
    var lastVisible = visiblesLast[visiblesLast.length - 1];
    var lastNoVisible = invisiblesLast[invisiblesLast.length - 1];

    $('.lasted-product[data-index="' + lastVisible + '"]').addClass(
      "hidden-offer"
    );
    $('.lasted-product[data-index="' + lastVisible + '"]').removeClass(
      "no-hidden-offer"
    );

    $('.lasted-product[data-index="' + lastNoVisible + '"]').addClass(
      "no-hidden-offer"
    );
    $('.lasted-product[data-index="' + lastNoVisible + '"]').removeClass(
      "hidden-offer"
    );

    var tmpVisibles = [lastNoVisible];
    var tmpNoVisibles = [lastVisible];
    visiblesLast.splice(visiblesLast.length - 1, 1);
    visiblesLast = tmpVisibles.concat(visiblesLast);

    invisiblesLast.splice(invisiblesLast.length - 1, 1);
    invisiblesLast = tmpNoVisibles.concat(invisiblesLast);
  });

  $(".btn-next-last").click(function() {
    var productsLimit = Number.parseInt($(this).data("products-limit")) - 1;
    if (visiblesLast[productsLimit] >= countLast - 1) {
      return;
    }
    $(
      '.lasted-product.no-hidden-offer[data-index="' + visiblesLast[0] + '"]'
    ).addClass("hidden-offer");
    $(
      '.lasted-product.no-hidden-offer[data-index="' + visiblesLast[0] + '"]'
    ).removeClass("no-hidden-offer");

    $(
      '.lasted-product.hidden-offer[data-index="' + invisiblesLast[0] + '"]'
    ).addClass("no-hidden-offer");
    $(
      '.lasted-product.hidden-offer[data-index="' + invisiblesLast[0] + '"]'
    ).removeClass("hidden-offer");

    invisiblesLast.push(visiblesLast[0]);
    visiblesLast.push(invisiblesLast[0]);
    invisiblesLast.splice(0, 1);
    visiblesLast.splice(0, 1);
  });

  $(".store-product.no-hidden-offer").each(function(_index, current) {
    visiblesStore.push($(current).data("index"));
  });

  $(".store-product.hidden-offer").each(function(_index, current) {
    invisiblesStore.push($(current).data("index"));
  });

  $(".btn-previous-store").click(function() {
    if (visiblesStore[0] === 0) {
      return;
    }
    var lastVisible = visiblesStore[visiblesStore.length - 1];
    var lastNoVisible = invisiblesStore[invisiblesStore.length - 1];

    $('.store-product[data-index="' + lastVisible + '"]').addClass(
      "hidden-offer"
    );
    $('.store-product[data-index="' + lastVisible + '"]').removeClass(
      "no-hidden-offer"
    );

    $('.store-product[data-index="' + lastNoVisible + '"]').addClass(
      "no-hidden-offer"
    );
    $('.store-product[data-index="' + lastNoVisible + '"]').removeClass(
      "hidden-offer"
    );

    var tmpVisibles = [lastNoVisible];
    var tmpNoVisibles = [lastVisible];
    visiblesStore.splice(visiblesStore.length - 1, 1);
    visiblesStore = tmpVisibles.concat(visiblesStore);

    invisiblesStore.splice(invisiblesStore.length - 1, 1);
    invisiblesStore = tmpNoVisibles.concat(invisiblesStore);
  });

  $(".btn-next-store").click(function() {
    var productsLimit = Number.parseInt($(this).data("products-limit")) - 1;
    if (visiblesStore[productsLimit] >= countStore - 1) {
      return;
    }
    $(
      '.store-product.no-hidden-offer[data-index="' + visiblesStore[0] + '"]'
    ).addClass("hidden-offer");
    $(
      '.store-product.no-hidden-offer[data-index="' + visiblesStore[0] + '"]'
    ).removeClass("no-hidden-offer");

    $(
      '.store-product.hidden-offer[data-index="' + invisiblesStore[0] + '"]'
    ).addClass("no-hidden-offer");
    $(
      '.store-product.hidden-offer[data-index="' + invisiblesStore[0] + '"]'
    ).removeClass("hidden-offer");

    invisiblesStore.push(visiblesStore[0]);
    visiblesStore.push(invisiblesStore[0]);
    invisiblesStore.splice(0, 1);
    visiblesStore.splice(0, 1);
  });
});
