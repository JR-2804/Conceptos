var template_image_member =
  '<tr><td>%1</td><td>%2</td><td>%3</td><td><img src="%4" class="img-responsive"></td><td><a class="btn btn-secondary btn-remove-member" data-id="%5" data-index="%6"><i class="fa fa-remove"></i></a></td></tr>';

var headerImage = undefined;
var shortDescriptionImage = undefined;
var memberImage = undefined;
var memberImages = undefined;
var footerImage = undefined;

$(document).ready(function() {
  if (data.header) {
    headerImage = data.header.image;
  }
  if (data.shortDescription) {
    shortDescriptionImage = data.shortDescription.image;
  }
  if (data.members && data.members.members) {
    memberImages = data.members.members;
    populateMemberImages();
  } else {
    memberImages = [];
  }
  if (data.footer) {
    footerImage = data.footer.image;
  }

  dropzoneHeader = new Dropzone("form#picture-dropzone-about-us-header", {
    url: $("#picture-dropzone-about-us-header").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneHeader = this;
      if (headerImage != undefined) {
        var mockFile = {
          name: headerImage.name,
          size: headerImage.size
        };
        dropzoneHeader.emit("addedfile", mockFile);
        dropzoneHeader.emit("thumbnail", mockFile, headerImage.path);
        dropzoneHeader.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      headerImage = r;
    }
  });
  dropzoneShortDescription = new Dropzone(
    "form#picture-dropzone-short-description-image",
    {
      url: $("#picture-dropzone-short-description-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneShortDescription = this;
        if (shortDescriptionImage != undefined) {
          var mockFile = {
            name: shortDescriptionImage.name,
            size: shortDescriptionImage.size
          };
          dropzoneShortDescription.emit("addedfile", mockFile);
          dropzoneShortDescription.emit(
            "thumbnail",
            mockFile,
            shortDescriptionImage.path
          );
          dropzoneShortDescription.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        shortDescriptionImage = r;
      }
    }
  );
  dropzoneMember = new Dropzone("form#picture-dropzone-member-image", {
    url: $("#picture-dropzone-member-image").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneMember = this;
      dropzoneMember.on("removedfile", function() {
        if (memberImage != undefined) {
          var path = $(".remove-path-image").val() + "/" + memberImage.id;
          memberImage = undefined;
          dropzoneMember.options.maxFiles = 1;
        }
      });
    },
    success: function(e, r) {
      memberImage = r;
    }
  });
  dropzoneFooter = new Dropzone("form#picture-dropzone-footer-image", {
    url: $("#picture-dropzone-footer-image").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneFooter = this;
      if (footerImage != undefined) {
        var mockFile = {
          name: footerImage.name,
          size: footerImage.size
        };
        dropzoneFooter.emit("addedfile", mockFile);
        dropzoneFooter.emit("thumbnail", mockFile, footerImage.path);
        dropzoneFooter.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      footerImage = r;
    }
  });

  $(".btn-add-member").click(function() {
    if (memberImage) {
      memberImage.name = $("#member-name").val();
      memberImage.rol = $("#member-rol").val();
      memberImage.description = $("#member-description").val();
      memberImages.push(memberImage);
      memberImage = undefined;
      var dropzone = Dropzone.forElement("form#picture-dropzone-member-image");
      dropzone.removeAllFiles(true);
      $("#member-name").val("");
      $("#member-rol").val("");
      $("#member-description").val("");
      populateMemberImages();
    } else {
      alert("Inserte los datos correctamente");
    }
  });
});

function init() {
  populateMemberImages();
  if (data.shortDescription) {
    $("#short-description-title").val(data.shortDescription.title);
    $("#short-description-text").val(data.shortDescription.text);
  }
  if (data.members) {
    $("#members-title").val(data.members.title);
  }
  if (data.footer) {
    $("#footer-text").val(data.footer.text);
  }
}

function generateData() {
  return {
    header: {
      image: headerImage
    },
    shortDescription: {
      title: $("#short-description-title").val(),
      text: $("#short-description-text").val(),
      image: shortDescriptionImage
    },
    members: {
      title: $("#members-title").val(),
      members: memberImages
    },
    footer: {
      text: $("#footer-text").val(),
      image: footerImage
    }
  };
}

function validateSubmitData() {
  var valid = true;
  if (!headerImage) {
    valid = false;
  }
  if (!$("#short-description-title").val()) {
    valid = false;
  }
  if (!$("#short-description-text").val()) {
    valid = false;
  }
  if (!shortDescriptionImage) {
    valid = false;
  }
  if (!$("#members-title").val()) {
    valid = false;
  }
  if (!memberImages || memberImages.length === 0) {
    valid = false;
  }
  if (!$("#footer-text").val()) {
    valid = false;
  }
  if (!footerImage) {
    valid = false;
  }
  return valid;
}

function populateMemberImages() {
  $(".table-members tr").remove();
  if (memberImages) {
    memberImages.forEach(function(image, index) {
      var tmp_image = template_image_member
        .substring(-1)
        .replace("%1", image.name)
        .replace("%2", image.rol)
        .replace("%3", image.description)
        .replace("%4", image.path)
        .replace("%5", image.id);
      var index1 = index + 1;
      tmp_image = tmp_image.replace("%6", index1);
      $(".table-members").append(tmp_image);
    });
    $(".btn-remove-member").click(function() {
      var id = $(this).data("id");
      var tmpImages = [];
      memberImages.forEach(function(image) {
        if (image.id != id) {
          tmpImages.push(image);
        }
      });
      memberImages = tmpImages;
      populateMemberImages();
    });
  }
}
