/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
var gdCPTAdmin = {
    tmp: {
        settings_toggle: [],
        form_mediaupload_function: null,
        custom_field_editor: "new",
        custom_field_to_delete: null,
        custom_fields_count: 0,
        custom_fields: { },
        meta_box_group_editor: "new",
        meta_box_group_to_delete: null,
        meta_box_groups_count: 0,
        meta_box_editor: "new",
        meta_box_to_delete: null,
        meta_boxes_count: 0,
        meta_box_groups: { },
        meta_boxes: { },
        post_types: { },
        post_types_map: { },
        post_types_map_groups: { }
    },
    tpl: {
        cfe_row: "", mbe_row: ""
    },
    meta: {
        
    },
    tpl_render: function(tpl, obj) {
        var result = tpl;
        var i;
        jQuery.each(obj, function(idx, val){
            var code = "%" + idx.toUpperCase() + "%";
            if (idx === "required") {
                val = val ? gdCPTTools.yes : gdCPTTools.no;
            }
            for (i = 0; i < 32; i++) {result = result.replace(code, val);}
        });
        return result;
    },
    grid_order: function(id, item, for_type) {
        jQuery(id + " td.column-icon").css("cursor", "move");
        jQuery(id + " tbody").sortable({
            items: "tr." + item,
            cursor: "move",
            axis: "y",
            containment: id,
            scrollSensitivity: 32,
            helper: function(e, ui) {					
                ui.children().each(function() {
                    jQuery(this).width(jQuery(this).width());
                });

                return ui;
            },
            start: function(event, ui) {
                ui.item.addClass("gdtt-dragged");
            },
            stop: function(event, ui) {
                ui.item.removeClass("gdtt-dragged");
            },
            update: function(event, ui) {
                var order = [];

                jQuery(id + " tr." + item).each(function(idx) {
                    order.push(jQuery(this).attr("gdcptid").substr(4));
                });

                jQuery.ajax({
                    dataType: "html", data: { list: order, type: for_type },
                    type: "POST", url: "admin-ajax.php?action=gd_cpt_change_order&_ajax_nonce=" + gdCPTTools.nonce
                });
            }
        });
    },
    custom_dialog: {
        open: function(title, content) {
            jQuery("#gdr2dialog_custom .gdr2-dialog-content p").html(content);
            jQuery("#gdr2dialog_custom").dialog("option", "title", title);
            jQuery("#gdr2dialog_custom").dialog("open");
        },
        close: function() {
            jQuery("#gdr2dialog_custom").dialog("close");
        }
    },
    caps: {
        list: {
            cpt: ["edit_post", "edit_posts", "edit_private_posts", "edit_published_posts", "edit_others_posts", "publish_posts", "read_post", "read_private_posts", "delete_post", "delete_posts", "delete_private_posts", "delete_published_posts", "delete_others_posts"],
            tax: ["manage_terms", "edit_terms", "delete_terms", "assign_terms"]
        },
        init: function() {
            jQuery("#cpt_show").click(function(){gdCPTAdmin.caps.load("cpt");});
            jQuery("#tax_show").click(function(){gdCPTAdmin.caps.load("tax");});

            jQuery("#gdcpt-caps-form-cpt, #gdcpt-caps-form-tax").submit(function() {
                jQuery(this).ajaxSubmit({
                    beforeSubmit: function() {jQuery("#gdr2dialogsave").dialog("open");},
                    success: function(json) {
                        cpt_rules = json;
                        jQuery("#gdr2dialogsave").dialog("close");
                    },
                    dataType: "json",
                    url: ajaxurl + "?action=gd_cpt_save_caps&_ajax_nonce=" + gdCPTTools.nonce
                });
                return false;
            });
        },
        load: function(what) {
            jQuery("#editor-" + what).show().find(".gdr2-control-bool input").removeAttr("checked");
            var i;
            var rule = jQuery("#" + what + "_rule").val();
            var role = jQuery("#" + what + "_role").val();
            jQuery("#" + what + "_info_name").val(rule);
            jQuery("#" + what + "_info_role").val(role);

            if (cpt_rules[what][rule].active[role]) {
                jQuery("#" + what + "_info_active").attr("checked", "checked");
            }

            for (i = 0; i < gdCPTAdmin.caps.list[what].length; i++) {
                var desc = what === "cpt" ? gdCPTAdmin.caps.list[what][i].replace(/post/, rule) :
                                            gdCPTAdmin.caps.list[what][i].replace(/terms/, rule);
                jQuery("#gdr2-el-" + what + "_caps_" + gdCPTAdmin.caps.list[what][i] + " span").attr("qtip-content", desc);
                if (jQuery.inArray(gdCPTAdmin.caps.list[what][i], cpt_rules[what][rule].caps[role]) > -1) {
                    jQuery("#" + what + "_caps_" + gdCPTAdmin.caps.list[what][i]).attr("checked", "checked");
                }
            }
        }
    },
    editor: {
        update_fields_select: function() {
            jQuery("#gdtt-metabasic select").removeOption(/./);
            jQuery("#gdtt-metabasic select").addOption("__none__", gdCPTTools.txt_select_field);

            jQuery.each(gdCPTAdmin.tmp.custom_fields, function(idx, val){
                jQuery("#gdtt-metabasic select").addOption(idx, val.name);
            });
        },
        update_boxes_select: function() {
            jQuery("#gdtt-metagroupbasic select").removeOption(/./);
            jQuery("#gdtt-metagroupbasic select").addOption("__none__", gdCPTTools.txt_select_box);

            jQuery.each(gdCPTAdmin.tmp.meta_boxes, function(idx, val){
                jQuery("#gdtt-metagroupbasic select").addOption(idx, val.name);
            });
        },
        load_meta_box_group: function(code) {
            jQuery("#gdtt-mbg-code").attr("readonly", true).val(code);
            jQuery("#gdtt-mbg-name").val(gdCPTAdmin.tmp.meta_box_groups[code].name);
            jQuery("#gdtt-mbg-location").val(gdCPTAdmin.tmp.meta_box_groups[code].location);

            jQuery("#gdtt-mbg-user_access").val(gdCPTAdmin.tmp.meta_box_groups[code].user_access);
            jQuery("#gdtt-mbg-user_roles").val(gdCPTAdmin.tmp.meta_box_groups[code].user_roles);
            jQuery("#gdtt-mbg-user_caps").val(gdCPTAdmin.tmp.meta_box_groups[code].user_caps);

            var i;
            var boxes = gdCPTAdmin.tmp.meta_box_groups[code].boxes;
            jQuery("#gdtt-metaboxes").html("");
            for (i = 0; i < boxes.length; i++) {
                var item = jQuery("#gdtt-metagroupbasic li").clone().show();
                item.find("select").val(boxes[i]);
                jQuery("#gdtt-metaboxes").append(item);
            }
            jQuery("#gdtt-metaboxes li:first .gdr2-metafield-group-buttons div:last").hide();

            gdCPTAdmin.editor.boxes_dnd();
        },
        load_meta_box: function(code) {
            if (!gdCPTAdmin.tmp.meta_boxes[code].repeater) {
                gdCPTAdmin.tmp.meta_boxes[code].repeater = "no";
            }

            if (!gdCPTAdmin.tmp.meta_boxes[code].location) {
                gdCPTAdmin.tmp.meta_boxes[code].location = "advanced";
            }

            jQuery("#gdtt-mbe-code").attr("readonly", true).val(code);
            jQuery("#gdtt-mbe-name").val(gdCPTAdmin.tmp.meta_boxes[code].name);
            jQuery("#gdtt-mbe-location").val(gdCPTAdmin.tmp.meta_boxes[code].location);
            jQuery("#gdtt-mbe-repeater").val(gdCPTAdmin.tmp.meta_boxes[code].repeater);
            jQuery("#gdtt-mbe-description").val(gdCPTAdmin.tmp.meta_boxes[code].description);

            jQuery("#gdtt-mbe-user_access").val(gdCPTAdmin.tmp.meta_boxes[code].user_access);
            jQuery("#gdtt-mbe-user_roles").val(gdCPTAdmin.tmp.meta_boxes[code].user_roles);
            jQuery("#gdtt-mbe-user_caps").val(gdCPTAdmin.tmp.meta_boxes[code].user_caps);

            var i;
            var fields = gdCPTAdmin.tmp.meta_boxes[code].fields;
            jQuery("#gdtt-metafields").html("");
            for (i = 0; i < fields.length; i++) {
                var item = jQuery("#gdtt-metabasic li").clone().show();
                item.find("select").val(fields[i]);
                jQuery("#gdtt-metafields").append(item);
            }
            jQuery("#gdtt-metafields li:first .gdr2-metafield-buttons div:last").hide();

            gdCPTAdmin.editor.fields_dnd();
        },
        init: function() {
            jQuery(".gdtt-addnew-small").button({
                icons: {
                    primary: "ui-icon-plus"
                },
                text: false
            });

            jQuery("#gdtt-cfe-limit").numeric({decimal: false, negative: false});

            jQuery("#gdttcfdelete").next().append('<div id="gdtt-cfd-infopane" class="ui-dialog-infopane"></div>');
            jQuery("#gdttcfedit").next().append('<div id="gdtt-cfe-infopane" class="ui-dialog-infopane"></div>');

            jQuery("#gdttmbdelete").next().append('<div id="gdtt-mbd-infopane" class="ui-dialog-infopane"></div>');
            jQuery("#gdttmbedit").next().append('<div id="gdtt-mbe-infopane" class="ui-dialog-infopane"></div>');
            jQuery("#gdttmbptypes").next().append('<div id="gdtt-mbp-infopane" class="ui-dialog-infopane"></div>');

            jQuery("#gdttmbgdelete").next().append('<div id="gdtt-mbgd-infopane" class="ui-dialog-infopane"></div>');
            jQuery("#gdttmbgedit").next().append('<div id="gdtt-mbge-infopane" class="ui-dialog-infopane"></div>');
            jQuery("#gdttmbgptypes").next().append('<div id="gdtt-mbgp-infopane" class="ui-dialog-infopane"></div>');

            jQuery(document).on("click", ".gdtt-mbo-edit", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_editor = "edit";
                var to_edit = jQuery(this).attr("href").substr(1);

                gdCPTAdmin.editor.load_meta_box(to_edit);

                jQuery("#gdttmbedit").dialog("open");
            });

            jQuery(document).on("click", ".gdtt-mbg-edit", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_group_editor = "edit";
                var to_edit = jQuery(this).attr("href").substr(1);

                gdCPTAdmin.editor.load_meta_box_group(to_edit);

                jQuery("#gdttmbgedit").dialog("open");
            });

            jQuery(document).on("change", "#gdtt-cfe-selmethod", function() {
                var method = jQuery(this).val();

                jQuery(".gdtt-select-list").hide();
                jQuery(".gdtt-select-" + method).show();
            });

            jQuery(document).on("click", ".gdtt-cfo-copy, .gdtt-cfo-edit", function(e) {
                e.preventDefault();

                var copy = jQuery(this).hasClass("gdtt-cfo-copy");
                gdCPTAdmin.tmp.custom_field_editor = copy ? "new" : "edit";
                var to_edit = jQuery(this).attr("href").substr(1);

                jQuery("#gdtt-cfe-code").attr("readonly", !copy).val(to_edit);

                jQuery("#gdtt-cfe-type").val(gdCPTAdmin.tmp.custom_fields[to_edit].type);
                jQuery("#gdtt-cfe-name").val(gdCPTAdmin.tmp.custom_fields[to_edit].name);
                jQuery("#gdtt-cfe-selection").val(gdCPTAdmin.tmp.custom_fields[to_edit].selection);
                jQuery("#gdtt-cfe-selmethod").val(gdCPTAdmin.tmp.custom_fields[to_edit].selmethod);
                jQuery("#gdtt-cfe-description").val(gdCPTAdmin.tmp.custom_fields[to_edit].description);
                jQuery("#gdtt-cfe-function").val(gdCPTAdmin.tmp.custom_fields[to_edit].fnc_name);

                jQuery("#gdtt-cfe-user_access").val(gdCPTAdmin.tmp.custom_fields[to_edit].user_access);
                jQuery("#gdtt-cfe-user_roles").val(gdCPTAdmin.tmp.custom_fields[to_edit].user_roles);
                jQuery("#gdtt-cfe-user_caps").val(gdCPTAdmin.tmp.custom_fields[to_edit].user_caps);

                jQuery(".gdtt-element-field").hide();
                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "select") {
                    jQuery(".gdtt-element-values").show();
                    jQuery("#gdtt-cfe-selmethod").trigger("change");
                }

                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "date" || gdCPTAdmin.tmp.custom_fields[to_edit].type === "month" || gdCPTAdmin.tmp.custom_fields[to_edit].type === "date_time" || gdCPTAdmin.tmp.custom_fields[to_edit].type === "time") {
                    jQuery(".gdtt-element-date").show();
                    jQuery("#gdtt-cfe-format").val(gdCPTAdmin.tmp.custom_fields[to_edit].format);
                    jQuery("#gdtt-cfe-datesave").val(gdCPTAdmin.tmp.custom_fields[to_edit].datesave);
                }

                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "text" || gdCPTAdmin.tmp.custom_fields[to_edit].type === "html" || gdCPTAdmin.tmp.custom_fields[to_edit].type === "rewrite") {
                    jQuery(".gdtt-element-limit").show();
                    jQuery("#gdtt-cfe-limit").val(gdCPTAdmin.tmp.custom_fields[to_edit].limit);
                }

                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "rewrite") {
                    jQuery(".gdtt-element-rewrite").show();
                    jQuery("#gdtt-cfe-rewrite").val(gdCPTAdmin.tmp.custom_fields[to_edit].rewrite);
                }

                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "unit") {
                    jQuery(".gdtt-element-unit").show();
                    jQuery("#gdtt-cfe-unit").val(gdCPTAdmin.tmp.custom_fields[to_edit].unit);
                }

                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "text") {
                    jQuery(".gdtt-element-regex").show();
                    jQuery("#gdtt-cfe-regex").val(gdCPTAdmin.tmp.custom_fields[to_edit].regex);
                    jQuery("#gdtt-cfe-regex_custom").val(gdCPTAdmin.tmp.custom_fields[to_edit].regex_custom);
                    jQuery("#gdtt-cfe-mask_custom").val(gdCPTAdmin.tmp.custom_fields[to_edit].mask_custom);
                }

                if (gdCPTAdmin.tmp.custom_fields[to_edit].type === "select") {
                    jQuery("#gdtt-cfe-values").val(gdCPTAdmin.tmp.custom_fields[to_edit].values.join("\n"));
                    jQuery("#gdtt-cfe-assoc-values").val(gdCPTAdmin.tmp.custom_fields[to_edit].assoc_values.join("\n"));

                    jQuery("#gdtt-cfe-selmethod").trigger("change");
                }

                if (gdCPTAdmin.meta[gdCPTAdmin.tmp.custom_fields[to_edit].type]) {
                    gdCPTAdmin.meta[gdCPTAdmin.tmp.custom_fields[to_edit].type].edit(to_edit);
                }

                jQuery("#gdtt-cfe-required").removeAttr("checked");

                if (gdCPTAdmin.tmp.custom_fields[to_edit].required) {
                    jQuery("#gdtt-cfe-required").attr("checked", "checked");
                }

                jQuery("#gdttcfedit").dialog("open");
            });

            jQuery(document).on("click", ".gdtt-mbo-postypes", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_to_delete = jQuery(this).attr("href").substr(1);
                jQuery("#gdtt-ptypesfields").html("");

                if (gdCPTAdmin.tmp.post_types_map.hasOwnProperty(gdCPTAdmin.tmp.meta_box_to_delete) && gdCPTAdmin.tmp.post_types_map[gdCPTAdmin.tmp.meta_box_to_delete].length === 0) {
                    jQuery("#gdtt-ptypesfields").append(jQuery("#gdtt-ptypesbasic li").clone());
                } else {
                    jQuery.each(gdCPTAdmin.tmp.post_types_map[gdCPTAdmin.tmp.meta_box_to_delete], function(idx, val){
                        var row = jQuery("#gdtt-ptypesbasic li").clone();
                        row.find("select").val(val);
                        jQuery("#gdtt-ptypesfields").append(row);
                    });
                }

                jQuery("#gdttmbptypes").dialog("open");
            });

            jQuery(document).on("click", "#gdtt-ptypesfields li span.ui-icon-minus", function(){
                jQuery(this).parent().parent().parent().fadeOut("slow", function(){jQuery(this).remove();});
            });

            jQuery(document).on("click", "#gdtt-ptypesfields li span.ui-icon-plus", function(){
                var item = jQuery("#gdtt-ptypesbasic li").clone().hide().fadeIn("slow");
                jQuery(this).parent().parent().parent().after(item);
            });

            jQuery(document).on("click", ".gdtt-cfo-delete", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.custom_field_to_delete = jQuery(this).attr("href").substr(1);
                jQuery("#gdttcfdelete").dialog("open");
            });

            jQuery(document).on("click", ".gdtt-mbg-delete", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_group_to_delete = jQuery(this).attr("href").substr(1);
                jQuery("#gdttmbgdelete").dialog("open");
            });

            jQuery(document).on("click", ".gdtt-mbo-delete", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_to_delete = jQuery(this).attr("href").substr(1);
                jQuery("#gdttmbdelete").dialog("open");
            });

            jQuery(document).on("click", "#gdtt-metafields li span.ui-icon-minus", function(){
                jQuery(this).parent().parent().parent().fadeOut("slow", function(){jQuery(this).remove();});
                gdCPTAdmin.editor.fields_dnd();
            });

            jQuery(document).on("click", "#gdtt-metaboxes li span.ui-icon-plus", function(){
                var item = jQuery("#gdtt-metagroupbasic li").clone().hide().fadeIn("slow");
                jQuery(this).parent().parent().parent().after(item);
                gdCPTAdmin.editor.boxes_dnd();
            });

            jQuery(document).on("click", "#gdtt-metaboxes li span.ui-icon-minus", function(){
                jQuery(this).parent().parent().parent().fadeOut("slow", function(){jQuery(this).remove();});
                gdCPTAdmin.editor.boxes_dnd();
            });

            jQuery(document).on("click", ".gdtt-mbg-postypes", function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_group_to_delete = jQuery(this).attr("href").substr(1);
                jQuery("#gdtt-ptypesfields-group").html("");

                if (gdCPTAdmin.tmp.post_types_map_groups.hasOwnProperty(gdCPTAdmin.tmp.meta_box_group_to_delete) && gdCPTAdmin.tmp.post_types_map_groups[gdCPTAdmin.tmp.meta_box_group_to_delete].length === 0) {
                    jQuery("#gdtt-ptypesfields-group").append(jQuery("#gdtt-ptypesbasic-group li").clone());
                } else {
                    jQuery.each(gdCPTAdmin.tmp.post_types_map_groups[gdCPTAdmin.tmp.meta_box_group_to_delete], function(idx, val){
                        var row = jQuery("#gdtt-ptypesbasic-group li").clone();
                        row.find("select").val(val);
                        jQuery("#gdtt-ptypesfields-group").append(row);
                    });
                }

                jQuery("#gdttmbgptypes").dialog("open");
            });

            jQuery(document).on("click", "#gdtt-ptypesfields-group li span.ui-icon-plus", function(){
                var item = jQuery("#gdtt-ptypesbasic-group li").clone().hide().fadeIn("slow");
                jQuery(this).parent().parent().parent().after(item);
            });

            jQuery(document).on("click", "#gdtt-ptypesfields-group li span.ui-icon-minus", function(){
                jQuery(this).parent().parent().parent().fadeOut("slow", function(){jQuery(this).remove();});
            });

            jQuery(document).on("click", "#gdtt-metafields li span.ui-icon-plus", function(){
                var item = jQuery("#gdtt-metabasic li").clone().hide().fadeIn("slow");
                jQuery(this).parent().parent().parent().after(item);
                gdCPTAdmin.editor.fields_dnd();
            });

            jQuery("#gdtt-mbg-addnew, #gdtt-mbg-addnew-small").click(function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_group_editor = "new";

                jQuery("#gdtt-mbg-code").attr("readonly", false).val("");
                jQuery("#gdtt-mbg-name").val("");
                jQuery("#gdtt-mbg-location").val("normal");
                jQuery("#gdtt-mbg-user_access").val("none");
                jQuery("#gdtt-mbg-user_roles").val("");
                jQuery("#gdtt-mbg-user_caps").val("");
                jQuery("#gdtt-metaboxes").html("").append(jQuery("#gdtt-metagroupbasic li").clone());
                jQuery("#gdtt-metaboxes li:first .gdr2-metafield-group-buttons div:last").hide();

                jQuery("#gdttmbgedit").dialog("open");
                gdCPTAdmin.editor.boxes_dnd();
            });

            jQuery("#gdtt-mbe-addnew, #gdtt-mbe-addnew-small").click(function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.meta_box_editor = "new";

                jQuery("#gdtt-mbe-code").attr("readonly", false).val("");
                jQuery("#gdtt-mbe-name").val("");
                jQuery("#gdtt-mbe-location").val("normal");
                jQuery("#gdtt-mbe-repeater").val("no");
                jQuery("#gdtt-mbe-description").val("");
                jQuery("#gdtt-mbe-user_access").val("none");
                jQuery("#gdtt-mbe-user_roles").val("");
                jQuery("#gdtt-mbe-user_caps").val("");
                jQuery("#gdtt-metafields").html("").append(jQuery("#gdtt-metabasic li").clone());
                jQuery("#gdtt-metafields li:first .gdr2-metafield-buttons div:last").hide();

                jQuery("#gdttmbedit").dialog("open");
                gdCPTAdmin.editor.fields_dnd();
            });

            jQuery("#gdtt-cfe-addnew, #gdtt-cfe-addnew-small").click(function(e){
                e.preventDefault();

                gdCPTAdmin.tmp.custom_field_editor = "new";

                jQuery(".gdtt-element-values").hide();

                jQuery("#gdtt-cfe-type").val("text");
                jQuery("#gdtt-cfe-code").attr("readonly", false).val("");
                jQuery("#gdtt-cfe-name").val("");
                jQuery("#gdtt-cfe-selection").val("select");
                jQuery("#gdtt-cfe-selmethod").val("normal");
                jQuery("#gdtt-cfe-description").val("");
                jQuery("#gdtt-cfe-taxname").val("");
                jQuery("#gdtt-cfe-values").val("");
                jQuery("#gdtt-cfe-assoc-values").val("");
                jQuery("#gdtt-cfe-function").val("__none__");
                jQuery("#gdtt-cfe-required").removeAttr("checked");
                jQuery("#gdtt-cfe-limit").val("0");
                jQuery("#gdtt-cfe-format").val("");
                jQuery("#gdtt-cfe-datesave").val("dashed");
                jQuery("#gdtt-cfe-rewrite").val("__none__");
                jQuery("#gdtt-cfe-regex").val("__none__");
                jQuery("#gdtt-cfe-regex_custom").val("");
                jQuery("#gdtt-cfe-mask_custom").val("");
                jQuery("#gdtt-cfe-user_access").val("none");
                jQuery("#gdtt-cfe-user_roles").val("");
                jQuery("#gdtt-cfe-user_caps").val("");
                jQuery("#gdtt-cfe-unit").val("memory");

                jQuery("#gdtt-cfe-type").trigger("change");
                jQuery("#gdtt-cfe-selmethod").trigger("change");

                jQuery("#gdttcfedit").dialog("open");
            });

            jQuery("#gdtt-cfe-type").change(function(){
                var field = jQuery(this).val();
                jQuery(".gdtt-element-field").hide();

                if (field === "select" || field === "radio" || field === "checkbox") {
                    jQuery(".gdtt-element-values").show();
                }

                if (field === "date" || field === "month" || field === "date_time" || field === "time") {
                    jQuery(".gdtt-element-date").show();
                }

                if (field === "unit") {
                    jQuery(".gdtt-element-unit").show();
                }

                if (field === "text" || field === "html" || field === "rewrite") {
                    jQuery(".gdtt-element-limit").show();
                }

                if (field === "rewrite") {
                    jQuery(".gdtt-element-rewrite").show();
                }

                if (field === "text") {
                    jQuery(".gdtt-element-regex").show();
                }

                if (gdCPTAdmin.meta[field]) {
                    gdCPTAdmin.meta[field].change();
                }
            });
        },
        boxes_dnd: function() {
            jQuery("#gdtt-metaboxes").sortable({placeholder: "ui-state-highlight", handle: ".gdtt-field-drag",
                stop: function(event, ui){
                    jQuery("#gdtt-metaboxes li .gdr2-metafield-group-buttons div").show();
                    jQuery("#gdtt-metaboxes li:first .gdr2-metafield-group-buttons div:last").hide();
                }
            });
        },
        fields_dnd: function() {
            jQuery("#gdtt-metafields").sortable({placeholder: "ui-state-highlight", handle: ".gdtt-field-drag",
                stop: function(event, ui){
                    jQuery("#gdtt-metafields li .gdr2-metafield-buttons div").show();
                    jQuery("#gdtt-metafields li:first .gdr2-metafield-buttons div:last").hide();
                }
            });
        },
        show_info: function(id, info) {
            jQuery("#" + id).fadeIn().html(info);
        },
        hide_info: function(id) {
            jQuery("#" + id).fadeOut("slow").html("");
        },
        add_metabox_group: function() {
            gdCPTAdmin.editor.hide_info("gdtt-mbge-infopane");
            var group = {method:  gdCPTAdmin.tmp.meta_box_group_editor,
                       code: jQuery("#gdtt-mbg-code").val().trim(),
                       name: jQuery("#gdtt-mbg-name").val().trim(),
                       location: jQuery("#gdtt-mbg-location").val().trim(),
                       user_access: jQuery("#gdtt-mbg-user_access").val().trim(),
                       user_roles: jQuery("#gdtt-mbg-user_roles").val().trim(),
                       user_caps: jQuery("#gdtt-mbg-user_caps").val().trim(),
                       boxes: []};
            jQuery("#gdtt-metaboxes li select").each(function(idx, el){
                var value = jQuery(this).val();

                if (value !== "__none__") {
                    group.boxes[group.boxes.length] = value;
                }
            });

            if (group.code === "" || group.name === "" || group.boxes.length === 0) {
                gdCPTAdmin.editor.show_info("gdtt-mbge-infopane", gdCPTTools.txt_editor_group_missing);
            } else {
                jQuery.ajax({
                    success: function(json) {
                        if (json.status === "error") {
                            gdCPTAdmin.editor.show_info("gdtt-mbge-infopane", json.error);
                        } else {
                            var first = gdCPTAdmin.tmp.meta_box_groups_count === 0;
                            gdCPTAdmin.tmp.meta_box_groups[json.group.code] = jQuery.extend(true, {}, json.group);
                            gdCPTAdmin.tmp.post_types_map_groups[json.group.code] = [];

                            var boxes = [];
                            var ptypes = [];
                            jQuery.each(gdCPTAdmin.tmp.meta_box_groups[json.group.code].boxes, function(idx, value){
                                var f = gdCPTAdmin.tmp.meta_boxes[value];
                                boxes[boxes.length] = f.name;
                            });
                            jQuery.each(json.map, function(idx, value){
                                var p = gdCPTAdmin.tmp.post_types[value];
                                ptypes[ptypes.length] = p + " (<strong>" + value + "</strong>)";
                            });
                            json.group.post_types = ptypes.length === 0 ? "/" : ptypes.join("<br/>");
                            json.group.boxes = boxes.join("<br/>");

                            var row = gdCPTAdmin.tpl_render(gdCPTAdmin.tpl.mbg_row, json.group);
                            if (gdCPTAdmin.tmp.meta_box_group_editor === "new") {
                                if (first) {jQuery("#list-groups").html("");}
                                jQuery("#list-groups").append(row);
                                gdCPTAdmin.tmp.meta_box_groups_count++;
                            } else {
                                jQuery(".gdtt-mbgrow-" + json.group.code).replaceWith(row);
                            }

                            jQuery("#gdttmbgedit").dialog("close");
                        }
                    }, dataType: "json", data: group, type: "POST",
                    url: ajaxurl + "?action=gd_cpt_meta_add_metabox_group&_ajax_nonce=" + gdCPTTools.nonce
                });
            }
        },
        delete_metabox_group: function() {
            var del_data = {code: gdCPTAdmin.tmp.meta_box_group_to_delete,
                            definition: jQuery('#gdtt-mbg-del-definition').is(":checked") ? "1" : "0"};

            if (del_data.definition === "1") {
                gdCPTAdmin.editor.hide_info("gdtt-mbgd-infopane");
                jQuery.ajax({
                    success: function(json) {
                        if (json.del_row === "yes") {
                            gdCPTAdmin.tmp.meta_box_groups_count--;
                            jQuery(".gdtt-mbgrow-" + gdCPTAdmin.tmp.meta_box_group_to_delete).fadeOut("slow").remove();
                        }

                        jQuery("#gdttmbgdelete").dialog("close");
                    }, dataType: "json", data: del_data, type: "POST",
                    url: 'admin-ajax.php?action=gd_cpt_meta_delete_metabox_group&_ajax_nonce=' + gdCPTTools.nonce
                });
            } else {
                gdCPTAdmin.editor.show_info("gdtt-mbgd-infopane", gdCPTTools.txt_editor_nothing);
            }
        },
        clear_group_posttypes: function() {
            gdCPTAdmin.editor.hide_info("gdtt-mbgp-infopane");

            jQuery.ajax({
                success: function(json) {
                    gdCPTAdmin.tmp.post_types_map_groups[json.code] = json.map;

                    jQuery(".gdtt-mbgrow-" + gdCPTAdmin.tmp.meta_box_to_delete + " .gdtt-post-types").html("/");
                    jQuery("#gdttmbgptypes").dialog("close");
                }, dataType: "json", data: {code: gdCPTAdmin.tmp.meta_box_group_to_delete}, type: "POST",
                url: ajaxurl + "?action=gd_cpt_meta_clear_metabox_group&_ajax_nonce=" + gdCPTTools.nonce
            });
        },
        attach_group_posttypes: function() {
            gdCPTAdmin.editor.hide_info("gdtt-mbgp-infopane");
            var metas = {code: gdCPTAdmin.tmp.meta_box_group_to_delete, post_types: []};

            jQuery("#gdtt-ptypesfields-group li select").each(function(idx, el){
                var value = jQuery(this).val();

                if (value !== "__none__") {
                    metas.post_types[metas.post_types.length] = value;
                }
            });

            jQuery.ajax({
                success: function(json) {
                    var ptypes = [];
                    jQuery.each(json.map, function(idx, value){
                        var p = gdCPTAdmin.tmp.post_types[value];
                        ptypes[ptypes.length] = p + " (<strong>" + value + "</strong>)";
                    });

                    gdCPTAdmin.tmp.post_types_map_groups[json.code] = json.map;

                    jQuery(".gdtt-mbgrow-" + gdCPTAdmin.tmp.meta_box_group_to_delete + " .gdtt-post-types").html(ptypes.join("<br/>"));
                    jQuery("#gdttmbgptypes").dialog("close");
                }, dataType: "json", data: metas, type: "POST",
                url: ajaxurl + "?action=gd_cpt_meta_attach_metabox_group&_ajax_nonce=" + gdCPTTools.nonce
            });
        },
        clear_posttypes: function() {
            gdCPTAdmin.editor.hide_info("gdtt-mbp-infopane");

            jQuery.ajax({
                success: function(json) {
                    gdCPTAdmin.tmp.post_types_map[json.code] = json.map;

                    jQuery(".gdtt-mbrow-" + gdCPTAdmin.tmp.meta_box_to_delete + " .gdtt-post-types").html("/");
                    jQuery("#gdttmbptypes").dialog("close");
                }, dataType: "json", data: {code: gdCPTAdmin.tmp.meta_box_to_delete}, type: "POST",
                url: ajaxurl + "?action=gd_cpt_meta_clear_metabox&_ajax_nonce=" + gdCPTTools.nonce
            });
        },
        attach_posttypes: function() {
            gdCPTAdmin.editor.hide_info("gdtt-mbp-infopane");
            var metas = {code: gdCPTAdmin.tmp.meta_box_to_delete, post_types: []};

            jQuery("#gdtt-ptypesfields li select").each(function(idx, el){
                var value = jQuery(this).val();

                if (value !== "__none__") {
                    metas.post_types[metas.post_types.length] = value;
                }
            });

            if (metas.post_types.length > 0) {
                jQuery.ajax({
                    success: function(json) {
                        var ptypes = [];
                        jQuery.each(json.map, function(idx, value){
                            var p = gdCPTAdmin.tmp.post_types[value];
                            ptypes[ptypes.length] = p + " (<strong>" + value + "</strong>)";
                        });

                        gdCPTAdmin.tmp.post_types_map[json.code] = json.map;

                        jQuery(".gdtt-mbrow-" + gdCPTAdmin.tmp.meta_box_to_delete + " .gdtt-post-types").html(ptypes.join("<br/>"));
                        jQuery("#gdttmbptypes").dialog("close");
                    }, dataType: "json", data: metas, type: "POST",
                    url: ajaxurl + "?action=gd_cpt_meta_attach_metabox&_ajax_nonce=" + gdCPTTools.nonce
                });
            } else {
                gdCPTAdmin.editor.show_info("gdtt-mbp-infopane", gdCPTTools.txt_editor_nothing);
            }
        },
        add_metabox: function() {
            gdCPTAdmin.editor.hide_info("gdtt-mbe-infopane");
            var box = {method:  gdCPTAdmin.tmp.meta_box_editor,
                       code: jQuery("#gdtt-mbe-code").val().trim(),
                       name: jQuery("#gdtt-mbe-name").val().trim(),
                       location: jQuery("#gdtt-mbe-location").val().trim(),
                       repeater: jQuery("#gdtt-mbe-repeater").val().trim(),
                       description: jQuery("#gdtt-mbe-description").val().trim(),
                       user_access: jQuery("#gdtt-mbe-user_access").val().trim(),
                       user_roles: jQuery("#gdtt-mbe-user_roles").val().trim(),
                       user_caps: jQuery("#gdtt-mbe-user_caps").val().trim(),
                       fields: []};
            jQuery("#gdtt-metafields li select").each(function(idx, el){
                var value = jQuery(this).val();

                if (value !== "__none__") {
                    box.fields[box.fields.length] = value;
                }
            });

            if (box.code === "" || box.name === "" || box.fields.length === 0) {
                gdCPTAdmin.editor.show_info("gdtt-mbe-infopane", gdCPTTools.txt_editor_box_missing);
            } else {
                jQuery.ajax({
                    success: function(json) {
                        if (json.status === "error") {
                            gdCPTAdmin.editor.show_info("gdtt-mbe-infopane", json.error);
                        } else {
                            var first = gdCPTAdmin.tmp.meta_boxes_count === 0;
                            gdCPTAdmin.tmp.meta_boxes[json.box.code] = jQuery.extend(true, {}, json.box);
                            gdCPTAdmin.tmp.post_types_map[json.box.code] = [];
                            gdCPTAdmin.editor.update_boxes_select();

                            var fields = [];
                            var ptypes = [];
                            jQuery.each(gdCPTAdmin.tmp.meta_boxes[json.box.code].fields, function(idx, value){
                                var f = gdCPTAdmin.tmp.custom_fields[value];
                                fields[fields.length] = f.name + " (<strong>" + f.code + "</strong>: " + ucfirst(f.type) + ")";
                            });

                            jQuery.each(json.map, function(idx, value){
                                var p = gdCPTAdmin.tmp.post_types[value];
                                ptypes[ptypes.length] = p + " (<strong>" + value + "</strong>)";
                            });

                            json.box.post_types = ptypes.length === 0 ? "/" : ptypes.join("<br/>");
                            json.box.fields = fields.join("<br/>");

                            var row = gdCPTAdmin.tpl_render(gdCPTAdmin.tpl.mbe_row, json.box);
                            if (gdCPTAdmin.tmp.meta_box_editor === "new") {
                                if (first) {jQuery("#list-boxes").html("");}
                                jQuery("#list-boxes").append(row);
                                gdCPTAdmin.tmp.meta_boxes_count++;
                            } else {
                                jQuery(".gdtt-mbrow-" + json.box.code).replaceWith(row);
                            }

                            jQuery("#gdttmbedit").dialog("close");
                        }
                    }, dataType: "json", data: box, type: "POST",
                    url: ajaxurl + "?action=gd_cpt_meta_add_metabox&_ajax_nonce=" + gdCPTTools.nonce
                });
            }
        },
        delete_metabox: function() {
            var del_data = {code: gdCPTAdmin.tmp.meta_box_to_delete,
                            definition: jQuery("#gdtt-mbe-del-definition").is(":checked") ? "1" : "0"};

            if (del_data.definition === "1") {
                gdCPTAdmin.editor.hide_info("gdtt-mbd-infopane");
                jQuery.ajax({
                    success: function(json) {
                        if (json.del_row === "yes") {
                            gdCPTAdmin.tmp.meta_boxes_count--;
                            jQuery(".gdtt-mbrow-" + gdCPTAdmin.tmp.meta_box_to_delete).fadeOut("slow").remove();
                        }
                        jQuery("#gdttmbdelete").dialog("close");
                    }, dataType: "json", data: del_data, type: "POST",
                    url: "admin-ajax.php?action=gd_cpt_meta_delete_metabox&_ajax_nonce=" + gdCPTTools.nonce
                });
            } else {
                gdCPTAdmin.editor.show_info("gdtt-mbd-infopane", gdCPTTools.txt_editor_nothing);
            }
        },
        delete_field: function() {
            var del_data = {code: gdCPTAdmin.tmp.custom_field_to_delete,
                            definition: jQuery("#gdtt-cfe-del-definition").is(":checked") ? "1" : "0", 
                            data: jQuery("#gdtt-cfe-del-postmeta").is(":checked") ? "1" : "0"};

            if (del_data.data === "1" || del_data.definition === "1") {
                gdCPTAdmin.editor.hide_info("gdtt-cfd-infopane");
                jQuery.ajax({
                    success: function(json) {
                        if (json.del_row === "yes") {
                            gdCPTAdmin.tmp.custom_fields_count--;
                            delete gdCPTAdmin.tmp.custom_fields[gdCPTAdmin.tmp.custom_field_to_delete];
                            gdCPTAdmin.editor.update_fields_select();
                            jQuery(".gdtt-cfrow-" + gdCPTAdmin.tmp.custom_field_to_delete).fadeOut("slow").remove();
                        }
                        jQuery("#gdttcfdelete").dialog("close");
                    }, dataType: "json", data: del_data, type: "POST",
                    url: ajaxurl + "?action=gd_cpt_meta_delete_field&_ajax_nonce=" + gdCPTTools.nonce
                });
            } else {
                gdCPTAdmin.editor.show_info("gdtt-cfd-infopane", gdCPTTools.txt_editor_nothing);
            }
        },
        add_field: function() {
            gdCPTAdmin.editor.hide_info("gdtt-cfe-infopane");
            var field = {method:  gdCPTAdmin.tmp.custom_field_editor,
                         type: jQuery("#gdtt-cfe-type").val().trim(),
                         code: jQuery("#gdtt-cfe-code").val().trim(),
                         name: jQuery("#gdtt-cfe-name").val().trim(),
                         required: jQuery("#gdtt-cfe-required").is(":checked"),
                         user_access: jQuery("#gdtt-cfe-user_access").val().trim(),
                         user_roles: jQuery("#gdtt-cfe-user_roles").val().trim(),
                         user_caps: jQuery("#gdtt-cfe-user_caps").val().trim(),
                         selection: jQuery("#gdtt-cfe-selection").val().trim(),
                         selmethod: jQuery("#gdtt-cfe-selmethod").val().trim(),
                         fnc_name: jQuery("#gdtt-cfe-function").val().trim(),
                         description: jQuery("#gdtt-cfe-description").val().trim(),
                         values: jQuery("#gdtt-cfe-values").val().trim(),
                         limit: jQuery("#gdtt-cfe-limit").val().trim(),
                         format: jQuery("#gdtt-cfe-format").val().trim(),
                         datesave: jQuery("#gdtt-cfe-datesave").val().trim(),
                         rewrite: jQuery("#gdtt-cfe-rewrite").val().trim(),
                         unit: jQuery("#gdtt-cfe-unit").val().trim(),
                         regex: jQuery("#gdtt-cfe-regex").val().trim(),
                         regex_custom: jQuery("#gdtt-cfe-regex_custom").val().trim(),
                         mask_custom: jQuery("#gdtt-cfe-mask_custom").val().trim(),
                         assoc_values: jQuery("#gdtt-cfe-assoc-values").val().trim()};

            if (field.code === "" || field.name === "") {
                gdCPTAdmin.editor.show_info("gdtt-cfe-infopane", gdCPTTools.txt_editor_field_missing);
            } else {
                if (gdCPTAdmin.meta[field.type]) {
                    field = gdCPTAdmin.meta[field.type].save(field);
                }

                jQuery.ajax({
                    success: function(json) {
                        jQuery("#gdtt-cfe-type").val(json.field.type);
                        jQuery("#gdtt-cfe-code").val(json.field.code);
                        jQuery("#gdtt-cfe-name").val(json.field.name);
                        jQuery("#gdtt-cfe-function").val(json.field.fnc_name);
                        jQuery("#gdtt-cfe-description").val(json.field.description);

                        jQuery("#gdtt-cfe-required").removeAttr("checked");
                        if (json.field.required) {
                            jQuery("#gdtt-cfe-required").attr("checked", "checked");
                        }

                        if (json.status === "error") {
                            gdCPTAdmin.editor.show_info("gdtt-cfe-infopane", json.error);
                        } else {
                            var first = gdCPTAdmin.tmp.custom_fields_count === 0;
                            gdCPTAdmin.tmp.custom_fields[json.field.code] = jQuery.extend(true, {}, json.field);
                            gdCPTAdmin.editor.update_fields_select();

                            if (json.field.type === "boolean") {
                                json.field.values = "true<br/>false";
                            } else if (json.field.type === "function") {
                                json.field.values = "$" + json.field.fnc_name + "()";
                            } else if (json.field.type === "select" || json.field.type === "radio" || json.field.type === "checkbox") {
                                json.field.values = json.field.values.join("<br/>");

                                if (json.field.values === "") {
                                    json.field.values = json.field.assoc_values.join("<br/>");
                                }
                            } else { 
                                json.field.values = "/";
                            }

                            json.field.type = ucfirst(json.field.type);
                            var row = gdCPTAdmin.tpl_render(gdCPTAdmin.tpl.cfe_row, json.field);

                            if (gdCPTAdmin.tmp.custom_field_editor === "new") {
                                if (first) {
                                    jQuery("#list-fields").html("");
                                }

                                jQuery("#list-fields").append(row);
                                gdCPTAdmin.tmp.custom_fields_count++;
                            } else {
                                jQuery(".gdtt-cfrow-" + json.field.code).replaceWith(row);
                            }

                            jQuery("#gdttcfedit").dialog("close");
                        }
                    }, dataType: "json", data: field, type: "POST",
                    url: ajaxurl + "?action=gd_cpt_meta_add_field&_ajax_nonce=" + gdCPTTools.nonce
                });
            }
        }
    },
    save_cpt_quick: function() {
        jQuery("#gdr2dialog_cpt_full .edit-again").remove();
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            gdCPTAdmin.ajax("gdr2dialog_cpt_full");
        });
    },
    save_cpt_full: function(cpt, id, name, dialog) {
        var url = jQuery("#" + dialog + " .edit-again").attr("href") + cpt;
        if (cpt === 1) {url+= "&pid=" + id;} else {url+= "&pname=" + name;}
        jQuery("#" + dialog + " .edit-again").attr("href", url);
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            gdCPTAdmin.ajax(dialog);
        });
    },
    save_cpt_simple: function(name, dialog) {
        var url = jQuery("#" + dialog + " .edit-again").attr("href");
        jQuery("#" + dialog + " .edit-again").attr("href", url + name);
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            gdCPTAdmin.ajax(dialog);
        });
    },
    save_tax_quick: function() {
        jQuery("#gdr2dialog_tax_full .edit-again").remove();
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            gdCPTAdmin.ajax("gdr2dialog_tax_full");
        });
    },
    save_tax_full: function(cpt, id, name, dialog) {
        var url = jQuery("#" + dialog + " .edit-again").attr("href") + cpt;
        if (cpt === 1) {url+= "&tid=" + id;} else {url+= "&tname=" + name;}
        jQuery("#" + dialog + " .edit-again").attr("href", url);
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            gdCPTAdmin.ajax(dialog);
        });
    },
    save_tax_simple: function(name, dialog) {
        var url = jQuery("#" + dialog + " .edit-again").attr("href");
        jQuery("#" + dialog + " .edit-again").attr("href", url + name);
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            gdCPTAdmin.ajax(dialog);
        });
    },
    ajax_simple: function() {
        jQuery("#gdcpt-settings-form").submit(function(e) {
            e.preventDefault();

            jQuery("#gdcpt-settings-form").ajaxSubmit({
                beforeSubmit: function() { 
                    jQuery("#gdr2dialogsave").dialog("open");
                },
                success: function(json) {
                    jQuery("#gdr2dialogsave").dialog("close");

                    if (json.status) {
                        jQuery("#gdr2dialog_error .gdr2-dialog-content p").html(json.msg);
                        jQuery("#gdr2dialog_error").dialog("option", "title", json.title);
                        jQuery("#gdr2dialog_error").dialog("open");
                    }
                },
                dataType: "json", timeout: 600 * 1000,
                url: "admin-ajax.php?action=gdcpt_save_settings&_ajax_nonce=" + gdCPTTools.nonce
            });
        });
    },
    ajax: function(dialog) {
        jQuery(".gdr2-element").removeClass("gdr2-error");
        jQuery(".gdr2-panel .gdr2-group h2").css("backgroundColor", "#EEEEEE").css("color", "#222222");
        jQuery(".gdr2-panel .gdr2-group h2 span").hide();

        jQuery("#gdcpt-settings-form").ajaxSubmit({
            beforeSubmit: function() {jQuery("#gdr2dialogsave").dialog("open");},
            success: function(json) {
                jQuery("#gdr2dialogsave").dialog("close");
                if (json.status === "ok") {
                    jQuery("#" + dialog).dialog("open");
                } else {
                    var i;
                    for (i = 0; i < json.groups.length; i++) {
                        jQuery(json.groups[i][0] + " h2").css("backgroundColor", "#FFDADA").css("color", "#C71200");
                        jQuery(json.groups[i][0] + " h2 span").show();
                    }
                    for (i = 0; i < json.errors.length; i++) {
                        jQuery(".gdr2-element-" + json.errors[i][0])
                            .addClass("gdr2-error")
                            .find(".gdr2-description-for-error span")
                            .attr("qtip-content", json.errors[i][1]);
                    }
                    jQuery("#gdr2dialog_error").dialog("open");
                }
            },
            dataType: "json",
            url: ajaxurl + "?action=gd_cpt_save_settings&_ajax_nonce=" + gdCPTTools.nonce
        });
    },
    init: function() {
        jQuery("#tabs").tabs();

        jQuery("input.pressbutton, a.pressbutton").button();
        jQuery(".gdr2-group button.pressbutton").button({
            icons: {
                primary: "ui-icon-disk"
            },
            text: false
        }).click(function(){
            jQuery(this).closest("form").submit();
            return false;
        });
        jQuery(".gdr2-group button.linkbutton").button({
            icons: {
                primary: "ui-icon-extlink"
            },
            text: false
        }).click(function(){
            window.open(jQuery(this).attr("href"), "_blank");
            return false;
        });

        jQuery(".widefat tbody tr").hover(
            function(){jQuery(this).addClass("gdr2-highlight-row");}, 
            function(){jQuery(this).removeClass("gdr2-highlight-row");}
        );

        jQuery("#gdr2dialog_custom").dialog({
            bgiframe: true, autoResize:true, autoOpen: false, minHeight: 60, 
            width: 300, modal: true, closeOnEscape: true, resizable: false,
            buttons: {"OK": function() {jQuery(this).dialog("close");}}
        });

        jQuery("#gdr2dialogsave").dialog({
            bgiframe: true, autoResize:true, autoOpen: false, minHeight: 60, 
            width: 300, modal: true, closeOnEscape: false, resizable: false
        });

        jQuery("#gdr2dialog_cpt_simple, #gdr2dialog_cpt_full, #gdr2dialog_tax_simple, #gdr2dialog_tax_full").dialog({
            bgiframe: true, autoResize:true, autoOpen: false, minHeight: 100, 
            width: 450, modal: true, closeOnEscape: false, resizable: false
        });

        jQuery("#gdr2dialog_error").dialog({
            bgiframe: true, autoResize:true, autoOpen: false, minHeight: 100, 
            width: 300, modal: true, closeOnEscape: false, resizable: false,
            buttons: {"OK": function() {jQuery(this).dialog("close");}}
        });

        jQuery(".gdr2-media-open span").click(function() {
            var gdr2_id = jQuery(this).attr("gdr2-id");
            gdCPTAdmin.tmp.form_mediaupload_function = window.send_to_editor;
            tb_show(gdCPTTools.select_title, "media-upload.php?type=image&amp;TB_iframe=true");
            window.send_to_editor = function(html) {
                var imgurl = jQuery(html).attr("href");
                jQuery("#" + gdr2_id).val(imgurl);
                tb_remove();
                window.send_to_editor = gdCPTAdmin.tmp.form_mediaupload_function;
            };
        });
        jQuery(".gdr2-media-preview span").click(function() {
            var gdr2_id = jQuery(this).attr("gdr2-id");
            var url = jQuery("#" + gdr2_id).val().trim();
            if (url !== "") {
                tb_show(gdCPTTools.preview_title, gdCPTTools.preview_url + "?img=" + encodeURIComponent(url) + "&TB_iframe=true");
            }
        });
    },
    init_confirm: function() {
        jQuery("#gdr2dialog_confirm").dialog({bgiframe: true, modal: true, 
               autoResize:true, autoOpen: false, resizable: false, width: 400});

        jQuery(".gdr2_confirm_alert").click(function(e) {
            e.preventDefault();
            var targetUrl = jQuery(this).attr("href");

            jQuery("#gdr2dialog_confirm").dialog({
                buttons: {"OK": function() {window.location.href = targetUrl;},
                           "Cancel": function() {jQuery(this).dialog("close");}}
            });

            jQuery("#gdr2dialog_confirm").dialog("open");
        });
    },
    init_toggler: function() {
        jQuery(".gdr2-group-elements-toggle").click(function(){
            var opened = jQuery(this).hasClass("toggle-opened");
            var id = jQuery(this).attr("id").substr(12);

            if (opened) {
                gdCPTAdmin.toggler.groupClose(id);
            } else {
                gdCPTAdmin.toggler.groupOpen(id, true);
            }

            return false;
        });

        var cookie_settings = getCookie(gdCPTTools.cookie_name);
        if (cookie_settings !== null) {
            gdCPTAdmin.tmp.settings_toggle = cookie_settings.split("|");

            var ic;
            for (ic = 0; ic < gdCPTAdmin.tmp.settings_toggle.length; ic++) {
                gdCPTAdmin.toggler.groupOpen(gdCPTAdmin.tmp.settings_toggle[ic], false);
            }
        }
    },
    init_qtip: function() {
        var qtip_info = {
             content: {
                 text: function(api) {return jQuery(this).attr("title");}
             },
             position: {
                 my: "bottom center", at: "top center", adjust: {y: -5}
             },
             style: {
                 widget: true, classes: "qtip-shadow qtip-blue"
             }
        };
        var qtip_d4p = {
            content: {
                 title:function(api) {return jQuery(this).attr("qtip-title");},
                 text: function(api) {return jQuery(this).attr("qtip-content");}
             },
             position: {
                 my: "left center", at: "right center", adjust: {x: 10}
             },
             style: {
                 widget: true, classes: "qtip-shadow qtip-rounded qtip-d4p qtip-red"
             }
        };
        var qtip_data = {
             content: {
                 title: gdCPTTools.txt_qtip_title, button: true,
                 text: function(api) {return jQuery(this).attr("qtip-content");}
             },
             position: {
                 my: "left center", at: "right center", adjust: {x: 10}
             },
             style: {
                 widget: true, classes: "qtip-shadow qtip-red"
             }
        };

        var qtip_error = jQuery.extend(true, {}, qtip_data);
        var qtip_info_error = jQuery.extend(true, {}, qtip_info);

        qtip_error.style.classes = "qtip-shadow qtip-red";
        qtip_info_error.style.classes = "qtip-shadow qtip-red";
        qtip_error.content.title = gdCPTTools.txt_qtip_error;

        jQuery(".gdr2-qtip-info").qtip(qtip_info);
        jQuery(".gdr2-qtip-info-error").qtip(qtip_info_error);
        jQuery(".qtip-d4p-product").qtip(qtip_d4p);
        jQuery(".gdr2-qtip").qtip(qtip_data);
        jQuery(".gdr2-qtip-error").qtip(qtip_error);
    },
    init_checkboxes: function() {
        if (gdCPTTools.ui_enhance === "off") {return;}

        jQuery("input:checkbox:not(.check-no-correcting)").checkbox({cls:"jquery-checkbox", empty:gdCPTTools.url + "gfx/blank.gif"});
        jQuery("input:radio:not(.check-no-correcting)").checkbox({cls:"jquery-radiobox", empty:gdCPTTools.url + "gfx/blank.gif"});
        jQuery("input.switchbox:not(.check-no-correcting)").checkbox({cls:"jquery-switchbox", empty:gdCPTTools.url + "gfx/blank.gif"});
        jQuery("input.switchbox-big:not(.check-no-correcting)").checkbox({cls:"jquery-switchbox-big", empty:gdCPTTools.url + "gfx/blank.gif"});
    },
    toggler: {
        groupOpen: function(id, add_to_cookie) {
            jQuery(".gdr2-group-elements-" + id).slideDown();
            jQuery("#gdr2-toggle-" + id).addClass("toggle-opened");
            jQuery("#gdr2-toggle-" + id).removeClass("toggle-closed");

            if (add_to_cookie) {
                gdCPTAdmin.tmp.settings_toggle[gdCPTAdmin.tmp.settings_toggle.length] = id;
                setCookie(gdCPTTools.cookie_name, gdCPTAdmin.tmp.settings_toggle.join("|"), 365);
            }
        },
        groupClose: function(id) {
            jQuery(".gdr2-group-elements-" + id).slideUp();
            jQuery("#gdr2-toggle-" + id).addClass("toggle-closed");
            jQuery("#gdr2-toggle-" + id).removeClass("toggle-opened");
            gdCPTAdmin.tmp.settings_toggle = jQuery.grep(gdCPTAdmin.tmp.settings_toggle, function(value) {
                return value !== id;
            });
            setCookie(gdCPTTools.cookie_name, gdCPTAdmin.tmp.settings_toggle.join("|"), 365);
        }
    },
    panel: {
        modules: {
            init: function() {
                jQuery("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");

                gdCPTAdmin.ajax_simple();
            }
        },
        post_types: {
            init: function() {
                jQuery("#cpt_name, .limit-query-safe").limitkeypress({ rexp: /^[a-z0-9\-\_]*$/ });
                jQuery(".limit-url-safe").limitkeypress({ rexp: /^[a-z0-9\-\_\/]*$/ });
            },
            list: function() {
                gdCPTAdmin.grid_order("#gdcpt-post-types", "post_type", "cpt");
            }
        },
        taxonomies: {
            init: function() {
                jQuery("#tax_name, .limit-query-safe").limitkeypress({ rexp: /^[a-z0-9\-\_]*$/ });
                jQuery(".limit-url-safe").limitkeypress({ rexp: /^[a-z0-9\-\_\/]*$/ });
            },
            list: function() {
                gdCPTAdmin.grid_order("#gdcpt-taxonomies", "taxonomy", "tax");
            }
        }
    }
};

jQuery(document).ready(function() {
    gdCPTAdmin.init_qtip();
    gdCPTAdmin.init_checkboxes();
    gdCPTAdmin.init_toggler();
    gdCPTAdmin.init_confirm();

    gdCPTAdmin.init();
});
