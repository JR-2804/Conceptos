import axios from "axios";

class LoadProduct {
  constructor() {
    this.loadElements = document.querySelectorAll("div.ProductMarker");
    this.productPrototype = document.querySelector(".product_prototype > div");
    this.url = "/apiProduct";

    this.loadProducts();
  }

  addProduct(productData, index) {
    this.loadElements[index].querySelector("span:first-child").style.display = "none";
    this.loadElements[index].appendChild(product);
  }

  loadProducts() {
    this.loadElements.forEach((element, index) => {
      let code = element.getAttribute("data-product");

      let newUrl = `${this.url}/${code}`;

      axios
        .get(newUrl)
        .then(response => {
          let productData = response.data;
          this.addProduct(productData, index);
        })
        .catch(error => {
          console.error(error);
        });
    });
  }
}

export default LoadProduct;
