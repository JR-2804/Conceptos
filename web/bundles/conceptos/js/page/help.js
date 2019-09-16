var template_faq =
  '<div class="col-12"><div class="panel panel-default" style="margin: 20px;"><p style="margin-left: 10px;">%1</p><p style="margin-left: 10px;">%2</p><a class="btn btn-link btn-secondary btn-remove-faq" data-id="%3">Eliminar</a></div></div>';

var headerImage = undefined;
var faq = {};
var faqs = [];

$(document).ready(function() {
  if (data.header) {
    headerImage = data.header.image;
  }
  if (data.faqs) {
    faqs = data.faqs;
    populateFaqs();
  }

  dropzoneServicesImageHeader = new Dropzone(
    "form#picture-dropzone-help-header",
    {
      url: $("#picture-dropzone-help-header").attr("action"),
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

  $("#btn-new-faq").click(function() {
    if ($("#faq-question").val() && $("#faq-answer").val()) {
      faq.question = $("#faq-question").val();
      faq.answer = $("#faq-answer").val();
      faqs.push(faq);
      faq = {};
      $("#faq-question").val("");
      $("#faq-answer").val("");
      populateFaqs();
    } else {
      alert("Inserte los datos correctamente");
    }
  });
});

function init() {}

function generateData() {
  var data = {
    header: {
      image: headerImage
    },
    faqs: faqs
  };
  return data;
}

function validateSubmitData() {
  var valid = true;
  if (!headerImage) {
    valid = false;
  }
  return valid;
}

function populateFaqs() {
  $(".row-faq .col-12").remove();
  if (faqs) {
    faqs.forEach(function(faq) {
      var tmp_faq = template_faq
        .substring(-1)
        .replace("%1", faq.question)
        .replace("%2", faq.answer)
        .replace("%3", faq.question);
      $(".row-faq").append(tmp_faq);
    });
    $(".btn-remove-faq").click(function() {
      var id = $(this).data("id");
      var tmpFaqs = [];
      faqs.forEach(function(faq) {
        if (faq.question != id) {
          tmpFaqs.push(faq);
        }
      });
      faqs = tmpFaqs;
      populateFaqs();
    });
  }
}
