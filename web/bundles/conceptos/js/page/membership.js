var headerImage = undefined;
var advertisementImage = undefined;
var headerSectionImage = undefined;
var giftCardImage = undefined;
var giftCardGeneralImage = undefined;
var giftCard15Image = undefined;
var giftCard25Image = undefined;
var giftCard50Image = undefined;
var giftCard100Image = undefined;
var bottomImage = undefined;

$(document).ready(function() {
  if (data.header) {
    headerImage = data.header.image;

    if (data.header.advertisementImage) {
      advertisementImage = data.header.advertisementImage;
    }
  }
  if (data.headerSection && data.headerSection.image) {
    headerSectionImage = data.headerSection.image;
  }
  if (data.giftCardSection) {
    giftCardImage = data.giftCardSection.image;
    if (data.giftCardSection.generalImage) {
      giftCardGeneralImage = data.giftCardSection.generalImage;
    }
    if (data.giftCardSection.giftCard15Image) {
      giftCard15Image = data.giftCardSection.giftCard15Image;
      giftCard25Image = data.giftCardSection.giftCard25Image;
      giftCard50Image = data.giftCardSection.giftCard50Image;
      giftCard100Image = data.giftCardSection.giftCard100Image;
    }
  }
  if (data.bottom) {
    bottomImage = data.bottom.image;
  }

  dropzoneServicesImageHeader = new Dropzone(
    "form#picture-dropzone-membership-header",
    {
      url: $("#picture-dropzone-membership-header").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneServicesImageHeader = this;
        if (headerImage != undefined) {
          var mockFile = {
            name: headerImage.name,
            size: headerImage.size
          };
          dropzoneServicesImageHeader.emit("addedfile", mockFile);
          dropzoneServicesImageHeader.emit(
            "thumbnail",
            mockFile,
            headerImage.path
          );
          dropzoneServicesImageHeader.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        headerImage = r;
      }
    }
  );
  dropzoneAdvertisementImage = new Dropzone(
    "form#picture-dropzone-membership-advertisement",
    {
      url: $("#picture-dropzone-membership-advertisement").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneAdvertisementImage = this;
        if (advertisementImage != undefined) {
          var mockFile = {
            name: advertisementImage.name,
            size: advertisementImage.size
          };
          dropzoneAdvertisementImage.emit("addedfile", mockFile);
          dropzoneAdvertisementImage.emit(
            "thumbnail",
            mockFile,
            advertisementImage.path
          );
          dropzoneAdvertisementImage.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        advertisementImage = r;
      }
    }
  );
  dropzoneServicesHeaderSectionImage = new Dropzone(
    "form#picture-dropzone-header-section-image",
    {
      url: $("#picture-dropzone-header-section-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneServicesHeaderSectionImage = this;
        if (headerSectionImage != undefined) {
          var mockFile = {
            name: headerSectionImage.name,
            size: headerSectionImage.size
          };
          dropzoneServicesHeaderSectionImage.emit("addedfile", mockFile);
          dropzoneServicesHeaderSectionImage.emit(
            "thumbnail",
            mockFile,
            headerSectionImage.path
          );
          dropzoneServicesHeaderSectionImage.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        headerSectionImage = r;
      }
    }
  );
  dropzoneGiftCardImage = new Dropzone(
    "form#picture-dropzone-gift-card-image",
    {
      url: $("#picture-dropzone-gift-card-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneGiftCardImage = this;
        if (giftCardImage != undefined) {
          var mockFile = {
            name: giftCardImage.name,
            size: giftCardImage.size
          };
          dropzoneGiftCardImage.emit("addedfile", mockFile);
          dropzoneGiftCardImage.emit("thumbnail", mockFile, giftCardImage.path);
          dropzoneGiftCardImage.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        giftCardImage = r;
      }
    }
  );
  dropzoneGiftCardGeneralImage = new Dropzone(
    "form#picture-dropzone-gift-card-general-image",
    {
      url: $("#picture-dropzone-gift-card-general-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneGiftCardGeneralImage = this;
        if (giftCardGeneralImage != undefined) {
          var mockFile = {
            name: giftCardGeneralImage.name,
            size: giftCardGeneralImage.size
          };
          dropzoneGiftCardGeneralImage.emit("addedfile", mockFile);
          dropzoneGiftCardGeneralImage.emit(
            "thumbnail",
            mockFile,
            giftCardGeneralImage.path
          );
          dropzoneGiftCardGeneralImage.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        giftCardGeneralImage = r;
      }
    }
  );
  dropzoneGiftCard15Image = new Dropzone("form#picture-dropzone-gift-card-15", {
    url: $("#picture-dropzone-gift-card-15").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGiftCard15Image = this;
      if (giftCard15Image != undefined) {
        var mockFile = {
          name: giftCard15Image.name,
          size: giftCard15Image.size
        };
        dropzoneGiftCard15Image.emit("addedfile", mockFile);
        dropzoneGiftCard15Image.emit(
          "thumbnail",
          mockFile,
          giftCard15Image.path
        );
        dropzoneGiftCard15Image.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      giftCard15Image = r;
    }
  });
  dropzoneGiftCard25Image = new Dropzone("form#picture-dropzone-gift-card-25", {
    url: $("#picture-dropzone-gift-card-25").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGiftCard25Image = this;
      if (giftCard25Image != undefined) {
        var mockFile = {
          name: giftCard25Image.name,
          size: giftCard25Image.size
        };
        dropzoneGiftCard25Image.emit("addedfile", mockFile);
        dropzoneGiftCard25Image.emit(
          "thumbnail",
          mockFile,
          giftCard25Image.path
        );
        dropzoneGiftCard25Image.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      giftCard25Image = r;
    }
  });
  dropzoneGiftCard50Image = new Dropzone("form#picture-dropzone-gift-card-50", {
    url: $("#picture-dropzone-gift-card-50").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGiftCard50Image = this;
      if (giftCard50Image != undefined) {
        var mockFile = {
          name: giftCard50Image.name,
          size: giftCard50Image.size
        };
        dropzoneGiftCard50Image.emit("addedfile", mockFile);
        dropzoneGiftCard50Image.emit(
          "thumbnail",
          mockFile,
          giftCard50Image.path
        );
        dropzoneGiftCard50Image.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      giftCard50Image = r;
    }
  });
  dropzoneGiftCard100Image = new Dropzone(
    "form#picture-dropzone-gift-card-100",
    {
      url: $("#picture-dropzone-gift-card-100").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneGiftCard100Image = this;
        if (giftCard100Image != undefined) {
          var mockFile = {
            name: giftCard100Image.name,
            size: giftCard100Image.size
          };
          dropzoneGiftCard100Image.emit("addedfile", mockFile);
          dropzoneGiftCard100Image.emit(
            "thumbnail",
            mockFile,
            giftCard100Image.path
          );
          dropzoneGiftCard100Image.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        giftCard100Image = r;
      }
    }
  );
  dropzoneBottomImage = new Dropzone("#picture-dropzone-membership-bottom", {
    url: $("#picture-dropzone-membership-bottom").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneBottomImage = this;
      if (bottomImage != undefined) {
        var mockFile = {
          name: bottomImage.name,
          size: bottomImage.size
        };
        dropzoneBottomImage.emit("addedfile", mockFile);
        dropzoneBottomImage.emit("thumbnail", mockFile, bottomImage.path);
        dropzoneBottomImage.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      bottomImage = r;
    }
  });
});

function init() {
  if (data.header && data.header.mainText) {
    $("#form-main-text").val(data.header.mainText);
    $("#form-bottom-text").val(data.header.bottomText);
  }
  if (data.headerSection) {
    $("#header-main-text").val(data.headerSection.mainText);
    $("#header-bottom-text-1").val(data.headerSection.bottomText1);
    $("#header-bottom-text-highlighted").val(
      data.headerSection.bottomTextHighlighted
    );
  }
  if (data.giftCardSection) {
    $("#card-main-text").val(data.giftCardSection.mainText);
    $("#card-bottom-text").val(data.giftCardSection.bottomText);
    if (data.giftCardSection.modalText) {
      $("#card-modal-text").val(data.giftCardSection.modalText);
    }
  }
}

function generateData() {
  var data = {
    header: {
      image: headerImage,
      advertisementImage: advertisementImage,
      mainText: $("#form-main-text").val(),
      bottomText: $("#form-bottom-text").val()
    },
    headerSection: {
      mainText: $("#header-main-text").val(),
      bottomText1: $("#header-bottom-text-1").val(),
      bottomTextHighlighted: $("#header-bottom-text-highlighted").val(),
      image: headerSectionImage
    },
    giftCardSection: {
      mainText: $("#card-main-text").val(),
      bottomText: $("#card-bottom-text").val(),
      modalText: $("#card-modal-text").val(),
      image: giftCardImage,
      generalImage: giftCardGeneralImage,
      giftCard15Image: giftCard15Image,
      giftCard25Image: giftCard25Image,
      giftCard50Image: giftCard50Image,
      giftCard100Image: giftCard100Image
    },
    bottom: {
      image: bottomImage
    }
  };
  return data;
}

function validateSubmitData() {
  var valid = true;
  if (!headerImage) {
    valid = false;
  }
  if (!advertisementImage) {
    valid = false;
  }
  if (!$("#form-main-text").val()) {
    valid = false;
  }
  if (!$("#form-bottom-text").val()) {
    valid = false;
  }
  if (!headerSectionImage) {
    valid = false;
  }
  if (!$("#header-main-text").val()) {
    valid = false;
  }
  if (!$("#header-bottom-text-1").val()) {
    valid = false;
  }
  if (!$("#header-bottom-text-highlighted").val()) {
    valid = false;
  }
  if (!$("#card-main-text").val()) {
    valid = false;
  }
  if (!$("#card-bottom-text").val()) {
    valid = false;
  }
  if (!$("#card-modal-text").val()) {
    valid = false;
  }
  if (!giftCardImage) {
    valid = false;
  }
  if (!giftCardGeneralImage) {
    valid = false;
  }
  if (!giftCard15Image) {
    valid = false;
  }
  if (!giftCard25Image) {
    valid = false;
  }
  if (!giftCard50Image) {
    valid = false;
  }
  if (!giftCard100Image) {
    valid = false;
  }
  if (!bottomImage) {
    valid = false;
  }
  return valid;
}
