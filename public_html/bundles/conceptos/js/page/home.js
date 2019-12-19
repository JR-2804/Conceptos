var template_row_empty =
  '<tr><td colspan="4" class="text-center">Sin pasos añadidos</td></tr>';
var template_row_social_empty =
  '<tr><td colspan="3" class="text-center">Sin redes sociales añadidas</td></tr>';
var template_row_social =
  '<tr><td>%1</td><td>%2</td><td><a class="btn btn-secondary btn-edit-social-network" data-index="%3"><i class="fa fa-edit"></i></a><a class="btn btn-secondary btn-remove-social-network" data-index="%4"><i class="fa fa-remove"></i></a></td></tr>';
var template_image_header =
  '<div class="col-3"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Slide #%6</h3></div><div class="panel-body"><img src="%1" class="img-responsive img-header center-block" height="150" width="150"><p class="video-link">%4</p><p class="video-link">%5</p><p class="video-link">%3</p></div><div class="panel-footer"><div class="center-block"><a class="btn btn-link btn-secondary pull-right btn-remove-header-image" data-id="%2">Eliminar</a></div></div></div></div>';
var template_advertisement =
  '<div class="col-3"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">Propaganda #%6</h3></div><div class="panel-body"><img src="%1" class="img-responsive img-header center-block" height="150" width="150"><p class="video-link">%3</p><p class="video-link">%4</p><p class="video-link">%5</p><p class="video-link"></p><a class="btn btn-link btn-secondary btn-remove-advertisement" data-id="%2">Eliminar</a><a class="btn btn-link btn-secondary btn-edit-advertisement" data-id="%7">Editar</a></div></div></div>';

var social_networks = [];
var headerImages = undefined;
var headerImage = undefined;
var topImage1 = undefined;
var topImage2 = undefined;
var servicesImage = undefined;
var appImage = undefined;
var contactImage = undefined;
var loginImage = undefined;
var loginImageShort = undefined;
var cataloge = undefined;
var advertisementImages = undefined;
var advertisementImage = undefined;
var successMailImage = undefined;
var logoImage = undefined;

var editingAdvertisement = undefined;

