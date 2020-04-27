import { setCookie, getCookie, getCookieValue } from "../app/_utils";

class ContactCard {
  constructor() {
    this.contactCard = document.querySelector(".contact_card");
    this.contactCardClose = document.querySelector(".contact_card__close");

    this.showOrNot();
  }

  showOrNot() {
    if (getCookie("contactCard")) {
      var cookieValue = getCookieValue("contactCard");

      if (cookieValue > 3) {
        this.contactCard.classList.add("contact_card--no-display");
      } else {
        setCookie(parseInt(cookieValue) + 1, "contactCard");
        this.contactCard.classList.remove("contact_card--no-display");
        this.event();
      }
    } else {
      setCookie("1", "contactCard");
      this.contactCard.classList.remove("contact_card--no-display");
      this.event();
    }
  }

  event() {
    setTimeout(this.hideCard.bind(this), 20000);
    this.contactCardClose.addEventListener("click", this.hideCard.bind(this));
  }

  hideCard() {
    this.contactCard.classList.add("contact_card--hide");
  }
}

export default ContactCard;
