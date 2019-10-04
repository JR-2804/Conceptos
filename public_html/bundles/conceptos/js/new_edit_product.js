var data = {
  image: {
    id: 0
  },
  images: [],
  imagesToDelete: [],
  highlightImages: []
};

Dropzone.autoDiscover = false;
var dropzone = undefined;
var dropzoneImages = undefined;
$(document).ready(function() {
  dropzone = new Dropzone("form#picture-dropzone", {
    url: $("#picture-dropzone").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
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

  dropzoneImages = new Dropzone("form#pictures-dropzone", {
    url: $("#pictures-dropzone").attr("action"),
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: "image/*",
    init: function() {
      dropzoneImages = this;
      if (data.images.length > 0) {
        $.each(data.images, function(i, r) {
          var mockFile = { name: r.name, size: r.size };
          dropzoneImages.emit("addedfile", mockFile);
          dropzoneImages.emit("thumbnail", mockFile, r.path);
          dropzoneImages.emit("complete", mockFile);
        });
      }
      dropzoneImages.on("removedfile", function(file) {
        var imagesTmp = [];
        $.each(data.images, function(i, f) {
          if (file.name != f.name) {
            imagesTmp.push(f);
          } else {
            data.imagesToDelete.push(f);
          }
        });
        data.images = imagesTmp;
      });
    },
    success: function(e, r) {
      data.images.push(r);
    }
  });

  dropzoneImages = new Dropzone("form#picture-highlight-dropzone", {
    url: $("#picture-highlight-dropzone").attr("action"),
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: "image/*",
    init: function() {
      dropzoneImages = this;
      if (data.highlightImages.length > 0) {
        $.each(data.highlightImages, function(i, r) {
          var mockFile = { name: r.name, size: r.size };
          dropzoneImages.emit("addedfile", mockFile);
          dropzoneImages.emit("thumbnail", mockFile, r.path);
          dropzoneImages.emit("complete", mockFile);
        });
      }
      dropzoneImages.on("removedfile", function(file) {
        var imagesTmp = [];
        $.each(data.highlightImages, function(i, f) {
          if (file.name != f.name) {
            imagesTmp.push(f);
          } else {
            data.imagesToDelete.push(f);
          }
        });
        data.highlightImages = imagesTmp;
      });
    },
    success: function(e, r) {
      data.highlightImages.push(r);
    }
  });

  $("#category").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true
  });

  $("#color, #material").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true,
    tags: true,
    maximumSelectionLength: 1
  });

  $("#category-favorite").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true
  });

  $("#in-store").change(function() {
    if ($(this).prop("checked")) {
      $(".store-count-content").show();
    } else {
      $(".store-count-content").hide();
    }
  });

  $(".btn-calculate-price").click(function() {
    var weight = $("#weight").val();
    var ikeaPrice = $("#ikea-price").val();
    if (weight && ikeaPrice) {
      ajax(
        $(this).data("path"),
        "POST",
        {
          weight: weight,
          ikeaPrice: ikeaPrice,
          isFurniture: $("#is-furniture").prop("checked"),
          isFragile: $("#is-fragile").prop("checked"),
          isAriplaneForniture: $("#is-airplane-furniture").prop("checked"),
          isOversize: $("#is-oversize").prop("checked"),
          isTableware: $("#is-tableware").prop("checked"),
          isLamp: $("#is-lamp").prop("checked"),
          numberOfPackages: $("#number-of-packages").val(),
        },
        function(response) {
          var valueResponse = Number(response).toFixed(2);
          $("#calculate-price").val(valueResponse);
          $("#price").val(valueResponse);
        },
        function(error) {
          alert("Ha ocurrido un error calculando el precio del producto");
        }
      );
    } else {
      alert("Debe llenar los campos de Peso, Límite por envío y Precio IKEA");
    }
  });

  $('form[name="product"]').submit(function(e) {
    if (!validForm()) {
      e.preventDefault();
    } else {
      $("#product_name").val($("#name").val());
      $("#product_code").val($("#code").val());
      $("#product_item").val($("#item").val());
      $("#product_isFurniture").val($("#is-furniture").prop("checked"));
      $("#product_description").val($("#description").val());
      $("#product_category").val(JSON.stringify($("#category").val()));
      $("#product_image").val(data.image.id);
      $("#product_price").val($("#price").val());
      $("#product_images").val(JSON.stringify(data.images));
      $("#product_imagesToDelete").val(JSON.stringify(data.imagesToDelete));
      $("#product_popular").val($("#popular").prop("checked") == true ? 1 : 0);
      $("#product_recent").val($("#recent").prop("checked") == true ? 1 : 0);
      $("#product_inStore").val($("#in-store").prop("checked") == true ? 1 : 0);
      $("#product_countStore").val($("#store-count").val());
      $("#product_color").val($("#color").val());
      $("#product_material").val($("#material").val());
      $("#product_favoritesCategories").val(
        JSON.stringify($("#category-favorite").val())
      );
      $("#product_weight").val($("#weight").val());
      $("#product_shippingLimit").val($("#shipping-limit").val());
      $("#product_ikeaPrice").val($("#ikea-price").val());
      $("#product_calculatePrice").val($("#calculate-price").val());
      $("#product_isHighlight").val(
        $("#is-highlight").prop("checked") == true ? 1 : 0
      );
      $("#product_highlightImages").val(JSON.stringify(data.highlightImages));
      $("#product_isAriplaneForniture").val(
        $("#is-airplane-furniture").prop("checked") == true ? 1 : 0
      );
      $("#product_isFragile").val(
        $("#is-fragile").prop("checked") == true ? 1 : 0
      );
      $("#product_isOversize").val(
        $("#is-oversize").prop("checked") == true ? 1 : 0
      );
      $("#product_isTableware").val(
        $("#is-tableware").prop("checked") == true ? 1 : 0
      );
      $("#product_isLamp").val($("#is-lamp").prop("checked") == true ? 1 : 0);
      $("#product_numberOfPackages").val($("#number-of-packages").val());
    }
  });
});

