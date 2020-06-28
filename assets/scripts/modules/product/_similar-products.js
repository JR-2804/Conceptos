class SimilarProducts{
    constructor(){
        this.expandLink = document.querySelector('.similar_products__show_button button');
        this.similarProductContainer = document.querySelector('.similar_products__container');
        this.similarProductModal = document.querySelector('.similar_products__container__modal');
        this.btnClose = document.querySelector('.similar_products__container__modal__header__close');
        this.body = document.querySelector('body');
        this.events();
    }

    events(){

        if (this.expandLink === null)
            return;

        this.expandLink.addEventListener('click', this.toggleModal.bind(this));
        this.similarProductContainer.addEventListener('click', this.toggleModal.bind(this));
        this.btnClose.addEventListener('click', this.toggleModal.bind(this));
        this.similarProductModal.addEventListener('click', (e)=>{e.stopPropagation();});
    }

    toggleModal(e){
        e.preventDefault();
        this.similarProductContainer.classList.toggle('similar_products__container--hide');
        this.body.classList.toggle('no-scroll');
    }
}

export default SimilarProducts;