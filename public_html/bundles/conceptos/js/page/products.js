var genericImage = undefined;
var inOfferImage = undefined;
var inStoreImage = undefined;
var newImage = undefined;
var generalImage = undefined;
$(document).ready(function() {
  if (data.genericImage) {
    genericImage = data.genericImage;
  }
  if (data.inOfferImage) {
    inOfferImage = data.inOfferImage;
  }
  if (data.inStoreImage) {
    inStoreImage = data.inStoreImage;
  }
  if (data.newImage) {
    newImage = data.newImage;
  }
  if (data.generalImage) {
    generalImage = data.generalImage;
  }

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

  dropzoneInOfferImage = new Dropzone("form#picture-dropzone-in-offer", {
    url: $("#picture-dropzone-in-offer").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneInOfferImage = this;
      if (inOfferImage != undefined) {
        var mockFile = { name: inOfferImage.name, size: inOfferImage.size };
        dropzoneInOfferImage.emit("addedfile", mockFile);
        dropzoneInOfferImage.emit("thumbnail", mockFile, inOfferImage.path);
        dropzoneInOfferImage.emit("complete", mockFile);
      }
      dropzoneInOfferImage.on("removedfile", function() {
        inStoreIinOfferImagemage = undefined;
        dropzoneInOfferImage.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      inOfferImage = r;
    }
  });

  dropzoneInStoreImage = new Dropzone("form#picture-dropzone-in-store", {
    url: $("#picture-dropzone-in-store").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneInStoreImage = this;
      if (inStoreImage != undefined) {
        var mockFile = { name: inStoreImage.name, size: inStoreImage.size };
        dropzoneInStoreImage.emit("addedfile", mockFile);
        dropzoneInStoreImage.emit("thumbnail", mockFile, inStoreImage.path);
        dropzoneInStoreImage.emit("complete", mockFile);
      }
      dropzoneInStoreImage.on("removedfile", function() {
        inStoreImage = undefined;
        dropzoneInStoreImage.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      inStoreImage = r;
    }
  });

  dropzoneNewImage = new Dropzone("form#picture-dropzone-new", {
    url: $("#picture-dropzone-new").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneNewImage = this;
      if (newImage != undefined) {
        var mockFile = { name: newImage.name, size: newImage.size };
        dropzoneNewImage.emit("addedfile", mockFile);
        dropzoneNewImage.emit("thumbnail", mockFile, newImage.path);
        dropzoneNewImage.emit("complete", mockFile);
      }
      dropzoneNewImage.on("removedfile", function() {
        newImage = undefined;
        dropzoneNewImage.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      newImage = r;
    }
  });

  dropzoneGeneralImage = new Dropzone("form#picture-dropzone-general", {
    url: $("#picture-dropzone-general").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGeneralImage = this;
      if (generalImage != undefined) {
        var mockFile = { name: generalImage.name, size: generalImage.size };
        dropzoneGeneralImage.emit("addedfile", mockFile);
        dropzoneGeneralImage.emit("thumbnail", mockFile, generalImage.path);
        dropzoneGeneralImage.emit("complete", mockFile);
      }
      dropzoneGeneralImage.on("removedfile", function() {
        generalImage = undefined;
        dropzoneGeneralImage.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      generalImage = r;
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
    header: {
      mainText: $("#header-main-text").val(),
      bottomText1: $("#header-bottom-text-1").val(),
      bottomTextHighlighted: $("#header-bottom-text-highlighted").val(),
      bottomText2: $("#header-bottom-text-2").val()
    },
    genericImage: genericImage,
    inOfferImage: inOfferImage,
    inStoreImage: inStoreImage,
    newImage: newImage,
    generalImage: generalImage,
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