$(document).ready(function() {
  if (data.top) {
    topImage1 = data.top.image1;
    topImage2 = data.top.image2;
  }
  if (data.services) {
    servicesImage = data.services.image;
  }
  if (data.login) {
    loginImage = data.login.image;
    if (data.login.imageShort) {
      loginImageShort = data.login.imageShort;
    }
  }
  if (data.successMail) {
    successMailImage = data.successMail.image;
  }
  if (data.logo) {
    logoImage = data.logo.image;
  }

  headerImages = data.slides;
  appImage = data.app.image;
  contactImage = data.contact.image;
  cataloge = data.cataloge;
  if (data.advertisements) {
    advertisementImages = data.advertisements;
    populateAdvertisementsImages();
  } else {
    advertisementImages = [];
  }
  init();

  dropzone = new Dropzone("form#picture-dropzone-header", {
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
      dropzone = this;
      dropzone.on("removedfile", function() {
        if (headerImage != undefined) {
          var path = $(".remove-path-image").val() + "/" + headerImage.id;
          headerImage = undefined;
          dropzone.options.maxFiles = 1;
        }
      });
    },
    success: function(e, r) {
      headerImage = r;
    }
  });
  dropzoneTop1 = new Dropzone("form#picture-dropzone-top-1", {
    url: $("#picture-dropzone-top-1").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneTop1 = this;
      if (topImage1 != undefined) {
        var mockFile = { name: topImage1.name, size: topImage1.size };
        dropzoneTop1.emit("addedfile", mockFile);
        dropzoneTop1.emit("thumbnail", mockFile, topImage1.path);
        dropzoneTop1.emit("complete", mockFile);
      }
      dropzoneTop1.on("removedfile", function() {
        var path = $(".remove-path-image").val() + "/" + topImage1.id;
        topImage1 = undefined;
        dropzoneTop1.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      topImage1 = r;
    }
  });
  dropzoneTop2 = new Dropzone("form#picture-dropzone-top-2", {
    url: $("#picture-dropzone-top-2").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneTop2 = this;
      if (topImage2 != undefined) {
        var mockFile = { name: topImage2.name, size: topImage2.size };
        dropzoneTop2.emit("addedfile", mockFile);
        dropzoneTop2.emit("thumbnail", mockFile, topImage2.path);
        dropzoneTop2.emit("complete", mockFile);
      }
      dropzoneTop2.on("removedfile", function() {
        var path = $(".remove-path-image").val() + "/" + topImage2.id;
        topImage2 = undefined;
        dropzoneTop2.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      topImage2 = r;
    }
  });
  dropzoneServices = new Dropzone("form#services-dropzone", {
    url: $("#services-dropzone").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneServices = this;
      if (servicesImage != undefined) {
        var mockFile = { name: servicesImage.name, size: servicesImage.size };
        dropzoneServices.emit("addedfile", mockFile);
        dropzoneServices.emit("thumbnail", mockFile, servicesImage.path);
        dropzoneServices.emit("complete", mockFile);
      }
      dropzoneServices.on("removedfile", function() {
        servicesImage = undefined;
        dropzoneServices.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      servicesImage = r;
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
  dropzoneCataloge = new Dropzone("form#picture-dropzone-cataloge", {
    url: $("#picture-dropzone-cataloge").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".pdf",
    init: function() {
      dropzoneCataloge = this;
      if (cataloge != undefined) {
        var mockFile = { name: cataloge.name, size: cataloge.size };
        dropzoneCataloge.emit("addedfile", mockFile);
        dropzoneCataloge.emit("thumbnail", mockFile, cataloge.path);
        dropzoneCataloge.emit("complete", mockFile);
      }
      dropzoneCataloge.on("removedfile", function() {
        cataloge = undefined;
        dropzoneCataloge.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      cataloge = r;
    }
  });
  dropzoneContact = new Dropzone("form#picture-dropzone-contact", {
    url: $("#picture-dropzone-contact").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneContact = this;
      if (contactImage != undefined) {
        var mockFile = { name: contactImage.name, size: contactImage.size };
        dropzoneContact.emit("addedfile", mockFile);
        dropzoneContact.emit("thumbnail", mockFile, contactImage.path);
        dropzoneContact.emit("complete", mockFile);
      }
      dropzoneContact.on("removedfile", function() {
        var path = $(".remove-path-image").val() + "/" + contactImage.id;
        contactImage = undefined;
        dropzoneContact.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      contactImage = r;
    }
  });
  advertisementDropzone = new Dropzone(
    "form#picture-dropzone-advertisement-image",
    {
      url: $("#picture-dropzone-advertisement-image").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        advertisementDropzone = this;
        advertisementDropzone.on("removedfile", function() {
          if (advertisementImage != undefined) {
            var path =
              $(".remove-path-image").val() + "/" + advertisementImage.id;
            advertisementImage = undefined;
            advertisementDropzone.options.maxFiles = 1;
          }
        });
      },
      success: function(e, r) {
        advertisementImage = r;
      }
    }
  );
  dropzoneLogin = new Dropzone("form#picture-dropzone-login", {
    url: $("#picture-dropzone-login").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneLogin = this;
      if (loginImage != undefined) {
        var mockFile = { name: loginImage.name, size: loginImage.size };
        dropzoneLogin.emit("addedfile", mockFile);
        dropzoneLogin.emit("thumbnail", mockFile, loginImage.path);
        dropzoneLogin.emit("complete", mockFile);
      }
      dropzoneLogin.on("removedfile", function() {
        var path = $(".remove-path-image").val() + "/" + loginImage.id;
        loginImage = undefined;
        dropzoneLogin.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      loginImage = r;
    }
  });
  dropzoneLoginShort = new Dropzone("form#picture-dropzone-login-short", {
    url: $("#picture-dropzone-login-short").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneLoginShort = this;
      if (loginImageShort != undefined) {
        var mockFile = {
          name: loginImageShort.name,
          size: loginImageShort.size
        };
        dropzoneLoginShort.emit("addedfile", mockFile);
        dropzoneLoginShort.emit("thumbnail", mockFile, loginImageShort.path);
        dropzoneLoginShort.emit("complete", mockFile);
      }
      dropzoneLoginShort.on("removedfile", function() {
        loginImageShort = undefined;
        dropzoneLoginShort.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      loginImageShort = r;
    }
  });
  dropzoneSuccessMail = new Dropzone("form#picture-dropzone-success-mail", {
    url: $("#picture-dropzone-success-mail").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneSuccessMail = this;
      if (successMailImage != undefined) {
        var mockFile = {
          name: successMailImage.name,
          size: successMailImage.size
        };
        dropzoneSuccessMail.emit("addedfile", mockFile);
        dropzoneSuccessMail.emit("thumbnail", mockFile, successMailImage.path);
        dropzoneSuccessMail.emit("complete", mockFile);
      }
      dropzoneSuccessMail.on("removedfile", function() {
        var path = $(".remove-path-image").val() + "/" + successMailImage.id;
        successMailImage = undefined;
        dropzoneSuccessMail.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      successMailImage = r;
    }
  });
  dropzoneLogo = new Dropzone("form#picture-dropzone-logo", {
    url: $("#picture-dropzone-logo").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneLogo = this;
      if (logoImage != undefined) {
        var mockFile = {
          name: logoImage.name,
          size: logoImage.size
        };
        dropzoneLogo.emit("addedfile", mockFile);
        dropzoneLogo.emit("thumbnail", mockFile, logoImage.path);
        dropzoneLogo.emit("complete", mockFile);
      }
      dropzoneLogo.on("removedfile", function() {
        logoImage = undefined;
        dropzoneLogo.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      logoImage = r;
    }
  });

  $("#social-network-select").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true,
    maximumSelectionLength: 1
  });

  $(".btn-add-social-network").click(function() {
    if ($("#social-network-select").val() && $("#social-network-link").val()) {
      var edit = $(this).data("edit");
      if ($.isNumeric(edit)) {
        social_networks[edit].network = $("#social-network-select").val()[0];
        social_networks[edit].link = $("#social-network-link").val();
        $(this).data("edit", false);
        $(this).text("Añadir");
      } else {
        social_networks.push({
          network: $("#social-network-select").val()[0],
          link: $("#social-network-link").val()
        });
      }
      $("#social-network-select")
        .val([])
        .trigger("change");
      $("#social-network-link").val("");
      populateSocialNetworksTable();
    } else {
      alert("Debe llenar todos los campos de la red social");
    }
  });

  $("#btn-add-image-video").click(function() {
    if (headerImage != undefined) {
      if ($("#slide-link").val()) {
        headerImage.link = $("#slide-link").val();
      }
      headerImage.main = $("#header-main").val();
      headerImage.secondary = $("#header-secondary").val();
      headerImages.push(headerImage);
      headerImage = undefined;
      var dropzoneHeader = Dropzone.forElement("form#picture-dropzone-header");
      dropzoneHeader.removeAllFiles(true);
      $("#slide-link").val("");
      $("#header-main").val("");
      $("#header-secondary").val("");
      populateHeaderImages();
    } else {
      alert(
        "Debe seleccionar una imágen, introducir un texto principal y secundario para añadir un Slide porfavor"
      );
    }
  });

  $("#btn-add-advertisement").click(function() {
    if (
      advertisementImage != undefined &&
      $("#advertisement-name").val() &&
      $("#advertisement-section").val() &&
      $("#advertisement-link").val() &&
      $("#advertisement-priority").val()
    ) {
      advertisementImage.name = $("#advertisement-name").val();
      advertisementImage.section = $("#advertisement-section").val();
      advertisementImage.link = $("#advertisement-link").val();
      advertisementImage.priority = $("#advertisement-priority").val();
      advertisementImages.push(advertisementImage);
      advertisementImage = undefined;
      var dropzoneAdvertisement = Dropzone.forElement(
        "form#picture-dropzone-advertisement-image"
      );
      dropzoneAdvertisement.removeAllFiles(true);
      $("#advertisement-name").val("");
      $("#advertisement-section").val(1);
      $("#advertisement-link").val("");
      $("#advertisement-priority").val("");
      populateAdvertisementsImages();
    } else {
      alert("Debe insertar los datos correctamente");
    }
  });

  $("#btn-edit-advertisement").click(function() {
    if (
      advertisementImage != undefined &&
      $("#advertisement-name").val() &&
      $("#advertisement-section").val() &&
      $("#advertisement-link").val() &&
      $("#advertisement-priority").val()
    ) {
      advertisementImage.name = $("#advertisement-name").val();
      advertisementImage.section = $("#advertisement-section").val();
      advertisementImage.link = $("#advertisement-link").val();
      advertisementImage.priority = $("#advertisement-priority").val();

      var tmpAdvertisementImages = [];
      advertisementImages.forEach(function(a) {
        if (a.id != editingAdvertisement.id) {
          tmpAdvertisementImages.push(a);
        }
      });
      advertisementImages = tmpAdvertisementImages;

      advertisementImages.push(advertisementImage);
      advertisementImage = undefined;

      populateAdvertisementsImages();

      $("#advertisement-name").val("");
      $("#advertisement-section").val("");
      $("#advertisement-link").val("");
      $("#advertisement-priority").val("");

      $("#picture-dropzone-advertisement-image .dz-image-preview").remove();

      $("#btn-add-advertisement").show();
      $("#btn-edit-advertisement").hide();
      $("#btn-cancel-edit-advertisement").hide();
    } else {
      alert("Inserte los datos correctamente");
    }
  });

  $("#btn-cancel-edit-advertisement").click(function() {
    $("#advertisement-name").val("");
    $("#advertisement-section").val("");
    $("#advertisement-link").val("");
    $("#advertisement-priority").val("");

    $("#picture-dropzone-advertisement-image .dz-image-preview").remove();

    $("#btn-add-advertisement").show();
    $("#btn-edit-advertisement").hide();
    $("#btn-cancel-edit-advertisement").hide();
  });
});

function init() {
  populateHeaderImages();
  populateAdvertisementsImages();
  if (data.top.image1Link) {
    $("#top-image-1-link").val(data.top.image1Link);
    $("#top-image-2-link").val(data.top.image2Link);
  }
  if (data.subtitles) {
    $("#in-store-subtitle").val(data.subtitles.inStore);
    $("#recent-subtitle").val(data.subtitles.recent);
  }
  if (data.populars) {
    $("#populars-title").val(data.populars.title);
    $("#populars-subtitle").val(data.populars.subtitle);
  }
  if (data.cubanBrands) {
    $("#cuban-brands-title").val(data.cubanBrands.title);
    $("#cuban-brands-subtitle").val(data.cubanBrands.subtitle);
  }
  if (data.shopCart) {
    $("#shop-cart-main-text").val(data.shopCart.mainText);
    $("#shop-cart-secondary-text").val(data.shopCart.secondaryText);
  }
  if (data.services) {
    $("#services-title").val(data.services.title);
    $("#services-subtitle").val(data.services.subtitle);
  }

  $("#app-app").val(data.app.app);
  $("#app-db").val(data.app.db);

  $("#social-network").val(data.social_network.text);
  social_networks = data.social_network.networks;
  populateSocialNetworksTable();
  $("#blog-main").val(data.blog.main);
  $("#blog-secondary").val(data.blog.secondary);
  $("#contact-main").val(data.contact.main);
  $("#contact-secondary").val(data.contact.secondary);
}

function generateData() {
  var data = {
    slides: headerImages,
    top: {
      image1: topImage1,
      image2: topImage2,
      image1Link: $("#top-image-1-link").val(),
      image2Link: $("#top-image-2-link").val()
    },
    services: {
      image: servicesImage,
      title: $("#services-title").val(),
      subtitle: $("#services-subtitle").val()
    },
    subtitles: {
      inStore: $("#in-store-subtitle").val(),
      recent: $("#recent-subtitle").val()
    },
    populars: {
      title: $("#populars-title").val(),
      subtitle: $("#populars-subtitle").val()
    },
    cubanBrands: {
      title: $("#cuban-brands-title").val(),
      subtitle: $("#cuban-brands-subtitle").val()
    },
    cataloge: cataloge,
    app: {
      app: $("#app-app").val(),
      db: $("#app-db").val(),
      image: appImage
    },
    social_network: {
      text: $("#social-network").val(),
      networks: social_networks
    },
    blog: {
      main: $("#blog-main").val(),
      secondary: $("#blog-secondary").val()
    },
    contact: {
      main: $("#contact-main").val(),
      secondary: $("#contact-secondary").val(),
      image: contactImage
    },
    shopCart: {
      mainText: $("#shop-cart-main-text").val(),
      secondaryText: $("#shop-cart-secondary-text").val()
    },
    advertisements: advertisementImages,
    login: {
      image: loginImage,
      imageShort: loginImageShort
    },
    successMail: {
      image: successMailImage
    },
    logo: {
      image: logoImage
    }
  };
  return data;
}

function validateSubmitData() {
  var valid = true;
  if (
    !topImage1 ||
    !$("#top-image-1-link").val() ||
    !topImage2 ||
    !$("#top-image-2-link").val()
  ) {
    valid = false;
  }
  if (!servicesImage) {
    valid = false;
  }
  if (!$("#in-store-subtitle").val() || !$("#recent-subtitle").val()) {
    valid = false;
  }
  if (!$("#populars-title").val()) {
    valid = false;
  }
  if (!$("#cuban-brands-title").val() || !$("#cuban-brands-subtitle").val()) {
    valid = false;
  }
  if (!$("#app-app").val() || !$("#app-db").val()) {
    valid = false;
  }
  if (!$("#social-network").val()) {
    valid = false;
  }
  if (!$("#blog-main").val() || !$("#blog-secondary").val()) {
    valid = false;
  }
  if (!$("#contact-main").val() || !$("#contact-secondary").val()) {
    valid = false;
  }
  if (!loginImage) {
    valid = false;
  }
  if (!loginImageShort) {
    valid = false;
  }
  if (!successMailImage) {
    valid = false;
  }
  if (!logoImage) {
    valid = false;
  }
  return valid;
}

function populateHeaderImages() {
  $(".row-header-images .col-3").remove();
  if (headerImages) {
    headerImages.forEach(function(image, index) {
      var tmp_image = template_image_header
        .substring(-1)
        .replace("%1", image.path)
        .replace("%2", image.id);
      if (image.link) {
        var link = image.link;
      } else {
        link = "";
      }
      var index1 = index + 1;
      tmp_image = tmp_image.substring(-1).replace("%3", link);
      if (image.main) {
        tmp_image = tmp_image.replace("%4", image.main);
      } else {
        tmp_image = tmp_image.replace("%4", "");
      }
      if (image.secondary) {
        tmp_image = tmp_image.replace("%5", image.secondary);
      } else {
        tmp_image = tmp_image.replace("%5", "");
      }
      tmp_image = tmp_image.replace("%6", index1);
      $(".row-header-images").append(tmp_image);
    });
    $(".btn-remove-header-image").click(function() {
      var id = $(this).data("id");
      var tmpImages = [];
      headerImages.forEach(function(image) {
        if (image.id != id) {
          tmpImages.push(image);
        }
      });
      headerImages = tmpImages;
      populateHeaderImages();
    });
  }
}

function populateSocialNetworksTable() {
  $(".table-social-networks tbody tr").remove();
  if (social_networks.length == 0) {
    var tmp_empty = template_row_social_empty.substring(-1);
    $(".table-social-networks tbody").append(tmp_empty);
  } else {
    $.each(social_networks, function(i, s) {
      var tmp = template_row_social
        .substring(-1)
        .replace("%1", s.network)
        .replace("%2", s.link)
        .replace("%3", i)
        .replace("%4", i);
      $(".table-social-networks tbody").append(tmp);
    });
    $(".btn-edit-social-network").click(function() {
      var index = $(this).data("index");
      var socialNetwork = social_networks[index];
      $("#social-network-select")
        .val([socialNetwork.network])
        .trigger("change");
      $("#social-network-link").val(socialNetwork.link);
      var indexSocial = parseInt(index) + 1;
      $(".btn-add-social-network").text("Editar #" + indexSocial);
      $(".btn-add-social-network").data("edit", index);
    });
    $(".btn-remove-social-network").click(function() {
      var index = $(this).data("index");
      social_networks.splice(index, 1);
      populateSocialNetworksTable();
    });
  }
}

function populateAdvertisementsImages() {
  $(".row-advertisement .col-3").remove();
  if (advertisementImages) {
    advertisementImages.forEach(function(image, index) {
      var tmp_image = template_advertisement
        .substring(-1)
        .replace("%1", image.path)
        .replace("%2", image.id)
        .replace("%7", image.id)
        .replace("%3", image.name)
        .replace("%4", image.priority)
        .replace(
          "%5",
          $("#advertisement-section").children("option")[image.section - 1].text
        )
        .replace("%6", index + 1);

      $(".row-advertisement").append(tmp_image);
    });
    $(".btn-remove-advertisement").click(function() {
      var id = $(this).data("id");
      var tmpImages = [];
      advertisementImages.forEach(function(image) {
        if (image.id != id) {
          tmpImages.push(image);
        }
      });
      advertisementImages = tmpImages;
      populateAdvertisementsImages();
    });
    $(".btn-edit-advertisement").click(function() {
      $("#btn-add-advertisement").hide();
      $("#btn-edit-advertisement").show();
      $("#btn-cancel-edit-advertisement").show();
      var id = $(this).data("id");
      var advertisement;
      advertisementImages.forEach(function(image) {
        if (image.id === id) {
          advertisement = image;
        }
      });
      advertisementImage = advertisement;
      editingAdvertisement = advertisement;
      $("#advertisement-name").val(advertisement.name);
      $("#advertisement-section").val(advertisement.section);
      $("#advertisement-link").val(advertisement.link);
      $("#advertisement-priority").val(advertisement.priority);

      $("#picture-dropzone-advertisement-image .dz-image-preview").remove();
      var mockFile = { name: advertisement.name, size: advertisement.size };
      advertisementDropzone.emit("addedfile", mockFile);
      advertisementDropzone.emit("thumbnail", mockFile, advertisement.path);
      advertisementDropzone.emit("complete", mockFile);
    });
  }
}
