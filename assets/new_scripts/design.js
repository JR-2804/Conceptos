import Carousel from "./modules/global/_carousel";

let options = {
    cellAlign: 'left',
    pageDots: true,
    wrapAround: true,
    pauseAutoPlayOnHover: false,
    prevNextButtons: false,
};

new Carousel('.blog__slides', options);