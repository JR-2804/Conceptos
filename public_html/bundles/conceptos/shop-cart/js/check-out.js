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

  $("#type-select").change(function() {
    if ($(this).val() === "facture") {
      $("#prefactures-select").show();
    } else {
      $("#prefactures-select").hide();
    }
  });

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
    } else {
      $("#memberNumber").val(memberNumber);
      $("#transportCost").val(transportCost);
      $("#paymentType").val(paymentType);
      $("#paymentCurrency").val(paymentCurrency);
      $("#products").val(JSON.stringify(products));

      $("#check_out_type").val($("#type-select").val());
      $("#check_out_ignoreTransport").val(
        $("#ignoreTransport").prop("checked")
      );

      if ($("#type-select").val() === "facture") {
        $("#check_out_prefacture").val($("#prefactures-select").val());
      }
    }
  });
});
