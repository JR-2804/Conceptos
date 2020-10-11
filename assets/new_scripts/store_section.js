import Carousel from "./modules/global/_carousel";

const liteOptions = {
    cellAlign: 'left',
    pageDots: false,
    pauseAutoPlayOnHover: false,
    prevNextButtons: true,
    wrapAround: true,
};

const storeSectionOptions = {
    cellAlign: 'left',
    pageDots: true,
    pauseAutoPlayOnHover: false,
    prevNextButtons: false,
};

const topPopularOptions = {
    cellAlign: 'left',
    pageDots: false,
    pauseAutoPlayOnHover: false,
    prevNextButtons: true,
};

new Carousel('.sub_categories__list', liteOptions);
new Carousel('.section_store__top_content', topPopularOptions);
new Carousel('.section_store__content', storeSectionOptions);