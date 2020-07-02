$(document).ready(function () {
  var generalOpinion = "";
  var priceQualityEvaluation = $("#change-product-evaluation-modal").data(
    "price-quality-evaluation"
  );
  var utilityEvaluation = $("#change-product-evaluation-modal").data(
    "utility-evaluation"
  );
  var durabilityEvaluation = $("#change-product-evaluation-modal").data(
    "durability-evaluation"
  );
  var qualityEvaluation = $("#change-product-evaluation-modal").data(
    "quality-evaluation"
  );
  var designEvaluation = $("#change-product-evaluation-modal").data(
    "design-evaluation"
  );
  var generalEvaluation = $("#change-product-evaluation-modal").data(
    "general-evaluation"
  );
  var opinion = "";
  var recommended = false;

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

  $("#product-images-nav-carousel .prev-button").click(function () {
    $("#product-images-nav-carousel").carousel("prev");
  });
  $("#product-images-nav-carousel .next-button").click(function () {
    $("#product-images-nav-carousel").carousel("next");
  });

  $(".change-product-evaluation-short.price-quality.fa-star").click(
    function () {
      var starNumber = $(this).data("star");
      priceQualityEvaluation = starNumber;
      for (let i = 0; i < 5; i++) {
        if (i < starNumber) {
          $(
            ".change-product-evaluation-short.price-quality.fa-star[data-star='" +
              (i + 1) +
              "']"
          ).addClass("favorite");
        } else {
          $(
            ".change-product-evaluation-short.price-quality.fa-star[data-star='" +
              (i + 1) +
              "']"
          ).removeClass("favorite");
        }
      }
      CalculateGeneralEvaluation();
    }
  );

  $(".change-product-evaluation-short.utility.fa-star").click(function () {
    var starNumber = $(this).data("star");
    utilityEvaluation = starNumber;
    for (let i = 0; i < 5; i++) {
      if (i < starNumber) {
        $(
          ".change-product-evaluation-short.utility.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).addClass("favorite");
      } else {
        $(
          ".change-product-evaluation-short.utility.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).removeClass("favorite");
      }
    }
    CalculateGeneralEvaluation();
  });

  $(".change-product-evaluation-short.durability.fa-star").click(function () {
    var starNumber = $(this).data("star");
    durabilityEvaluation = starNumber;
    for (let i = 0; i < 5; i++) {
      if (i < starNumber) {
        $(
          ".change-product-evaluation-short.durability.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).addClass("favorite");
      } else {
        $(
          ".change-product-evaluation-short.durability.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).removeClass("favorite");
      }
    }
    CalculateGeneralEvaluation();
  });

  $(".change-product-evaluation-short.quality.fa-star").click(function () {
    var starNumber = $(this).data("star");
    qualityEvaluation = starNumber;
    for (let i = 0; i < 5; i++) {
      if (i < starNumber) {
        $(
          ".change-product-evaluation-short.quality.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).addClass("favorite");
      } else {
        $(
          ".change-product-evaluation-short.quality.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).removeClass("favorite");
      }
    }
    CalculateGeneralEvaluation();
  });

  $(".change-product-evaluation-short.design.fa-star").click(function () {
    var starNumber = $(this).data("star");
    designEvaluation = starNumber;
    for (let i = 0; i < 5; i++) {
      if (i < starNumber) {
        $(
          ".change-product-evaluation-short.design.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).addClass("favorite");
      } else {
        $(
          ".change-product-evaluation-short.design.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).removeClass("favorite");
      }
    }
    CalculateGeneralEvaluation();
  });

  function CalculateGeneralEvaluation() {
    generalEvaluation =
      (priceQualityEvaluation +
        utilityEvaluation +
        durabilityEvaluation +
        qualityEvaluation +
        designEvaluation) /
      5;

    for (let i = 0; i < 5; i++) {
      if (i < Math.round(generalEvaluation)) {
        $(
          ".change-product-evaluation.general.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).addClass("favorite");
      } else {
        $(
          ".change-product-evaluation.general.fa-star[data-star='" +
            (i + 1) +
            "']"
        ).removeClass("favorite");
      }
    }

    $("#general-evaluation").text(generalEvaluation);

    if (generalEvaluation < 1) {
      generalOpinion = "Muy mala";
    } else if (generalEvaluation < 2) {
      generalOpinion = "Mala";
    } else if (generalEvaluation < 3) {
      generalOpinion = "Regular";
    } else if (generalEvaluation < 4) {
      generalOpinion = "Buena";
    } else {
      generalOpinion = "Muy buena";
    }
    $("#general-opinion").text(generalOpinion);
  }

  function CreateEvaluationActions() {
    $("#view-evaluation-button").click(function () {
      $("#view-product-evaluations").modal("hide");
      $("#view-product-evaluations").on("hidden.bs.modal", function () {
        $("#change-product-evaluation-modal").modal("show");
      });
    });

    $("#send-evaluation-button").click(function () {
      opinion = $(".evaluation-textarea").val();
      recommended = $("#recommended-radio").prop("checked");

      var url = $(this).data("path");
      ajax(
        url,
        "POST",
        {
          generalOpinion: generalOpinion,
          priceQualityEvaluation: priceQualityEvaluation,
          utilityEvaluation: utilityEvaluation,
          durabilityEvaluation: durabilityEvaluation,
          qualityEvaluation: qualityEvaluation,
          designEvaluation: designEvaluation,
          generalEvaluation: generalEvaluation,
          opinion: opinion,
          recommended: recommended,
        },
        function (response) {
          $("#product-evaluation-section").children().remove();
          $("#product-evaluation-section").append(response.html);
          CreateEvaluationActions();

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
            heading: "Correcto",
          });
        },
        function () {
          $.toast({
            text: "Ha ocurrido un error evaluando el producto",
            showHideTransition: "fade",
            bgColor: "#c2b930",
            textColor: "#3f3c03",
            allowToastClose: true,
            hideAfter: 3000,
            stack: 5,
            textAlign: "center",
            position: "mid-center",
            icon: "error",
            heading: "Error",
          });
        }
      );
    });
  }

  CreateEvaluationActions();
});
