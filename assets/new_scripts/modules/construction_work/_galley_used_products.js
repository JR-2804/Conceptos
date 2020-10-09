class GalleryUsedProducts {
    constructor(){
        this.gallery = document.querySelector('.gallery');
        this.used_products = document.querySelector('.used_products');

        this.events();
    }

    events(){
        [this.gallery, this.used_products].forEach((elem)=>{
            elem.addEventListener('click', this.toggleActiveElement.bind(this));
        });
    }

    toggleActiveElement(){
        this.gallery.classList.toggle('gallery--active');
        this.used_products.classList.toggle('used_products--active');
    }
}

export default GalleryUsedProducts;