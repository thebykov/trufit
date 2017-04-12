(function() {
    tinymce.PluginManager.add("gdcpt_terms", function(editor, url) {
        editor.addCommand("GDCPT_Terms_Main", function(){
            var sel_text = editor.selection.getContent({format : "text"}).trim();

            var content = {
                type: 'container',
                html: jQuery("#gdcpt-container-term").html()
            };

            editor.windowManager.open({
                title: "GD Custom Posts And Taxonomies Tools",
                width: 482,
                height: 300,
                items: [ content ]
            });
        });

        editor.addButton("gdcpt_terms_ctrl", {
            type: "splitbutton",
            tooltip: "Term Control",
            cmd : "GDCPT_Terms_Main",
            onPostRender: function() {
                var gdcptTC = this;
                editor.on("nodechange", function(event) {
                    gdcptTC.disabled(editor.selection.isCollapsed());
                });
            }
        });
    });
})();