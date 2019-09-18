var productsImage = undefined;
var genericImage = undefined;
$(document).ready(function() {
  productsImage = data.products;
  if (data.genericImage) {
    genericImage = data.genericImage;
  }

  dropzoneProducts = new Dropzone("form#picture-dropzone-products", {
    url: $("#picture-dropzone-products").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneProducts = this;
      if (productsImage != undefined) {
        var mockFile = { name: productsImage.name, size: productsImage.size };
        dropzoneProducts.emit("addedfile", mockFile);
        dropzoneProducts.emit("thumbnail", mockFile, productsImage.path);
        dropzoneProducts.emit("complete", mockFile);
      }
      dropzoneProducts.on("removedfile", function() {
        productsImage = undefined;
        dropzoneProducts.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      productsImage = r;
    }
  });
  dropzoneGenericImage = new Dropzone("form#picture-dropzone-generic-image", {
    url: $("#picture-dropzone-generic-image").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGenericImage = this;
      if (genericImage != undefined) {
        var mockFile = { name: genericImage.name, size: genericImage.size };
        dropzoneGenericImage.emit("addedfile", mockFile);
        dropzoneGenericImage.emit("thumbnail", mockFile, genericImage.path);
        dropzoneGenericImage.emit("complete", mockFile);
      }
      dropzoneGenericImage.on("removedfile", function() {
        genericImage = undefined;
        dropzoneGenericImage.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      genericImage = r;
    }
  });
});

function init() {
  if (data.header) {
    $("#header-main-text").val(data.header.mainText);
    $("#header-bottom-text-1").val(data.header.bottomText1);
    $("#header-bottom-text-highlighted").val(data.header.bottomTextHighlighted);
    $("#header-bottom-text-2").val(data.header.bottomText2);
  }
}

function generateData() {
  var data = {
    products: productsImage,
    header: {
      mainText: $("#header-main-text").val(),
      bottomText1: $("#header-bottom-text-1").val(),
      bottomTextHighlighted: $("#header-bottom-text-highlighted").val(),
      bottomText2: $("#header-bottom-text-2").val()
    },
    genericImage: genericImage
  };
  return data;
}

function validateSubmitData() {
  var valid = true;
  if (!$("#header-main-text").val()) {
    valid = false;
  }
  if (!$("#header-bottom-text-1").val()) {
    valid = false;
  }
  if (!$("#header-bottom-text-highlighted").val()) {
    valid = false;
  }
  if (!$("#header-bottom-text-2").val()) {
    valid = false;
  }
  if (!genericImage) {
    valid = false;
  }
  return valid;
}
