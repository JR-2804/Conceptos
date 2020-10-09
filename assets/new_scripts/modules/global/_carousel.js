import Flickity from "flickity";
import asNavFor from "flickity-as-nav-for";

class Carousel{
    constructor(selector, options){
        this.carousel = document.querySelector(selector);
        this.options = options;
        this.initFlickity();
    }

    initFlickity(){
        new Flickity( this.carousel, this.options);
    }
}

export default Carousel;