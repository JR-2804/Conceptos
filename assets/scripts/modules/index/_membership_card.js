class MembershipCard {
    constructor(){
        this.memebershipCard = document.querySelector('.membership_card');
        this.memebershipCardClose = document.querySelector('.membership_card__close');

        this.event();
    }
    event(){
        // setTimeout(this.hideCard.bind(this), 20000);
        this.memebershipCardClose.addEventListener('click', this.hideCard.bind(this));
    }

    hideCard(){
        this.memebershipCard.classList.add('membership_card--hide');
    }
}

export default MembershipCard;