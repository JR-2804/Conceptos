var template_image_project =
  '<div class="col-3" style="padding-left: 0px;"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Proyecto #%6</h3></div><div class="panel-body"><img src="%1" class="img-responsive img-header center-block" height="150" width="150"><p class="video-link">%3</p><p class="video-link">%4</p><p class="video-link">%5</p><a class="btn btn-link btn-secondary btn-remove-project" data-id="%2">Eliminar</a><a class="btn btn-link btn-secondary btn-edit-project" data-id="%7">Editar</a></div></div></div>';

var template_service =
  '<div class="col-3" style="padding-left: 0px;"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Servicio #%6</h3></div><div class="panel-body"><p class="video-link">%3</p><p class="video-link">%4</p><p class="video-link">%5</p><a class="btn btn-link btn-secondary btn-remove-service" data-id="%2">Eliminar</a><a class="btn btn-link btn-secondary btn-edit-service" data-id="%7">Editar</a></div></div></div>';

var headerImage = undefined;
var arquitectureImage = undefined;
var inspirationImage = undefined;
var designersTeamImage = undefined;
var projectBackgroundImage = undefined;
var projectImages = undefined;
var projectImage = undefined;
var extraProjectImages = [];
var services = undefined;
var service = undefined;
var servicesImages = [];

var editingService = undefined;
var editingProject = undefined;

function initializeDropzoneServicesImages() {
  dropzoneServicesImages = new Dropzone(
    "form#picture-dropzone-service-images",
    {
      url: $("#picture-dropzone-service-images").attr("action"),
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: "image/*",
      init: function() {
        dropzoneServicesImages = this;
        if (servicesImages.length > 0) {
          $.each(servicesImages, function(i, r) {
            var mockFile = { name: r.name, size: r.size };
            dropzoneServicesImages.emit("addedfile", mockFile);
            dropzoneServicesImages.emit("thumbnail", mockFile, r.path);
            dropzoneServicesImages.emit("complete", mockFile);
          });
        }
        dropzoneServicesImages.on("removedfile", function(file) {
          var imagesTmp = [];
          $.each(servicesImages, function(i, f) {
            if (file.name != f.name) {
              imagesTmp.push(f);
            }
          });
          servicesImages = imagesTmp;
        });
      },
      success: function(e, r) {
        servicesImages.push(r);
      }
    }
  );
}

function initializeDropzoneProjectImages() {
  dropzoneProjectImages = new Dropzone(
    "form#picture-dropzone-new-project-image",
    {
      url: $("#picture-dropzone-new-project-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneProjectImages = this;
        dropzoneProjectImages.on("removedfile", function() {
          if (projectImage != undefined) {
            var path = $(".remove-path-image").val() + "/" + projectImage.id;
            projectImage = undefined;
            dropzoneProjectImages.options.maxFiles = 1;
          }
        });
      },
      success: function(e, r) {
        projectImage = r;
      }
    }
  );
}

function initializeDropzoneProjectExtraImages() {
  dropzoneProjectExtraImages = new Dropzone(
    "form#picture-dropzone-extra-images",
    {
      url: $("#picture-dropzone-extra-images").attr("action"),
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: "image/*",
      init: function() {
        dropzoneProjectExtraImages = this;
        if (extraProjectImages.length > 0) {
          $.each(extraProjectImages, function(i, r) {
            var mockFile = { name: r.name, size: r.size };
            dropzoneProjectExtraImages.emit("addedfile", mockFile);
            dropzoneProjectExtraImages.emit("thumbnail", mockFile, r.path);
            dropzoneProjectExtraImages.emit("complete", mockFile);
          });
        }
        dropzoneProjectExtraImages.on("removedfile", function(file) {
          var imagesTmp = [];
          $.each(extraProjectImages, function(i, f) {
            if (file.name != f.name) {
              imagesTmp.push(f);
            }
          });
          extraProjectImages = imagesTmp;
        });
      },
      success: function(e, r) {
        extraProjectImages.push(r);
      }
    }
  );
}

