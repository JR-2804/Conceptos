$(document).ready(function() {
  $(".cid-qUcSHd6NCd")
    .find("*")
    .removeClass("hidden animated");

  setTimeout(function() {
    var maxHeight = 0;
    var maxHeightText = 0;
    $(".plan-member").each(function(i, c) {
      if ($(c).height() > maxHeight) {
        maxHeight = $(c).height();
      }
      if (
        $(c)
          .find(".list-group-item:first")
          .height() > maxHeightText
      ) {
        maxHeightText = $(c)
          .find(".list-group-item:first")
          .height();
      }
    });
    $(".plan-member").height(maxHeight);
    $(".plan-member")
      .find(".list-group-item:first-child")
      .height(maxHeightText);
  }, 3000);

  var maxHeightGift = 0;
  var maxHeightTextGift = 0;
  $(".plan-gift").each(function(i, g) {
    if ($(g).height() > maxHeightGift) {
      maxHeightGift = $(g).height();
    }
    if (
      $(g)
        .find(".list-group-item:first")
        .height() > maxHeightTextGift
    ) {
      maxHeightTextGift = $(g)
        .find(".list-group-item:first")
        .height();
    }
  });
  $(".plan-gift").height(maxHeightGift);
  $(".plan-gift")
    .find(".list-group-item:first-child")
    .height(maxHeightTextGift);

  $(".btn-add-card").click(function(e) {
    e.preventDefault();
    var path = $(this).attr("href");
    ajax(
      path,
      "POST",
      {},
      function(response) {
        var count = response.count;
        $(".badge-shop-cart").text(count);
        $.toast({
          text: "Tarjeta añadida al carrito correctamente",
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
      function(error) {
        $.toast({
          text:
            "Ha ocurrido un error añadiendo el la tarjeta al carrito de compras",
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

  $(".btn-modal-add-card").click(function() {
    var path = $(this).data("type");
    var name = $("#card-name").val();
    if (name) {
      $("#modal-name").modal("hide");
      $("#card-name").val("");
      ajax(
        path,
        "POST",
        { name: name },
        function(response) {
          var count = response.count;
          $(".badge-shop-cart").text(count);
          $.toast({
            text: "Tarjeta añadida al carrito correctamente",
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
        function(error) {
          $.toast({
            text:
              "Ha ocurrido un error añadiendo el la tarjeta al carrito de compras",
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
      $.toast({
        text: "Debe introducir el nombre de la persona",
        showHideTransition: "fade",
        bgColor: "#c2b930",
        textColor: "#3f3c03",
        allowToastClose: true,
        hideAfter: 1000,
        stack: 5,
        textAlign: "center",
        position: "mid-center",
        icon: "error",
        heading: "Error"
      });
    }
  });

  $("#modal-name").on("show.bs.modal", function(e) {
    $(".modal-title").removeClass("hidden animated");
    $('.close[data-dismiss="modal"]').removeClass("hidden animated");
    $(".modal-body *").removeClass("hidden animated");
    $(".modal-footer *").removeClass("hidden animated");

    var button = $(e.relatedTarget);
    var type = $(button).data("type");

    $(".btn-modal-add-card").data("type", type);
  });

  $("#service-select").change(function() {
    var title = $(this).val();
    if (title) {
      window.location = $(this)
        .children('option[value="' + title + '"]')
        .data("path");
    }
  });
});
