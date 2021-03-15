var template_combo_product_empty =
  '<tr><td colspan="3" class="text-center">Sin combos añadidos</td></tr>';
var template_combo_product =
  '<tr><td>%1</td><td>%2</td><td><a class="btn btn-secondary btn-remove-combo-product" data-index="%3"><i class="fa fa-remove"></i></a></td></tr>';
var template_metaname_empty =
  '<tr><td colspan="3" class="text-center">Sin metanames añadidos</td></tr>';
var template_metaname =
  '<tr><td>%1</td><td><a class="btn btn-secondary btn-remove-metaname" data-index="%2"><i class="fa fa-remove"></i></a></td></tr>';

var data = {
  image: {
    id: 0,
  },
  images: [],
  imagesToDelete: [],
  highlightImages: [],
};

Dropzone.autoDiscover = false;
var comboProducts = [];
var metaNames = [];
var currentComboProductCode = undefined;
var currentComboProductPrice = undefined;
var dropzone = undefined;
var dropzoneImages = undefined;
$(document).ready(function () {
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
    init: function () {
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
      dropzone.on("removedfile", function () {
        dropzone.removeAllFiles(true);
        dropzone.options.maxFiles = 1;
        data.image = {
          id: 0,
        };
      });
    },
    success: function (e, r) {
      data.image = r;
    },
  });

  dropzoneImages = new Dropzone("form#pictures-dropzone", {
    url: $("#pictures-dropzone").attr("action"),
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: "image/*",
    init: function () {
      dropzoneImages = this;
      if (data.images.length > 0) {
        $.each(data.images, function (i, r) {
          var mockFile = { name: r.name, size: r.size };
          dropzoneImages.emit("addedfile", mockFile);
          dropzoneImages.emit("thumbnail", mockFile, r.path);
          dropzoneImages.emit("complete", mockFile);
        });
      }
      dropzoneImages.on("removedfile", function (file) {
        var imagesTmp = [];
        $.each(data.images, function (i, f) {
          if (file.name != f.name) {
            imagesTmp.push(f);
          } else {
            data.imagesToDelete.push(f);
          }
        });
        data.images = imagesTmp;
      });
    },
    success: function (e, r) {
      data.images.push(r);
    },
  });

  dropzoneImages = new Dropzone("form#picture-highlight-dropzone", {
    url: $("#picture-highlight-dropzone").attr("action"),
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: "image/*",
    init: function () {
      dropzoneImages = this;
      if (data.highlightImages.length > 0) {
        $.each(data.highlightImages, function (i, r) {
          var mockFile = { name: r.name, size: r.size };
          dropzoneImages.emit("addedfile", mockFile);
          dropzoneImages.emit("thumbnail", mockFile, r.path);
          dropzoneImages.emit("complete", mockFile);
        });
      }
      dropzoneImages.on("removedfile", function (file) {
        var imagesTmp = [];
        $.each(data.highlightImages, function (i, f) {
          if (file.name != f.name) {
            imagesTmp.push(f);
          } else {
            data.imagesToDelete.push(f);
          }
        });
        data.highlightImages = imagesTmp;
      });
    },
    success: function (e, r) {
      data.highlightImages.push(r);
    },
  });

  $(
    "#category, #combo-product, #complementary-products, #similar-products, #codes, #colors, #materials, #rooms"
  ).select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true,
  });

  $("#brand").val($("#product_brand").val() || "Ikea");

  $("#color, #material, #classification").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true,
    tags: true,
    maximumSelectionLength: 1,
  });

  $("#category-favorite").select2({
    theme: "bootstrap",
    language: "es",
    allowClear: true,
  });

  $("#in-store").change(function () {
    if ($(this).prop("checked")) {
      $(".store-count-content").show();
    } else {
      $(".store-count-content").hide();
    }
  });

  $("#is-combo").change(function () {
    if ($(this).prop("checked") == true) {
      setComboMode(true);
    } else {
      setComboMode(false);
    }
  });

  $("#combo-product").change(function () {
    currentComboProductCode = $(
      "#combo-product option[value=" + $(this).val() + "]"
    ).data("code");
    currentComboProductPrice = $(
      "#combo-product option[value=" + $(this).val() + "]"
    ).data("price");
  });

  $(".btn-calculate-price").click(function () {
    var weight = getWeight();
    var ikeaPrice = $("#ikea-price").val();
    if (weight && ikeaPrice) {
      ajax(
        $(this).data("path"),
        "POST",
        {
          weight: weight,
          ikeaPrice: ikeaPrice,
          isFurniture: $("#is-furniture").prop("checked"),
          isMattress: $("#is-mattress").prop("checked"),
          isFragile: $("#is-fragile").prop("checked"),
          isAriplaneForniture: false,
          isAriplaneMattress: false,
          isOversize: $("#is-oversize").prop("checked"),
          isTableware: $("#is-tableware").prop("checked"),
          isLamp: $("#is-lamp").prop("checked"),
          numberOfPackages: $("#number-of-packages").val(),
          isFaucet: $("#is-faucet").prop("checked"),
          isGrill: $("#is-grill").prop("checked"),
          isShelf: $("#is-shelf").prop("checked"),
          isDesk: $("#is-desk").prop("checked"),
          isBookcase: $("#is-bookcase").prop("checked"),
          isComoda: $("#is-comoda").prop("checked"),
          isRepisa: $("#is-repisa").prop("checked"),
        },
        function (response) {
          var valueResponse = Number(response).toFixed(2);
          $("#calculate-price").val(valueResponse);
          $("#price").val(valueResponse);
        },
        function (error) {
          alert("Ha ocurrido un error calculando el precio del producto");
        }
      );
    } else {
      alert("Debe llenar los campos de Peso, Límite por envío y Precio IKEA");
    }
  });

  $(".btn-add-combo-product").click(function () {
    if ($("#combo-product").val() && $("#combo-product-count").val()) {
      comboProducts.push({
        id: $("#combo-product").val(),
        count: $("#combo-product-count").val(),
        code: currentComboProductCode,
        price: currentComboProductPrice,
      });

      $("#combo-product").val([]).trigger("change");
      $("#combo-product-count").val("");

      RecalculateComboPrice();
      populateComboProductsTable();
    } else {
      alert("Debe llenar todos los campos del combo");
    }
  });

  $(".btn-add-metaname").click(function () {
    if ($("#metaname").val()) {
      metaNames.push({
        name: $("#metaname").val(),
      });

      $("#metaname").val([]).trigger("change");

      populateMetaNamesTable();
    } else {
      alert("Debe insertar el metaname");
    }
  });

  $('form[name="product"]').submit(function (e) {
    if (!validForm()) {
      e.preventDefault();
    } else {
      var weight = getWeight();

      if (!$("#priority").val()) {
        $("#priority").val(0);
      }

      $("#product_name").val($("#name").val());
      $("#product_brand").val($("#brand").val());
      $("#product_priority").val($("#priority").val());
      $("#product_code").val($("#code").val());
      $("#product_item").val($("#item").val());
      $("#product_isFurniture").val($("#is-furniture").prop("checked"));
      $("#product_isMattress").val($("#is-mattress").prop("checked"));
      $("#product_isFaucet").val($("#is-faucet").prop("checked"));
      $("#product_isGrill").val($("#is-grill").prop("checked"));
      $("#product_isShelf").val($("#is-shelf").prop("checked"));
      $("#product_isDesk").val($("#is-desk").prop("checked"));
      $("#product_isBookcase").val($("#is-bookcase").prop("checked"));
      $("#product_isComoda").val($("#is-comoda").prop("checked"));
      $("#product_isRepisa").val($("#is-repisa").prop("checked"));
      $("#product_description").val($("#description").val());
      $("#product_category").val(JSON.stringify($("#classification").val()));
      $("#product_image").val(data.image.id);
      $("#product_price").val($("#price").val());
      $("#product_images").val(JSON.stringify(data.images));
      $("#product_imagesToDelete").val(JSON.stringify(data.imagesToDelete));
      $("#product_popular").val($("#popular").prop("checked") == true ? 1 : 0);
      $("#product_recent").val($("#recent").prop("checked") == true ? 1 : 0);
      $("#product_inStore").val($("#in-store").prop("checked") == true ? 1 : 0);
      $("#product_isDisabled").val(
        $("#is-disabled").prop("checked") == true ? 1 : 0
      );
      $("#product_countStore").val($("#store-count").val());
      $("#product_color").val($("#color").val());
      $("#product_material").val($("#material").val());
      $("#product_favoritesCategories").val(
        JSON.stringify($("#category-favorite").val())
      );
      $("#product_comboProducts").val(JSON.stringify(comboProducts));
      $("#product_complementaryProducts").val(
        JSON.stringify($("#complementary-products").val())
      );

      $("#product_similarProducts").val(
        JSON.stringify($("#similar-products").val())
      );

      $("#product_weight").val(weight);
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
      $("#product_isAriplaneMattress").val(
        $("#is-airplane-mattress").prop("checked") == true ? 1 : 0
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
      $("#product_metaNames").val(JSON.stringify(metaNames));
      $("#product_widthLeft").val($("#width-left").val());
      $("#product_widthRight").val($("#width-right").val());
      $("#product_width").val($("#width").val());
      $("#product_heightMin").val($("#height-min").val());
      $("#product_heightMax").val($("#height-max").val());
      $("#product_height").val($("#height").val());
      $("#product_deepMin").val($("#deep-min").val());
      $("#product_deepMax").val($("#deep-max").val());
      $("#product_deep").val($("#deep").val());
      $("#product_length").val($("#length").val());
      $("#product_diameter").val($("#diameter").val());
      $("#product_maxLoad").val($("#max-load").val());
      $("#product_area").val($("#area").val());
      $("#product_thickness").val($("#thickness").val());
      $("#product_volume").val($("#volume").val());
      $("#product_surfaceDensity").val($("#surface-density").val());
      $("#product_codes").val(JSON.stringify($("#codes").val()));
      $("#product_colors").val(JSON.stringify($("#colors").val()));
      $("#product_materials").val(JSON.stringify($("#materials").val()));
      $("#product_classification").val($("#classification").val());
      $("#product_rooms").val(JSON.stringify($("#rooms").val()));
    }
  });

  $("#combo-product").val([]).trigger("change");
  $("#metaname").val([]).trigger("change");

  if (comboProducts.length === 0) {
    setComboMode(false);
  }
  populateComboProductsTable();
  populateMetaNamesTable();
});

