Dropzone.autoDiscover = false;
$(document).ready(function() {
  $("#hours").val($("#config_hours").val());
  $("#phone").val($("#config_phone").val());
  $("#email").val($("#config_email").val());
  $("#total-weight").val($("#config_totalWeight").val());
  $("#tax-tax").val($("#config_taxTax").val());
  $("#benefit").val($("#config_benefit").val());
  $("#ticket-price").val($("#config_ticketPrice").val());
  $("#tax-furniture").val($("#config_taxFurniture").val());
  var data = {
    image: {
      id: 0,
      name: "",
      path: "",
      size: ""
    },
    app: {
      id: 0,
      name: "",
      path: "",
      size: ""
    }
  };
  if ($("#config_image").val()) {
    data.image = JSON.parse($("#config_image").val());
  }
  if ($("#config_app").val()) {
    data.app = JSON.parse($("#config_app").val());
  }
  dropzone = new Dropzone("form#picture-dropzone", {
    url: $("#picture-dropzone").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: "image/*",
    init: function() {
      dropzone = this;
      if (data.image.id != 0) {
        var mockFile = { name: data.image.name, size: data.image.size };
        dropzone.emit("addedfile", mockFile);
        dropzone.emit("thumbnail", mockFile, data.image.path);
        dropzone.emit("complete", mockFile);
        dropzone.files.push(mockFile);
        var existingFileCount = 1;
        dropzone.options.maxFiles =
          dropzone.options.maxFiles - existingFileCount;
      }
      dropzone.on("removedfile", function() {
        dropzone.removeAllFiles(true);
        dropzone.options.maxFiles = 1;
        data.image = {
          id: 0
        };
      });
    },
    success: function(e, r) {
      data.image = r;
    }
  });

  $("#terms").ckeditor();
  $("#privacyPolicy").ckeditor();
  $("#terms").val($("#config_terms").val());
  $("#privacyPolicy").val($("#config_privacyPolicy").val());

  $('form[name="config"]').submit(function(e) {
    if (!$("#hours").val() || !$("#phone").val() || data.image == 0) {
      e.preventDefault();
      alert("Debe llenar todos los campos");
    } else {
      $("#config_hours").val($("#hours").val());
      $("#config_phone").val($("#phone").val());
      $("#config_email").val($("#email").val());
      $("#config_image").val(data.image.id);
      $("#config_terms").val($("#terms").val());
      $("#config_privacyPolicy").val($("#privacyPolicy").val());
      $("#config_totalWeight").val($("#total-weight").val());
      $("#config_taxTax").val($("#tax-tax").val());
      $("#config_benefit").val($("#benefit").val());
      $("#config_ticketPrice").val($("#ticket-price").val());
      $("#config_taxFurniture").val($("#tax-furniture").val());
      $("#config_recalculatePrice").val(
        $("#recalculate-prices").prop("checked")
      );
    }
  });
});