$(document).ready(function() {
  if (data.services.header) {
    headerImage = data.services.header.image;
  }
  if (data.services.inspiration) {
    inspirationImage = data.services.inspiration.image;
  }
  if (data.services.designersTeam) {
    designersTeamImage = data.services.designersTeam.image;
    if (data.services.designersTeam.services) {
      services = data.services.designersTeam.services;
      populateServices();
    } else {
      services = [];
    }
  } else {
    services = [];
  }
  if (data.services.projects) {
    projectBackgroundImage = data.services.projects.image;
  }
  resetAsociatedProducts();
  if (data.services.projects && data.services.projects.projects) {
    projectImages = data.services.projects.projects;
    populateProjectImages();
  } else {
    projectImages = [];
  }
  arquitectureImage = data.services.arg.image;

  dropzoneServicesImageHeader = new Dropzone(
    "form#picture-dropzone-services-header",
    {
      url: $("#picture-dropzone-services-header").attr("action"),
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
  dropzoneServicesInspirationImage = new Dropzone(
    "form#picture-dropzone-inspiration-image",
    {
      url: $("#picture-dropzone-inspiration-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneServicesInspirationImage = this;
        if (inspirationImage != undefined) {
          var mockFile = {
            name: inspirationImage.name,
            size: inspirationImage.size
          };
          dropzoneServicesInspirationImage.emit("addedfile", mockFile);
          dropzoneServicesInspirationImage.emit(
            "thumbnail",
            mockFile,
            inspirationImage.path
          );
          dropzoneServicesInspirationImage.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        inspirationImage = r;
      }
    }
  );
  dropzoneServicesDesignersTeam = new Dropzone(
    "form#picture-dropzone-designers-team",
    {
      url: $("#picture-dropzone-designers-team").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneServicesDesignersTeam = this;
        if (designersTeamImage != undefined) {
          var mockFile = {
            name: designersTeamImage.name,
            size: designersTeamImage.size
          };
          dropzoneServicesDesignersTeam.emit("addedfile", mockFile);
          dropzoneServicesDesignersTeam.emit(
            "thumbnail",
            mockFile,
            designersTeamImage.path
          );
          dropzoneServicesDesignersTeam.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        designersTeamImage = r;
      }
    }
  );
  dropzoneServicesProjectsBackground = new Dropzone(
    "form#picture-dropzone-projects-image",
    {
      url: $("#picture-dropzone-projects-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneServicesProjectsBackground = this;
        if (projectBackgroundImage != undefined) {
          var mockFile = {
            name: projectBackgroundImage.name,
            size: projectBackgroundImage.size
          };
          dropzoneServicesProjectsBackground.emit("addedfile", mockFile);
          dropzoneServicesProjectsBackground.emit(
            "thumbnail",
            mockFile,
            projectBackgroundImage.path
          );
          dropzoneServicesProjectsBackground.emit("complete", mockFile);
        }
      },
      success: function(e, r) {
        projectBackgroundImage = r;
      }
    }
  );

  dropzoneServicesImageArq = new Dropzone("form#picture-dropzone-arq", {
    url: $("#picture-dropzone-arq").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneServicesImageArq = this;
      if (arquitectureImage != undefined) {
        var mockFile = {
          name: arquitectureImage.name,
          size: arquitectureImage.size
        };
        dropzoneServicesImageArq.emit("addedfile", mockFile);
        dropzoneServicesImageArq.emit(
          "thumbnail",
          mockFile,
          arquitectureImage.path
        );
        dropzoneServicesImageArq.emit("complete", mockFile);
      }
    },
    success: function(e, r) {
      arquitectureImage = r;
    }
  });

  initializeDropzoneServicesImages();
  initializeDropzoneProjectImages();
  initializeDropzoneProjectExtraImages();

  $("#btn-new-project").click(function() {
    if (
      projectImage &&
      $("#new-project-title").val() &&
      $("#new-project-description").val() &&
      extraProjectImages.length > 0 &&
      $("#products").val()
    ) {
      projectImage.title = $("#new-project-title").val();
      projectImage.description = $("#new-project-description").val();
      projectImage.extraImages = extraProjectImages;
      projectImage.products = $("#products").val();
      projectImages.push(projectImage);
      projectImage = undefined;
      $("#new-project-title").val("");
      $("#new-project-description").val("");
      Dropzone.forElement(
        "form#picture-dropzone-new-project-image"
      ).removeAllFiles(true);
      Dropzone.forElement("form#picture-dropzone-extra-images").removeAllFiles(
        true
      );
      extraProjectImages = [];
      resetAsociatedProducts();
      populateProjectImages();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#btn-edit-project").click(function() {
    if (
      projectImage &&
      $("#new-project-title").val() &&
      $("#new-project-description").val() &&
      extraProjectImages.length > 0 &&
      $("#products").val()
    ) {
      projectImage.title = $("#new-project-title").val();
      projectImage.description = $("#new-project-description").val();
      projectImage.extraImages = extraProjectImages;
      projectImage.products = $("#products").val();

      var tmpProjectImages = [];
      projectImages.forEach(function(p) {
        if (p.title != editingProject.title) {
          tmpProjectImages.push(p);
        }
      });
      projectImages = tmpProjectImages;

      projectImages.push(projectImage);
      projectImage = undefined;
      $("#new-project-title").val("");
      $("#new-project-description").val("");
      Dropzone.forElement(
        "form#picture-dropzone-new-project-image"
      ).removeAllFiles(true);
      $("#picture-dropzone-new-project-image .dz-image-preview").remove();
      Dropzone.forElement("form#picture-dropzone-extra-images").removeAllFiles(
        true
      );
      $("#picture-dropzone-extra-images .dz-image-preview").remove();
      extraProjectImages = [];
      resetAsociatedProducts();
      populateProjectImages();

      $("#btn-new-project").show();
      $("#btn-edit-project").hide();
      $("#btn-cancel-edit-project").hide();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#btn-cancel-edit-project").click(function() {
    $("#new-project-title").val("");
    $("#new-project-description").val("");
    Dropzone.forElement(
      "form#picture-dropzone-new-project-image"
    ).removeAllFiles(true);
    $("#picture-dropzone-new-project-image .dz-image-preview").remove();
    Dropzone.forElement("form#picture-dropzone-extra-images").removeAllFiles(
      true
    );
    $("#picture-dropzone-extra-images .dz-image-preview").remove();
    extraProjectImages = [];
    resetAsociatedProducts();
    populateProjectImages();

    $("#btn-new-project").show();
    $("#btn-edit-project").hide();
    $("#btn-cancel-edit-project").hide();
  });

  $("#btn-new-service").click(function() {
    if (
      $("#new-service-title").val() &&
      $("#new-service-description").val() &&
      servicesImages.length > 0 &&
      $("#products-service").val()
    ) {
      service = {};
      service.title = $("#new-service-title").val();
      service.description = $("#new-service-description").val();
      service.images = servicesImages;
      service.products = $("#products-service").val();
      services.push(service);
      service = undefined;
      $("#new-service-title").val("");
      $("#new-service-description").val("");
      Dropzone.forElement(
        "form#picture-dropzone-service-images"
      ).removeAllFiles(true);
      $("#picture-dropzone-service-images .dz-image-preview").remove();
      servicesImages = [];
      resetAsociatedProducts();
      populateServices();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#btn-edit-service").click(function() {
    if (
      $("#new-service-title").val() &&
      $("#new-service-description").val() &&
      servicesImages.length > 0 &&
      $("#products-service").val()
    ) {
      service = {};
      service.title = $("#new-service-title").val();
      service.description = $("#new-service-description").val();
      service.images = servicesImages;
      service.products = $("#products-service").val();

      var tmpServices = [];
      services.forEach(function(s) {
        if (s.title != editingService.title) {
          tmpServices.push(s);
        }
      });
      services = tmpServices;

      services.push(service);
      service = undefined;
      $("#new-service-title").val("");
      $("#new-service-description").val("");
      Dropzone.forElement(
        "form#picture-dropzone-service-images"
      ).removeAllFiles(true);
      $("#picture-dropzone-service-images .dz-image-preview").remove();
      servicesImages = [];
      resetAsociatedProducts();
      populateServices();

      $("#btn-new-service").show();
      $("#btn-edit-service").hide();
      $("#btn-cancel-edit-service").hide();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#btn-cancel-edit-service").click(function() {
    $("#new-service-title").val("");
    $("#new-service-description").val("");
    Dropzone.forElement("form#picture-dropzone-service-images").removeAllFiles(
      true
    );
    $("#picture-dropzone-service-images .dz-image-preview").remove();
    servicesImages = [];
    resetAsociatedProducts();
    populateServices();

    $("#btn-new-service").show();
    $("#btn-edit-service").hide();
    $("#btn-cancel-edit-service").hide();
  });
});

function init() {
  populateProjectImages();
  populateServices();
  $("#arg-title").val(data.services.arg.title);
  $("#arg-description").val(data.services.arg.description);
  if (data.services.inspiration) {
    $("#inspiration-text").val(data.services.inspiration.text);
  }
  if (data.services.designersTeam) {
    $("#designers-team-title").val(data.services.designersTeam.title);
    $("#designers-team-description").val(
      data.services.designersTeam.description
    );
  }
  if (data.services.projects) {
    $("#projects-title").val(data.services.projects.title);
    $("#projects-subtitle").val(data.services.projects.subtitle);
  }
  if (data.services.testimony) {
    $("#testimony-title").val(data.services.testimony.title);
    $("#testimony-description").val(data.services.testimony.description);
    $("#testimony-author").val(data.services.testimony.author);
  }
}

function generateData() {
  var data = {
    services: {
      header: {
        image: headerImage
      },
      inspiration: {
        image: inspirationImage,
        text: $("#inspiration-text").val()
      },
      designersTeam: {
        image: designersTeamImage,
        title: $("#designers-team-title").val(),
        description: $("#designers-team-description").val(),
        services: services
      },
      projects: {
        title: $("#projects-title").val(),
        subtitle: $("#projects-subtitle").val(),
        image: projectBackgroundImage,
        projects: projectImages
      },
      testimony: {
        title: $("#testimony-title").val(),
        description: $("#testimony-description").val(),
        author: $("#testimony-author").val()
      },
      arg: {
        image: arquitectureImage,
        title: $("#arg-title").val(),
        description: $("#arg-description").val()
      }
    }
  };
  return data;
}

function validateSubmitData() {
  var valid = true;
  if (!headerImage) {
    valid = false;
  }
  if (!inspirationImage) {
    valid = false;
  }
  if (!$("#inspiration-text").val()) {
    valid = false;
  }
  if (!designersTeamImage) {
    valid = false;
  }
  if (!$("#designers-team-title").val()) {
    valid = false;
  }
  if (!$("#designers-team-description").val()) {
    valid = false;
  }
  if (!$("#projects-title").val()) {
    valid = false;
  }
  if (!$("#projects-subtitle").val()) {
    valid = false;
  }
  if (!projectBackgroundImage) {
    valid = false;
  }
  if (!projectImages || projectImages.length === 0) {
    valid = false;
  }
  if (!$("#testimony-title").val()) {
    valid = false;
  }
  if (!$("#testimony-description").val()) {
    valid = false;
  }
  if (!$("#testimony-author").val()) {
    valid = false;
  }
  if (!arquitectureImage) {
    valid = false;
  }
  if (!$("#arg-title").val()) {
    valid = false;
  }
  if (!$("#arg-description").val()) {
    valid = false;
  }
  if (!services || services.length === 0) {
    valid = false;
  }
  return valid;
}

function resetAsociatedProducts() {
  $("#products").val([]);
  $("#products").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true
  });
  $("#products-service").val([]);
  $("#products-service").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true
  });
}

function populateProjectImages() {
  $(".row-project-images .col-3").remove();
  if (projectImages) {
    projectImages.forEach(function(image, index) {
      var tmp_image = template_image_project
        .substring(-1)
        .replace("%1", image.path)
        .replace("%2", image.id)
        .replace("%7", image.id)
        .replace("%3", image.title)
        .replace("%4", image.extraImages.length + " imágenes extra")
        .replace("%5", image.products.length + " productos asociados")
        .replace("%6", index + 1);
      $(".row-project-images").append(tmp_image);
    });
    $(".btn-remove-project").click(function() {
      var id = $(this).data("id");
      var tmpImages = [];
      projectImages.forEach(function(image) {
        if (image.id != id) {
          tmpImages.push(image);
        }
      });
      projectImages = tmpImages;
      populateProjectImages();
    });
    $(".btn-edit-project").click(function() {
      $("#btn-new-project").hide();
      $("#btn-edit-project").show();
      $("#btn-cancel-edit-project").show();
      var id = $(this).data("id");
      var project;
      projectImages.forEach(function(p) {
        if (p.id === id) {
          project = p;
        }
      });
      projectImage = project;
      editingProject = project;
      $("#new-project-title").val(project.title);
      $("#new-project-description").val(project.description);
      $("#products")
        .val(project.products)
        .trigger("change");

      $("#picture-dropzone-new-project-image .dz-image-preview").remove();
      var mockFile = { name: project.name, size: project.size };
      dropzoneProjectImages.emit("addedfile", mockFile);
      dropzoneProjectImages.emit("thumbnail", mockFile, project.path);
      dropzoneProjectImages.emit("complete", mockFile);

      extraProjectImages = project.extraImages;
      $("#picture-dropzone-extra-images .dz-image-preview").remove();
      dropzoneProjectExtraImages.destroy();
      initializeDropzoneProjectExtraImages();
    });
  }
}

function populateServices() {
  $(".row-service-images .col-3").remove();
  if (services) {
    services.forEach(function(service, index) {
      var tmp_service = template_service
        .substring(-1)
        .replace("%2", service.title)
        .replace("%7", service.title)
        .replace("%3", service.title)
        .replace("%4", service.images.length + " imágenes")
        .replace("%5", service.products.length + " productos asociados")
        .replace("%6", index + 1);
      $(".row-service-images").append(tmp_service);
    });
    $(".btn-remove-service").click(function() {
      var id = $(this).data("id");
      var tmpImages = [];
      services.forEach(function(service) {
        if (service.title != id) {
          tmpImages.push(service);
        }
      });
      services = tmpImages;
      populateServices();
    });
    $(".btn-edit-service").click(function() {
      $("#btn-new-service").hide();
      $("#btn-edit-service").show();
      $("#btn-cancel-edit-service").show();
      var id = $(this).data("id");
      var service;
      services.forEach(function(s) {
        if (s.title === id) {
          service = s;
        }
      });
      editingService = service;
      $("#new-service-title").val(service.title);
      $("#new-service-description").val(service.description);
      $("#products-service")
        .val(service.products)
        .trigger("change");
      servicesImages = service.images;
      $("#picture-dropzone-service-images .dz-image-preview").remove();
      dropzoneServicesImages.destroy();
      initializeDropzoneServicesImages();
    });
  }
}
