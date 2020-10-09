import Flickity from "flickity";
import asNavFor from "flickity-as-nav-for";

class Carousel{
    constructor(selector){
        this.carousel = document.querySelector(selector);

        this.initFlickity();
    }

    initFlickity(){
        new Flickity( this.carousel, {
            // options
            cellAlign: 'left',
            // autoPlay: 10000,
            pageDots: true,
            wrapAround: true,
            pauseAutoPlayOnHover: false,
            prevNextButtons: false,
        });

    }
}

export default Carousel;