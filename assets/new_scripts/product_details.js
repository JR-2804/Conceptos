import Carousel from "./modules/global/_carousel";

const options = {
    cellAlign: 'left',
    pageDots: false,
    wrapAround: true,
    pauseAutoPlayOnHover: false,
    prevNextButtons: true,
};

const optionsSmall = {
    asNavFor: '.gallery__content',
    cellAlign: 'left',
    pageDots: false,
    wrapAround: true,
    pauseAutoPlayOnHover: false,
    prevNextButtons: false,
};

const storeSectionOptions = {
    cellAlign: 'left',
    pageDots: true,
    pauseAutoPlayOnHover: false,
    prevNextButtons: false,
};

new Carousel('.gallery__content', options);
new Carousel('.gallery__small', optionsSmall);
new Carousel('.related_products__content', storeSectionOptions);
