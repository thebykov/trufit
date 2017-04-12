var gdtt_pluginurl;
var gdtt_tax_codes;
var gdtt_tax_names;
var gdtt_tax_bases;

(function() {
    tinymce.create("tinymce.plugins.gdttTinyMain", {
        init: function(ed, url) {
            gdtt_pluginurl = url;
            gdtt_tax_codes = ed.settings.gdtt_taxonomies_ids.split("|");
            gdtt_tax_names = ed.settings.gdtt_taxonomies_names.split("|");
            gdtt_tax_bases = ed.settings.gdtt_taxonomies_base.split("|");

            ed.addCommand("mcegdttTinyMain", function() {
                var sel_text = tinyMCE.activeEditor.selection.getContent({format : "text"}).trim();

                jQuery("#txOriginalText").val(sel_text);

                ed.windowManager.open({
                    title: "GD Custom Posts And Taxonomies Tools",
                    width: 480,
                    height: "auto",
                    wpDialog: true,
                    id: "gdtt-tinymce-plugin"
                }, {
                    plugin_url: url
                });
            });

            ed.onNodeChange.add(function(ed, cm, n) {
                cm.setActive("gdttTinyMain", n.nodeName === "IMG");
                cm.get("gdttTinyMain").setDisabled(ed.selection.isCollapsed());
            });
        },

        createControl : function(n, cm) {
            switch (n) {
                case "gdttTinyMain":
                    var c = cm.createSplitButton("gdttTinyMain", {
                        title : "GD Custom Posts And Taxonomies Tools",
                        image : gdtt_pluginurl + "/gfx/taximg.png",
                        cmd : "mcegdttTinyMain"
                    });

                    c.onRenderMenu.add(function(c, m) {
                        m.add({title : "Create Term", "class" : "mceMenuItemTitle"}).setDisabled(1);

                        for (var i = 0; i < gdtt_tax_codes.length; i++) {
                            m.add({h: gdtt_tax_bases[i], id : gdtt_tax_codes[i], 
                            title: gdtt_tax_names[i], "class" : "mcegdttMenuItem",
                            onclick: function() {
                                var sel_text = tinyMCE.activeEditor.selection.getContent({format : "text"});

                                if (this.h === "0") {
                                    jQuery("#new-tag-" + this.id).val(sel_text);
                                    jQuery("#new-tag-" + this.id).next().click();
                                } else {
                                    jQuery("#" + this.id + "-add").show();
                                    jQuery("#new" + this.id).val(sel_text);
                                    jQuery("#" + this.id + "-add-submit").click();
                                    jQuery("#" + this.id + "-add").hide();
                                }
                            }
                            });
                        }
                    });

                    return c;
            }
            return null;
        },

        getInfo : function() {
            return {
                longname  : "GD Custom Posts And Taxonomies Tools Pro",
                author 	  : "Milan Petrovic",
                authorurl : "http://www.dev4press.com/",
                infourl   : "http://www.dev4press.com/gd-custom-posts-and-taxonomies-tools/",
                version   : "4.1.1"
            };
        }
    });

    tinymce.PluginManager.add("gdttTinyMain", tinymce.plugins.gdttTinyMain);
})();
