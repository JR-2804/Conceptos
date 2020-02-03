CKEDITOR.plugins.add( 'MapMarker', {
    icons: 'MapMarker',
    init: function( editor ) {
        editor.addCommand( 'MapMarker', new CKEDITOR.dialogCommand( 'MapMarkerDialog', {
            allowedContent: 'span[!data-map,!class]'
        } ) );
        editor.ui.addButton( 'MapMarker', {
            label: 'Insert MapMarker',
            command: 'MapMarker',
            toolbar: 'insert'
        });

        if ( editor.contextMenu ) {

            // Add a context menu group with the Edit Abbreviation item.
            editor.addMenuGroup( 'MapMarkerGroup' );
            editor.addMenuItem( 'MapMarkerItem', {
                label: 'Edit MapMarker',
                icon: this.path + 'navigation.png',
                command: 'MapMarker',
                group: 'MapMarkerGroup'
            });

            editor.contextMenu.addListener( function( element ) {
                if ( element['$'].className === 'MapMarker' ) {
                // if ( element.getAscendant( 'img', true ) ) {
                    return { MapMarkerItem: CKEDITOR.TRISTATE_OFF };
                }
            });
        }

        CKEDITOR.dialog.add( 'MapMarkerDialog', this.path + 'MapMarkerDialog.js' );
        CKEDITOR.addCss('.MapMarker{background: #f2f8ff url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAjCAYAAAAaLGNkAAADZUlEQVRYhe2Xv0vzXBTHn3/N3F+NHbSEbIKDDg6hkx0cXPzRDgoZOjUJ4iAiurlZcBAERQvSgFlEazfbRUWklQrfd3i490nMTX+87+v7Pi+8Bw73cm/OOZ9zcpKb/MBvID/+bQDgvwjR7/cRBAEsy4JpmhBCQAiB6elp2LaNnZ0d9Pv974EIwxC5XC5ThRBqlPMwDP8+iHw+n3Duui7a7Xbqunq9noASQsA0zb8G0e12lTMhBJrNZqaTRqOBTqcDAHh5eYHv+4kKdbvdySG63S4456lsoihCs9nE2toaNjY24Ps+giBAo9EA5xxvb28AoPpibm5OJTEMRAshATjnYIzBNE21Ftd6vQ4AaLfbODo6UvYnJydqvrq6CsYYcrnc+BCy60ulEm5ubhSMHKVeX18D+NULS0tLmUFs2wbnPLNHEhBhGKqAUlZWVhLB47q8vKyqks/nhz6e0kb31CQgZLbn5+dqrVwuZ0JIvbq6AgC8vr5mQhwfH6cSTEH0ej3lVIpsOEqpGuNzIQQ+Pj4yA2dV46uNgqjVauCcY3t7W20Wi0Vt5oyxBOy4Uq1WwTmH7/t6CMuywBjDw8OD2nx+foYQQmUvg+tKOo7c39+DMQbbtvUQjDEwxlKGruumMn96evpTEFlxRkLIvZmZGQDA+/s77u7uEEURBoPBPwcxGAwwPz8PxhgODg4mDvwV4ms/jQURF9d1Afy8JYeHh0PPFJ3IJ0wLUSgUwBhDq9UayxnnHIQQOI6DUqmEKIpG2rRaLTDGUCgU9BCe54FSikqlMtLZ5+enqhxjDJTSoWeDlK2tLVBK4XmeHqLX64FSOtYtOT09VWXVaRAEWjsJnfmyAn7dr7Ozs5EgsgI6LZfLqesvLi60/ZCCaDabIISMrMbt7S0qlQoopTAMIzHqgsShdY2cOsoZYyCEYHFxcSgIAOzv74NSCkJIYvwqjuMMBdR+1EiD+Dmik06nMxJgfX1d7WeJFkI6J4RgYWFhKEi8F6rVamLPcRzlZ+LPOwkSzzCrWWWQYrGo1i4vL9X6KIChEFIopZiamgIhBIZhYHNzE4+Pj2p/b28PhBCEYYjd3d1E8KwemBgC+PnZF3duGIYa5VyCSp3kdT7xb2CtVsPs7GwiICEElmXB87zv+w38bvkfQspvAfEHUnWJIMFgiBYAAAAASUVORK5CYII=) no-repeat scroll center center; border: 4px dashed #2c903e; display: block; width:35px; height: 35px;}');

    },

    afterInit: function( editor ) {
        let dataProcessor = editor.dataProcessor;
        let dataFilter = dataProcessor && dataProcessor.dataFilter;
        if ( dataFilter ) {
            dataFilter.addRules({
                elements: {
                    'span': function (element) {
                        // if(element.classes !== undefined )
                        if (element.attributes.class === 'MapMarker')
                            return editor.createFakeParserElement(element, 'MapMarker', 'div', true);
                    }
                }
            }, 10);
        }

    }

});
