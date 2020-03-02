class MembershipCard {
  constructor() {
    this.membershipCard = document.querySelector(".membership_card");
    this.membershipCardClose = document.querySelector(
      ".membership_card__close"
    );

    if (this.membershipCard) {
      this.showOrNot();
    }
  }

  showOrNot() {
    this.membershipCard.classList.remove("membership_card--no-display");
    this.event();
  }

  event() {
    setTimeout(this.hideCard.bind(this), 20000);
    this.membershipCardClose.addEventListener(
      "click",
      this.hideCard.bind(this)
    );
  }

  hideCard() {
    this.membershipCard.classList.add("membership_card--hide");
  }
}

export default MembershipCard;
