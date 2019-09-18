var images = {
  gift: {
    main: undefined,
    gift15: undefined,
    gift25: undefined,
    gift50: undefined
  },
  member: {
    main: undefined,
    gold: undefined,
    platinum: undefined,
    premium: undefined
  },
  team: undefined
};

$(document).ready(function() {
  $("#gift_text_main").ckeditor();
  $("#member_main_text").ckeditor();
  $("#team_text").ckeditor();

  var dropzoneGiftMain = new Dropzone("form#picture-dropzone-gift_main", {
    url: $("#picture-dropzone-gift_main").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGiftMain = this;
      if (data.gift.image) {
        var mockFile = {
          name: data.gift.image.name,
          size: data.gift.image.size
        };
        dropzoneGiftMain.emit("addedfile", mockFile);
        dropzoneGiftMain.emit("thumbnail", mockFile, data.gift.image.path);
        dropzoneGiftMain.emit("complete", mockFile);
        dropzoneGiftMain.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneGiftMain.options.maxFiles =
          dropzoneGiftMain.options.maxFiles - existingFileCount;
      }
      dropzoneGiftMain.on("removedfile", function() {
        images.gift.main = undefined;
        dropzoneGiftMain.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.gift.main = r;
    }
  });
  var dropzoneGift15 = new Dropzone("form#picture-dropzone-gift-15", {
    url: $("#picture-dropzone-gift-15").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGift15 = this;
      if (data.gift.gift15) {
        var mockFile = {
          name: data.gift.gift15.name,
          size: data.gift.gift15.size
        };
        dropzoneGift15.emit("addedfile", mockFile);
        dropzoneGift15.emit("thumbnail", mockFile, data.gift.gift15.path);
        dropzoneGift15.emit("complete", mockFile);
        dropzoneGift15.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneGift15.options.maxFiles =
          dropzoneGift15.options.maxFiles - existingFileCount;
      }
      dropzoneGift15.on("removedfile", function() {
        images.gift.gift15 = undefined;
        dropzoneGift15.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.gift.gift15 = r;
    }
  });
  var dropzoneGift25 = new Dropzone("form#picture-dropzone-gift-25", {
    url: $("#picture-dropzone-gift-25").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGift25 = this;
      if (data.gift.gift25) {
        var mockFile = {
          name: data.gift.gift25.name,
          size: data.gift.gift25.size
        };
        dropzoneGift25.emit("addedfile", mockFile);
        dropzoneGift25.emit("thumbnail", mockFile, data.gift.gift25.path);
        dropzoneGift25.emit("complete", mockFile);
        dropzoneGift25.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneGift25.options.maxFiles =
          dropzoneGift25.options.maxFiles - existingFileCount;
      }
      dropzoneGift25.on("removedfile", function() {
        images.gift.gift25 = undefined;
        dropzoneGift25.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.gift.gift25 = r;
    }
  });
  var dropzoneGift50 = new Dropzone("form#picture-dropzone-gift-50", {
    url: $("#picture-dropzone-gift-50").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneGift50 = this;
      if (data.gift.gift50) {
        var mockFile = {
          name: data.gift.gift50.name,
          size: data.gift.gift50.size
        };
        dropzoneGift50.emit("addedfile", mockFile);
        dropzoneGift50.emit("thumbnail", mockFile, data.gift.gift50.path);
        dropzoneGift50.emit("complete", mockFile);
        dropzoneGift50.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneGift50.options.maxFiles =
          dropzoneGift50.options.maxFiles - existingFileCount;
      }
      dropzoneGift50.on("removedfile", function() {
        images.gift.gift50 = undefined;
        dropzoneGift50.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.gift.gift50 = r;
    }
  });

  var dropzoneMemberMain = new Dropzone("form#picture-dropzone-member_main", {
    url: $("#picture-dropzone-member_main").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneMemberMain = this;
      if (data.members.image) {
        var mockFile = {
          name: data.members.image.name,
          size: data.members.image.size
        };
        dropzoneMemberMain.emit("addedfile", mockFile);
        dropzoneMemberMain.emit("thumbnail", mockFile, data.members.image.path);
        dropzoneMemberMain.emit("complete", mockFile);
        dropzoneMemberMain.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneMemberMain.options.maxFiles =
          dropzoneMemberMain.options.maxFiles - existingFileCount;
      }
      dropzoneMemberMain.on("removedfile", function() {
        images.member.main = undefined;
        dropzoneMemberMain.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.member.main = r;
    }
  });

  var dropzoneMemberGold = new Dropzone("form#picture-dropzone-member_gold", {
    url: $("#picture-dropzone-member_gold").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneMemberGold = this;
      if (data.members.gold) {
        var mockFile = {
          name: data.members.gold.name,
          size: data.members.gold.size
        };
        dropzoneMemberGold.emit("addedfile", mockFile);
        dropzoneMemberGold.emit("thumbnail", mockFile, data.members.gold.path);
        dropzoneMemberGold.emit("complete", mockFile);
        dropzoneMemberGold.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneMemberGold.options.maxFiles =
          dropzoneMemberGold.options.maxFiles - existingFileCount;
      }
      dropzoneMemberGold.on("removedfile", function() {
        images.member.gold = undefined;
        dropzoneMemberGold.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.member.gold = r;
    }
  });
  var dropzoneMemberPlatinum = new Dropzone(
    "form#picture-dropzone-member_platinum",
    {
      url: $("#picture-dropzone-member_platinum").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneMemberPlatinum = this;
        if (data.members.platinum) {
          var mockFile = {
            name: data.members.platinum.name,
            size: data.members.platinum.size
          };
          dropzoneMemberPlatinum.emit("addedfile", mockFile);
          dropzoneMemberPlatinum.emit(
            "thumbnail",
            mockFile,
            data.members.platinum.path
          );
          dropzoneMemberPlatinum.emit("complete", mockFile);
          dropzoneMemberPlatinum.files.push(mockFile);
          var existingFileCount = 1;
          dropzoneMemberPlatinum.options.maxFiles =
            dropzoneMemberPlatinum.options.maxFiles - existingFileCount;
        }
        dropzoneMemberPlatinum.on("removedfile", function() {
          images.member.platinum = undefined;
          dropzoneMemberPlatinum.options.maxFiles = 1;
        });
      },
      success: function(e, r) {
        images.member.platinum = r;
      }
    }
  );

  var dropzoneMemberPremium = new Dropzone(
    "form#picture-dropzone-member_premium",
    {
      url: $("#picture-dropzone-member_premium").attr("action"),
      maxFiles: 1,
      thumbnailWidth: 100,
      thumbnailHeight: 100,
      addRemoveLinks: true,
      dictCancelUpload: "Cancelar",
      dictRemoveFile: "Eliminar",
      previewTemplate: document.querySelector("#preview-template").innerHTML,
      acceptedFiles: ".jpg,.jpeg,.png,.gif",
      init: function() {
        dropzoneMemberPremium = this;
        if (data.members.premium) {
          var mockFile = {
            name: data.members.premium.name,
            size: data.members.premium.size
          };
          dropzoneMemberPremium.emit("addedfile", mockFile);
          dropzoneMemberPremium.emit(
            "thumbnail",
            mockFile,
            data.members.premium.path
          );
          dropzoneMemberPremium.emit("complete", mockFile);
          dropzoneMemberPremium.files.push(mockFile);
          var existingFileCount = 1;
          dropzoneMemberPremium.options.maxFiles =
            dropzoneMemberPremium.options.maxFiles - existingFileCount;
        }
        dropzoneMemberPremium.on("removedfile", function() {
          images.member.premium = undefined;
          dropzoneMemberPremium.options.maxFiles = 1;
        });
      },
      success: function(e, r) {
        images.member.premium = r;
      }
    }
  );

  var dropzoneTeam = new Dropzone("form#picture-dropzone-team", {
    url: $("#picture-dropzone-team").attr("action"),
    maxFiles: 1,
    thumbnailWidth: 100,
    thumbnailHeight: 100,
    addRemoveLinks: true,
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Eliminar",
    previewTemplate: document.querySelector("#preview-template").innerHTML,
    acceptedFiles: ".jpg,.jpeg,.png,.gif",
    init: function() {
      dropzoneTeam = this;
      if (data.team.image) {
        var mockFile = {
          name: data.team.image.name,
          size: data.team.image.size
        };
        dropzoneTeam.emit("addedfile", mockFile);
        dropzoneTeam.emit("thumbnail", mockFile, data.team.image.path);
        dropzoneTeam.emit("complete", mockFile);
        dropzoneTeam.files.push(mockFile);
        var existingFileCount = 1;
        dropzoneTeam.options.maxFiles =
          dropzoneTeam.options.maxFiles - existingFileCount;
      }
      dropzoneTeam.on("removedfile", function() {
        images.team = undefined;
        dropzoneTeam.options.maxFiles = 1;
      });
    },
    success: function(e, r) {
      images.team = r;
    }
  });
});

function validateSubmitData() {
  if (!images.gift.main) {
    return false;
  }
  if (!images.gift.gift15) {
    return false;
  }
  if (!images.gift.gift25) {
    return false;
  }
  if (!images.gift.gift50) {
    return false;
  }
  if (!$("#gift_text_main").val()) {
    return false;
  }
  if (!images.member.main) {
    return false;
  }
  if (!images.member.gold) {
    return false;
  }
  if (!images.member.platinum) {
    return false;
  }
  if (!images.member.premium) {
    return false;
  }
  if (!$("#member_main_text").val()) {
    return false;
  }
  if (!images.team) {
    return false;
  }
  if (!$("#team_text").val()) {
    return false;
  }
  return true;
}

function generateData() {
  return {
    gift: {
      image: images.gift.main,
      text: $("#gift_text_main").val(),
      gift15: images.gift.gift15,
      gift25: images.gift.gift25,
      gift50: images.gift.gift50
    },
    members: {
      image: images.member.main,
      text: $("#member_main_text").val(),
      gold: images.member.gold,
      platinum: images.member.platinum,
      premium: images.member.premium
    },
    team: {
      image: images.team,
      text: $("#team_text").val()
    }
  };
}

function init() {
  $("#gift_text_main").val(data.gift.text);
  images.gift.main = data.gift.image;
  images.gift.gift15 = data.gift.gift15;
  images.gift.gift25 = data.gift.gift25;
  images.gift.gift50 = data.gift.gift50;
  $("#member_main_text").val(data.members.text);
  images.member.main = data.members.image;
  images.member.gold = data.members.gold;
  images.member.platinum = data.members.platinum;
  images.member.premium = data.members.premium;
  // populateCards();
  $("#team_text").val(data.team.text);
  images.team = data.team.image;
}
