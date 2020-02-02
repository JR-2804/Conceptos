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
      let elm = editor.document.createElement('div');
      let child = editor.document.createElement('span');
      elm.append(child);
      let product_id = dialog.getValueOf('tab-basic', 'product-id');
      console.log(product_id);

      elm.setAttribute('data-product', product_id);
      elm.setAttribute( 'class','ProductMarker');
      child.setText( "[SP]");
      editor.insertElement(elm);
      // var instance = this.getParentEditor();
      // instance.insertHtml(elm);
    }
  };
});
