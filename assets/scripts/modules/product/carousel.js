import Flickity from "flickity";
import asNavFor from "flickity-as-nav-for";

class Carousel{
    constructor(){
        this.carousel = document.querySelector('.product_images');
        this.carouselSmall = document.querySelector('.product_images__small');

        this.initFlickity();
    }

    initFlickity(){
        new Flickity( this.carousel, {
            // options
            cellAlign: 'left',
            contain: true,
            autoPlay: 5000,
            pauseAutoPlayOnHover: false,
        });

        new Flickity( this.carouselSmall, {
            // options
            asNavFor: '.product_images',
            cellAlign: 'left',
            contain: true,
            groupCells: true,
            prevNextButtons: false,
        });
    }
}

export default Carousel;