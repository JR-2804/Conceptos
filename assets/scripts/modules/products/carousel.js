import Flickity from "flickity";

class Carousel{
    constructor(){
        this.carousel = document.querySelector('.popular_products');

        this.initFlickity();
    }

    initFlickity(){
        new Flickity( this.carousel, {
            // options
            cellAlign: 'left',
            contain: true,
            autoPlay: 5000,
            pauseAutoPlayOnHover: false,
            pageDots: false,
            groupCells: true,
        });
    }
}

export default Carousel;