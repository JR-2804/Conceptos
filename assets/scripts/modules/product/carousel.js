import Flickity from "flickity";
import asNavFor from "flickity-as-nav-for";

class Carousel{
    constructor(){
        this.carousel = document.querySelector('.product_images');
        this.carouselSmall = document.querySelector('.product_images__small');
        this.carouselSmallCount = document.querySelectorAll('.product_images__small .carousel__cell');

        this.initFlickity();
    }

    initFlickity(){
        new Flickity( this.carousel, {
            // options
            cellAlign: 'left',
            contain: true,
            autoPlay: 10000,
            pageDots: false,
            wrapAround: true,
            pauseAutoPlayOnHover: false,
        });

        if (this.carouselSmall != null)
            new Flickity( this.carouselSmall, {
                // options
                asNavFor: '.product_images',
                cellAlign: 'left',
                contain: true,
                groupCells: true,
                pageDots: false,
                wrapAround: this.carouselSmallCount.length > 3,
                prevNextButtons: false,
            });
    }
}

export default Carousel;