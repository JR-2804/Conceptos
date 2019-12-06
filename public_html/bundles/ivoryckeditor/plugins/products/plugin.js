CKEDITOR.plugins.add("products", {
  init: function(editor) {
    editor.addCommand(
      "productsCommand",
      new CKEDITOR.dialogCommand("productsDialog")
    );

    editor.ui.addButton("Products", {
      label: "Insert Products",
      command: "productsCommand",
      icon: this.path + "icons/cart.png",
      toolbar: "insert"
    });

    CKEDITOR.dialog.add("productsDialog", this.path + "dialogs/products.js");
  }
});