function validForm() {
  var valid = true;
  var name = $("#name");
  if (!$(name).val()) {
    addRemoveErrorClass(name, true);
    valid = false;
  } else {
    addRemoveErrorClass(name, false);
  }
  var code = $("#code");
  if (!$(code).val()) {
    addRemoveErrorClass(code, true);
    valid = false;
  } else {
    addRemoveErrorClass(code, false);
  }
  var description = $("#description");
  if (!$(description).val()) {
    addRemoveErrorClass(description, true);
    valid = false;
  } else {
    addRemoveErrorClass(description, false);
  }
  var category = $("#category");
  if ($(category).val() == null || $(category).val().length == 0) {
    addRemoveErrorClass(category, true);
    valid = false;
  } else {
    addRemoveErrorClass(category, false);
  }
  if (data.image.id == 0) {
    valid = false;
    if (!$("#picture-dropzone").hasClass("dropzone-error")) {
      $("#picture-dropzone").addClass("dropzone-error");
    }
  } else {
    if ($("#picture-dropzone").hasClass("dropzone-error")) {
      $("#picture-dropzone").removeClass("dropzone-error");
    }
  }
  if (data.images.length == 0) {
    valid = false;
    if (!$("#pictures-dropzone").hasClass("dropzone-error")) {
      $("#pictures-dropzone").addClass("dropzone-error");
    }
  } else {
    if ($("#pictures-dropzone").hasClass("dropzone-error")) {
      $("#pictures-dropzone").removeClass("dropzone-error");
    }
  }
  return valid;
}

function addRemoveErrorClass(input, add) {
  if (add) {
    if (
      !$(input)
        .parent()
        .hasClass("has-error")
    ) {
      $(input)
        .parent()
        .addClass("has-error");
    }
  } else {
    $(input)
      .parent()
      .removeClass("has-error");
  }
}
