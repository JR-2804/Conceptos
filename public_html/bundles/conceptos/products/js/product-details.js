$(document).ready(function() {
  var description = $(".description-content").text();
  var newDescription = description.substring(
    0,
    description.indexOf("Materiales")
  );
  newDescription +=
    "<br>" +
    description.substring(
      description.indexOf("Materiales"),
      description.indexOf("Dimensiones")
    );
  newDescription +=
    "<br>" +
    description.substring(
      description.indexOf("Dimensiones"),
      description.length
    );
  $(".description-content").text("");
  $(".description-content").append(newDescription);

  $("#product-images-nav-carousel .prev-button").click(function() {
    $("#product-images-nav-carousel").carousel("prev");
  });
  $("#product-images-nav-carousel .next-button").click(function() {
    $("#product-images-nav-carousel").carousel("next");
  });

  $(".change-product-evaluation.fa-star").mouseenter(function() {
    var starNumber = $(this).data("star");
    for (let i = 0; i < 5; i++) {
      if (i < starNumber) {
        $(".change-product-evaluation.fa-star[data-star='" + (i+1) + "']").addClass("favorite");
      } else {
        $(".change-product-evaluation.fa-star[data-star='" + (i+1) + "']").removeClass("favorite");
      }
    }
  });

  $(".change-product-evaluation.fa-star").mouseleave(function() {
    var productEvaluation = $("#change-product-evaluation").data("evaluation");
    for (let i = 0; i < 5; i++) {
      if (i < productEvaluation) {
        $(".change-product-evaluation.fa-star[data-star='" + (i+1) + "']").addClass("favorite");
      } else {
        $(".change-product-evaluation.fa-star[data-star='" + (i+1) + "']").removeClass("favorite");
      }
    }
  });

  $(".change-product-evaluation.fa-star").click(function() {
    var evaluation = $(this).data("star");
    ajax(
      $(this).data("evaluate-path"),
      "POST",
      {},
      function() {
        $("#change-product-evaluation").data("evaluation", evaluation);
        for (let i = 0; i < 5; i++) {
          if (i < evaluation) {
            $(".change-product-evaluation.fa-star[data-star='" + (i+1) + "']").addClass("favorite");
          } else {
            $(".change-product-evaluation.fa-star[data-star='" + (i+1) + "']").removeClass("favorite");
          }
        }
        $.toast({
          text: "Producto evaluado correctamente",
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
          text: "Ha ocurrido un error realizando la operación. Intente más tarde",
          showHideTransition: "fade",
          bgColor: "#c2b930",
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
