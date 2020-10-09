import Carousel from "./modules/global/_carousel";
import Sal from 'sal.js'

let options = {
    cellAlign: 'left',
    pageDots: true,
    wrapAround: true,
    pauseAutoPlayOnHover: false,
    prevNextButtons: false,
};

new Carousel('.hero__slides', options);
new Carousel('.blog__slides', options);
new Sal();