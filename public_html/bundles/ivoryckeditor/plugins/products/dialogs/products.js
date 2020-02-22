CKEDITOR.dialog.add("productsDialog", function(editor) {
  return {
    title: "Agregar Productos",
    minWidth: 200,
    minHeight: 100,
    contents: [
      {
        id: "tab-basic",
        label: "Opciones Básicas",
        elements: [
          {
            id: "product-id",
            type: "text",
            label: "ID del Producto"
          }
        ]
      }
    ],
    onOk: function() {
      let dialog = this;
      let elm = editor.document.createElement("div");
      let child = editor.document.createElement("span");
      child.setStyle("border", "2px dashed green");
      elm.append(child);

      let product_id = dialog.getValueOf("tab-basic", "product-id");

      elm.setAttribute("data-product", product_id);
      elm.setAttribute("class", "ProductMarker");
      child.setText("SP [" + product_id.toString() + "]");
      editor.insertElement(elm);
    },
    onShow: function() {
      let text = editor
        .getSelection()
        .getStartElement()
        .getText();

      if (text.startsWith("SP [")) {
        var products = text.substr(4, text.length - 5);
        console.log(products);
      }
    }
  };
});
