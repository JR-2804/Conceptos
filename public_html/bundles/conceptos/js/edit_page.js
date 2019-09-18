var data = {};
var members = [];
Dropzone.autoDiscover = false;
$(document).ready(function() {
  data = JSON.parse($("#page_data").val());
  init();

  $('form[name="page"]').submit(function(e) {
    if (validateSubmitData()) {
      var data = generateData();
      $("#page_data").val(JSON.stringify(data));
    } else {
      alert("Debe llenar todos los campos");
      e.preventDefault();
    }
  });
});
