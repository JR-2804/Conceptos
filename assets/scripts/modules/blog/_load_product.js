import axios from 'axios';

class LoadProduct{
    constructor(){
        this.loadElements = document.querySelectorAll('div.ProductMarker');
        this.productPrototype = document.querySelector('.product_prototype > div');
        this.url = '/apiProduct';

        this.loadProducts();
    }

    addProduct(productData, index){

        let product = this.productPrototype.cloneNode(true);

        product.querySelector('.conceptos-image').setAttribute('src', productData['image']);
        product.querySelector('a').setAttribute('href', productData['url']);
        product.querySelector('button').setAttribute('data-path', productData['addCart']);

        if (productData['priceOffer']!==null) {
            product.querySelector('.is_offer').classList.add('popular_product');
            product.querySelector('.conceptos-product-price').innerHTML = `$ ${productData['priceOffer']}`;
        }
        else{
            product.querySelector('.conceptos-product-price').innerHTML = `$ ${productData['price']}`;
        }

        this.loadElements[index].querySelector('span:first-child').style.display = 'none';
        this.loadElements[index].appendChild(product);
    }

    loadProducts(){
        this.loadElements.forEach((element, index)=>{
            let code = element.getAttribute('data-product');

            let newUrl = `${this.url}/${code}`;

            axios.get(newUrl).then((response)=>{
                let productData = response.data;
                this.addProduct(productData, index);

            }).catch((error)=>{
                console.error(error);
            })
        });
    }
}

export default LoadProduct;