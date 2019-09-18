$(document).ready(function() {
  $("#email_memberNumber, #check_out_memberNumber").hide();

  $("#cb6").change(function() {
    if ($(this).prop("checked")) {
      $("#email_memberNumber, #check_out_memberNumber").show();
      $("#email_memberNumber, #check_out_memberNumber").removeClass("hidden");
      $("#email_memberNumber, #check_out_memberNumber").attr("required", true);
    } else {
      $("#email_memberNumber, #check_out_memberNumber").hide();
      $("#email_memberNumber, #check_out_memberNumber").attr("required", false);
    }
  });

  $(".favorite-product").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var btn = $(this);
    var title = "Producto añadido a favoritos correctamente";
    var remove = false;
    if ($(btn).hasClass("favorite")) {
      var path = $(btn).data("path-remove");
      title = "Producto eliminado de favoritos correctamente";
      remove = true;
    } else {
      path = $(btn).data("path-add");
    }
    ajax(
      path,
      "POST",
      {},
      function() {
        if (!remove) {
          if (!$(btn).hasClass("favorite")) {
            $(btn).addClass("favorite");
          }
        } else {
          $(btn).removeClass("favorite");
        }
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
      },
      function(error) {
        $.toast({
          text:
            "Ha ocurrido un error realizando la operación. Intente más tarde",
          showHideTransition: "fade",
          bgColor: "#f7ed4a",
          textColor: "#3f3c03",
          allowToastClose: true,
          hideAfter: 3000,
          stack: 5,
          textAlign: "center",
          position: "mid-center",
          icon: "danger",
          heading: "Error"
        });
      }
    );
  });
});

function ajax(path, type, parameters, success, error, async) {
  if (!async) {
    async = true;
  }
  if (parameters) {
    parameters["type"] = "json";
  } else {
    parameters = {
      type: "json"
    };
  }
  if (!$(".la-anim-10").hasClass("la-animate")) {
    $(".la-anim-10").addClass("la-animate");
  }
  $.ajax({
    url: path,
    type: type,
    async: async,
    data: parameters,
    success: function(response) {
      $(".la-anim-10").removeClass("la-animate");
      success(response);
    },
    error: function(error) {
      $(".la-anim-10").removeClass("la-animate");
    }
  });
}
