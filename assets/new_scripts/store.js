import Carousel from "./modules/global/_carousel";

const lite_options = {
    cellAlign: 'left',
    pageDots: false,
    pauseAutoPlayOnHover: false,
    prevNextButtons: false,
};

const options = {
    cellAlign: 'left',
    pageDots: true,
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

new Carousel('.categories__list', lite_options);
new Carousel('.blog__slides', options);
new Carousel('.section_store__content--0', storeSectionOptions);
new Carousel('.section_store__content--1', storeSectionOptions);
new Carousel('.section_store__content--2', storeSectionOptions);
