$(document).ready(function() {
  $(".accept-external-request-button").click(function() {
    var path = $(this).data("path");
    ajax(
      path,
      "POST",
      {},
      function(response) {
        if (response == -1) {
          error();
          return;
        }
        $(".external-request[data-id='" + response + "']").hide();
        success();
      },
      function() {
        error();
      }
    );
  });

  function success() {
    $.toast({
      text: "Pedido externo aceptado correctamente",
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
  }

  function error() {
    $.toast({
      text: "Ha ocurrido un error aceptando el pedido externo",
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
});
