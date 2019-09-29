function IncreaseSearchBarSize() {
  $("#easyadmin-list-Product > div > div > section.content-header > div > div:nth-child(1)").removeClass();
  $("#easyadmin-list-Product > div > div > section.content-header > div > div:nth-child(1)").addClass("col-2");
  $("#easyadmin-list-Product > div > div > section.content-header > div > div:nth-child(2)").removeClass();
  $("#easyadmin-list-Product > div > div > section.content-header > div > div:nth-child(2)").addClass("col-10");
}

$(document).ready(function () {
  if ($('#flash-messages').length > 0) {
    setTimeout(function () {
      $('#flash-messages').hide('slow');
    }, 5000);
  }

  IncreaseSearchBarSize()
});
