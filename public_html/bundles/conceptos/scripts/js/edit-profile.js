var data = {
  image: {
    id: undefined,
  },
};

$(document).ready(function () {
  if ($("#fos_user_profile_form_jsonImage").val()) {
    data.image = JSON.parse($("#fos_user_profile_form_jsonImage").val());
  }

  dropzone = new Dropzone("#dropzone-profile-image", {
    url: $("#dropzone-profile-image").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function () {
      dropzone = this;
      if (data.image.id) {
        var mockFile = { name: data.image.name, size: data.image.size };
        dropzone.emit("addedfile", mockFile);
        dropzone.emit("thumbnail", mockFile, data.image.path);
        dropzone.emit("complete", mockFile);
        dropzone.files.push(mockFile);
        var existingFileCount = 1;
        dropzone.options.maxFiles =
          dropzone.options.maxFiles - existingFileCount;
      }
      dropzone.on("removedfile", function () {
        dropzone.removeAllFiles(true);
        dropzone.options.maxFiles = 1;
        data.image = {
          id: 0,
        };
      });
    },
    success: function (e, r) {
      data.image = r;
    },
  });

  $(".state-select").click(function (e) {
    e.preventDefault();
  });

  $(".state-select").change(function () {
    var state = $(this).val();
    var path = $(this).data("path");
    ajax(
      path,
      "POST",
      {
        state: state,
      },
      function () {},
      function () {
        alert(
          "Ha ocurrido un error actualizando el estado de la orden de compra"
        );
      }
    );
  });

  $("#edit-profile-button").click(function () {
    if ($("#edit-profile-section").data("hidden") === true) {
      $("#edit-profile-section").show();
      $("#edit-profile-section").data("hidden", false);
      $("#edit-profile label").hide();
    } else {
      $("#edit-profile-section").hide();
      $("#edit-profile-section").data("hidden", true);
    }
  });

  $(".update-profile-button").click(function (e) {
    $('form[name="fos_user_profile_form"]').submit();
    $('form[name="fos_user_registration_form"]').submit();
  });

  $('form[name="fos_user_profile_form"]').submit(function () {
    $("#fos_user_profile_form_image").val(data.image.id);
  });

  $('form[name="fos_user_registration_form"]').submit(function () {
    $("#fos_user_registration_form_image").val(data.image.id);
  });

  updateCounters();

  function updateCounters() {
    $(".external-request").each(function () {
      var id = $(this).data("id");
      var days = $(this).data("remaining-days");
      var hours = $(this).data("remaining-hours");
      var minutes = $(this).data("remaining-minutes");
      var seconds = $(this).data("remaining-seconds");

      var countdownDate = new Date(
        0,
        0,
        0,
        days * 24 + hours,
        minutes,
        seconds
      );

      setInterval(function () {
        var distance = new Date(0, 0, 0, 72) - countdownDate;

        if (distance < 0) {
          $(".external-request[data-id='" + id + "']").show();
          $(".external-request[data-id='" + id + "'] .remaining-time").text(
            "Tiempo de entrega expirado"
          );
        }

        var remainingDays = Math.floor(distance / 1000 / 60 / 60 / 24);
        distance -= remainingDays * 1000 * 60 * 60 * 24;
        var remainingHours = Math.floor(distance / 1000 / 60 / 60);
        distance -= remainingHours * 1000 * 60 * 60;
        var remainingMinutes = Math.floor(distance / 1000 / 60);
        distance -= remainingMinutes * 1000 * 60;
        var remainingSeconds = Math.floor(distance / 1000);

        if (remainingDays > 0) {
          remainingHours += remainingDays * 24;
        }
        if (remainingDays < 10) {
          remainingDays = "0" + remainingDays;
        }
        if (remainingHours < 10) {
          remainingHours = "0" + remainingHours;
        }
        if (remainingMinutes < 10) {
          remainingMinutes = "0" + remainingMinutes;
        }
        if (remainingSeconds < 10) {
          remainingSeconds = "0" + remainingSeconds;
        }

        $(".external-request[data-id='" + id + "']").show();
        $(".external-request[data-id='" + id + "'] .remaining-time").text(
          "Este pedido expira en: " +
            remainingHours +
            ":" +
            remainingMinutes +
            ":" +
            remainingSeconds
        );
        countdownDate.setTime(countdownDate.getTime() + 1000);
      }, 1000);
    });
  }
});
