var data = {
  image: {
    id: undefined
  }
};

$(document).ready(function() {
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
    init: function() {
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

  $("#edit-profile-button").click(function() {
    $("#edit-profile-section").show();
  });

  $(".update-profile-button").click(function(e) {
    $('form[name="fos_user_profile_form"]').submit();
    $('form[name="fos_user_registration_form"]').submit();
  });
  $('form[name="fos_user_profile_form"]').submit(function() {
    $("#fos_user_profile_form_image").val(data.image.id);
  });
  $('form[name="fos_user_registration_form"]').submit(function() {
    $("#fos_user_registration_form_image").val(data.image.id);
  });
});
