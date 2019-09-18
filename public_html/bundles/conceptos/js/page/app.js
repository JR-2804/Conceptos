var template_row_empty =
  '<tr><td colspan="4" class="text-center">Sin pasos añadidos</td></tr>';
var template_row_step =
  '<tr><td><strong>%1</strong></td><td>%2</td><td>%3</td><td><a class="btn btn-secondary btn-edit-step" data-index="%4"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-step" data-index="%5"><i class="fa fa-remove"></i></a></td></tr>';

var headerImage = undefined;
var appImage = undefined;

$(document).ready(function() {
  if (data.header) {
    headerImage = data.header.image;
  }
  appImage = data.app.image;

  dropzoneHeader = new Dropzone("form#picture-dropzone-header", {
    url: $("#picture-dropzone-header").attr("action"),
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
  dropzoneApp = new Dropzone("form#picture-dropzone-app", {
    url: $("#picture-dropzone-app").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneApp = this;
      if (appImage != undefined) {
        var mockFile = { name: appImage.name, size: appImage.size };
        dropzoneApp.emit("addedfile", mockFile);
        dropzoneApp.emit("thumbnail", mockFile, appImage.path);
        dropzoneApp.emit("complete", mockFile);
      }
      dropzoneApp.on("removedfile", function() {
        var path = $(".remove-path-image").val() + "/" + appImage.id;
        appImage = undefined;
        dropzoneApp.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      appImage = r;
    }
  });

  $(".btn-add-step-app").click(function() {
    if ($("#step-title").val() && $("#step-content").val()) {
      var index = $(this).data("edit");
      if ($.isNumeric(index)) {
        steps[index].title = $("#step-title").val();
        steps[index].content = $("#step-content").val();
        $(this).data("edit", false);
        $(this).text("Añadir");
      } else {
        steps.push({
          title: $("#step-title").val(),
          content: $("#step-content").val()
        });
      }
      populateStepsApp();
      $("#step-title").val("");
      $("#step-content").val("");
    } else {
      alert("Debe completar todos los campos");
    }
  });
});

function init() {
  steps = data.steps.steps;
  populateStepsApp();
  $("#step-main").val(data.steps.main);
  $("#step-secondary").val(data.steps.secondary);
  $("#app-app").val(data.app.app);
  $("#app-db").val(data.app.db);
}

function generateData() {
  var data = {
    header: {
      image: headerImage,
      main: $("#header-main").val(),
      secondary: $("#header-secondary").val()
    },
    steps: {
      main: $("#step-main").val(),
      secondary: $("#step-secondary").val(),
      steps: steps
    },
    app: {
      app: $("#app-app").val(),
      db: $("#app-db").val(),
      image: appImage
    }
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

function populateStepsApp() {
  $(".table-steps tbody tr").remove();
  if (steps.length == 0) {
    var tmp_empty = template_row_empty.substring(-1);
    $(".table-steps tbody").append(tmp_empty);
  } else {
    $.each(steps, function(i, s) {
      var tmp_row = template_row_step
        .substring(-1)
        .replace("%1", i + 1)
        .replace("%2", s.title)
        .replace("%3", s.content)
        .replace("%4", i)
        .replace("%5", i);
      $(".table-steps tbody").append(tmp_row);
    });

    $(".btn-edit-step").click(function() {
      var index = $(this).data("index");
      var step = steps[index];
      $("#step-title").val(step.title);
      $("#step-content").val(step.content);
      var indexBtn = parseInt(index) + 1;
      $(".btn-add-step-app").text("Editar paso #" + indexBtn);
      $(".btn-add-step-app").data("edit", index);
    });
    $(".btn-remove-step").click(function() {
      var index = $(this).data("index");
      steps.splice(index, 1);
      populateStepsApp();
    });
  }
}
