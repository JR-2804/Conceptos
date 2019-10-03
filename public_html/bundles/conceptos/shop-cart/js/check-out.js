$(document).ready(function() {
  window.addEventListener(
    "load",
    function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName("needs-validation");

      // Loop over them and prevent submission
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener(
          "submit",
          function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add("was-validated");
          },
          false
        );
      });
    },
    false
  );

  if (memberNumber) {
    $(".check-out #cb6").click();
    $("#check_out_memberNumber").val(memberNumber);
  }

  $("#send-request-button").click(function(e) {
    if (
      !$("#check_out_name").val() ||
      !$("#check_out_email").val() ||
      !$("#check_out_address").val() ||
      (!$("#check_out_phone").val() && !$("#check_out_movil").val()) ||
      !$("#termsAndConditions").prop("checked") ||
      !$("#privacyPolicy").prop("checked")
    ) {
      alert("Rellene todos los campos");
      e.preventDefault();
    }
  });
});