function setComboMode(comboMode) {
  if (comboMode) {
    $("#combo-products-section").show();

    $("#material-section").hide();
    $("#material")
      .val([$("#material option").prop("value")])
      .trigger("change");

    $("#color-section").hide();
    $("#color")
      .val([$("#color option").prop("value")])
      .trigger("change");

    $("#weight-section").hide();
    $("#price-section").hide();
    $("#shipping-section").hide();
    $("#labels-section").hide();
    $("#calculate-section").hide();

    if (!$("#weight-kg").val()) {
      $("#weight-kg").val(0);
    }
    if (!$("#ikea-price").val()) {
      $("#ikea-price").val(0);
    }
    if (!$("#shipping-limit").val()) {
      $("#shipping-limit").val(0);
    }
    if (!$("#calculate-price").val()) {
      $("#calculate-price").val(0);
    }
    if (!$("#price").val()) {
      $("#price").val(0);
    }
  } else {
    $("#combo-products-section").hide();

    $("#material-section").show();
    $("#color-section").show();

    $("#weight-section").show();
    $("#price-section").show();
    $("#shipping-section").show();
    $("#labels-section").show();
    $("#calculate-section").show();
  }
}

function getWeight() {
  var weightKg = parseFloat($("#weight-kg").val()) || 0;
  var weightOz = parseFloat($("#weight-oz").val()) || 0;
  var weightLb = parseFloat($("#weight-lb").val()) || 0;

  var finalWeight = (weightKg + weightOz / 35.27 + weightLb / 2.2).toFixed(2);
  return finalWeight;
}

