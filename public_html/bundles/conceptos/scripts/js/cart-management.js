$(document).ready(function() {
  let onSuccess = function(response) {
    var count = response.count;
    var title = "Producto añadido al carrito correctamente";
    if (response.exist) {
      title = "El producto seleccionado ya está en su carrito de compras";
    }
    $(".badge-shop-cart").text(count);
    $.toast({
      text: title,
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
  };

  let onError = function() {
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
  };

  $(".conceptos-add-to-cart-icon").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var url = $(this).data("path");
    ajax(url, "POST", {}, onSuccess, onError);
  });
});
