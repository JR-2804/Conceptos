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
      var content = "<h1>Sample Product</h1>";

      var instance = this.getParentEditor();
      instance.insertHtml(content);
    }
  };
});
