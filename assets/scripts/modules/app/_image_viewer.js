class ImageViewer {
  constructor() {
    this.modal = document.querySelector("#imageViewerModal");
    this.init();
  }

  init() {
    document.querySelectorAll(".image-viewer").forEach(this.displayModalOnClick.bind(this));
  }

  displayModalOnClick(node) {
    node.addEventListener("click", this.displayModal.bind(this));
  }

  displayModal(e) {
    this.modal.querySelector("img").setAttribute("src", e.target.getAttribute("src"));
    $(this.modal).modal();
  }
}

export default ImageViewer;
