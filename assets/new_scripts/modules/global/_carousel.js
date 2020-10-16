import Flickity from "flickity";
import asNavFor from "flickity-as-nav-for";

class Carousel{
    constructor(selector, options){
        this.carousel = document.querySelector(selector);
        this.options = options;
        this.initFlickity();
    }

    initFlickity(){
        let amount = this.carousel.children.length;
        if (amount <= 3) {
            this.options.wrapAround = false;
            this.options.pageDots = true;
            this.options.prevNextButtons = false;
        }

        new Flickity( this.carousel, this.options);
    }
}

export default Carousel;