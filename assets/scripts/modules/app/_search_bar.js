import Headroom from "headroom.js";

class SearchBar {
  constructor() {
    this.body = document.querySelector("body");
    this.navbar = document.querySelector(".navbar");
    this.navbarSupportedContent = document.querySelector(
      ".conceptos-navbar #navbarSupportedContent"
    );
    this.categoriesMenu = document.querySelector("#categories-menu");
    this.modalCart = document.querySelector("#cartPreviewModal .modal-dialog");
    this.imageViewerModal = document.querySelector(
      "#imageViewerModal .modal-dialog"
    );

    this.productDialog = document.querySelector(
      "#filterProductsModal .modal-dialog"
    );
    this.searchBar = document.querySelector(".search_bar");

    this.init();
  }

  init() {
    let headroom = new Headroom(this.searchBar);
    headroom.init();

    let topDisplacement =
      this.searchBar.clientHeight + this.navbar.clientHeight;
    let navbarDisplacement = this.navbar.clientHeight;

    this.body.setAttribute("style", "top: " + topDisplacement + "px");
    this.searchBar.setAttribute("style", "top: " + navbarDisplacement + "px");
    this.navbarSupportedContent.setAttribute(
      "style",
      "top: " + navbarDisplacement + "px"
    );
    this.categoriesMenu.setAttribute(
      "style",
      "top: " + navbarDisplacement + "px"
    );
    this.modalCart.setAttribute("style", "top: " + navbarDisplacement + "px");
    this.imageViewerModal.setAttribute(
      "style",
      "top: " + navbarDisplacement + "px"
    );

    if (this.productDialog !== null)
      this.productDialog.setAttribute(
        "style",
        "top: " + navbarDisplacement + "px"
      );
  }

  spaces() {}
}

export default SearchBar;