function validForm() {
  var valid = true;
  var name = $("#name");
  if (!$(name).val()) {
    addRemoveErrorClass(name, true);
    valid = false;
  } else {
    addRemoveErrorClass(name, false);
  }
  var brand = $("#brand");
  if (!$(brand).val()) {
    addRemoveErrorClass(brand, true);
    valid = false;
  } else {
    addRemoveErrorClass(brand, false);
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
  var item = $("#item");
  if (!$(item).val()) {
    addRemoveErrorClass(item, true);
    valid = false;
  } else {
    addRemoveErrorClass(item, false);
  }
  var material = $("#material");
  if (!$(material).val()) {
    addRemoveErrorClass(material, true);
    valid = false;
  } else {
    addRemoveErrorClass(material, false);
  }
  var color = $("#color");
  if (!$(color).val()) {
    addRemoveErrorClass(color, true);
    valid = false;
  } else {
    addRemoveErrorClass(color, false);
  }
  var weight = $("#weight-kg");
  if (!$(weight).val()) {
    addRemoveErrorClass(weight, true);
    valid = false;
  } else {
    addRemoveErrorClass(weight, false);
  }
  var ikeaPrice = $("#ikea-price");
  if (!$(ikeaPrice).val()) {
    addRemoveErrorClass(ikeaPrice, true);
    valid = false;
  } else {
    addRemoveErrorClass(ikeaPrice, false);
  }
  var shippingLimit = $("#shipping-limit");
  if (!$(shippingLimit).val()) {
    addRemoveErrorClass(shippingLimit, true);
    valid = false;
  } else {
    addRemoveErrorClass(shippingLimit, false);
  }
  var calculatePrice = $("#calculate-price");
  if (!$(calculatePrice).val()) {
    addRemoveErrorClass(calculatePrice, true);
    valid = false;
  } else {
    addRemoveErrorClass(calculatePrice, false);
  }
  var price = $("#price");
  if (!$(price).val()) {
    addRemoveErrorClass(price, true);
    valid = false;
  } else {
    addRemoveErrorClass(price, false);
  }
  var classification = $("#classification");
  if ($(classification).val() == null || $(classification).val().length == 0) {
    addRemoveErrorClass(classification, true);
    valid = false;
  } else {
    addRemoveErrorClass(classification, false);
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
    if (!$(input).parent().hasClass("has-error")) {
      $(input).parent().addClass("has-error");
    }
  } else {
    $(input).parent().removeClass("has-error");
  }
}

function RecalculateComboPrice() {
  var price = 0;
  comboProducts.forEach((comboProduct) => {
    price += comboProduct.price * comboProduct.count;
  });
  $("#price").val(price);
  $("#calculate-price").val(price);
}

function populateComboProductsTable() {
  $(".table-combo-products tbody tr").remove();
  if (comboProducts.length == 0) {
    var tmp_empty = template_combo_product_empty.substring(-1);
    $(".table-combo-products tbody").append(tmp_empty);
  } else {
    $.each(comboProducts, function (i, s) {
      var tmp = template_combo_product
        .substring(-1)
        .replace("%1", s.code)
        .replace("%2", s.count)
        .replace("%3", i);
      $(".table-combo-products tbody").append(tmp);
    });
    $(".btn-remove-combo-product").click(function () {
      var index = $(this).data("index");
      comboProducts.splice(index, 1);
      populateComboProductsTable();
      RecalculateComboPrice();
    });
  }
}

function populateMetaNamesTable() {
  $(".table-metanames tbody tr").remove();
  if (metaNames.length == 0) {
    var tmp_empty = template_metaname_empty.substring(-1);
    $(".table-metanames tbody").append(tmp_empty);
  } else {
    $.each(metaNames, function (i, s) {
      var tmp = template_metaname
        .substring(-1)
        .replace("%1", s.name)
        .replace("%2", i);
      $(".table-metanames tbody").append(tmp);
    });
    $(".btn-remove-metaname").click(function () {
      var index = $(this).data("index");
      metaNames.splice(index, 1);
      populateMetaNamesTable();
    });
  }
}
