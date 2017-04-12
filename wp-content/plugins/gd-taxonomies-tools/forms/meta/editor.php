<?php

global $gdr2_units;

require_once(GDTAXTOOLS_PATH.'gdr2/gdr2.ui.php');

gdtt_update_custom_fields(true);

$wppt = gdtt_get_public_post_types(true);
$post_types_list = array('__none__' => __("Select Post Type", "gd-taxonomies-tools"));
foreach ($wppt as $name => $_obj) {
    $obj = (array)$_obj;
    $post_types_list[$name] = $obj['label'];
}

$custom_list_of_units = $gdr2_units->get_units();
unset($custom_list_of_units['currency']);

$custom_date_rewrite_fields = array('__none__' => __("No Field Mirroring", "gd-taxonomies-tools"));
$custom_boxes_list = array('__none__' => __("Select Meta Box", "gd-taxonomies-tools"));
foreach ($gdtt_meta['boxes'] as $box => $_obj) {
    $obj = (array)$_obj;
    $custom_boxes_list[$box] = $obj['name'];
}

$custom_fields_list = array('__none__' => __("Select Field", "gd-taxonomies-tools"));
foreach ($gdtt_meta['fields'] as $field => $_obj) {
    $obj = (array)$_obj;
    $custom_fields_list[$field] = $obj['name'].' ('.$gdtt_fields->get_field_type($obj).')';

    if ($gdtt_fields->is_rewritable($obj['type'])) {
        $custom_date_rewrite_fields[$field] = $obj['name'].' ('.$gdtt_fields->get_field_type($obj).')';
    }
}

$custom_functions_list = array('__none__' => __("Select Function", "gd-taxonomies-tools"));
$custom_functions_list = apply_filters("gdcpt_custom_field_function", $custom_functions_list);

$custom_regex_raw = apply_filters('gdcpt_custom_field_regex', array());
$custom_mask_raw = apply_filters('gdcpt_custom_field_mask', array());
$custom_regex = $custom_mask = array();

foreach ($custom_regex_raw as $key => $val) {
    $custom_regex['regex|'.$key] = $val;
}

foreach ($custom_mask_raw as $key => $val) {
    $custom_mask['mask|'.$key] = $val;
}

$custom_restrictions_list = array(
    array('title' => __("General", "gd-taxonomies-tools"), 
          'values' => array('__none__' => __("Not Restricted", "gd-taxonomies-tools"), 
                            '__custom__' => __("Use Custom Regex", "gd-taxonomies-tools"),
                            '__custom_mask__' => __("Use Custom Mask", "gd-taxonomies-tools"))
    ),
    array('title' => __("Regular Expressions", "gd-taxonomies-tools"), 
          'values' => $custom_regex
    ),
    array('title' => __("Masks", "gd-taxonomies-tools"), 
          'values' => $custom_mask
    )
);

$select_methods = array(
    'normal' => __("Normal List", "gd-taxonomies-tools"),
    'associative' => __("Associative List", "gd-taxonomies-tools"),
    'function' => __("Function", "gd-taxonomies-tools")
);

$selection_methods = array(
    'select' => __("Single Item / Select Control", "gd-taxonomies-tools"),
    'radio' => __("Single Item / Radio Control", "gd-taxonomies-tools"),
    'multi' => __("Multi Items / Select Control", "gd-taxonomies-tools"),
    'checkbox' => __("Multi Items / Checkbox Control", "gd-taxonomies-tools"),
);

$custom_meta_locations = array(
    'advanced' => __("Advanced", "gd-taxonomies-tools"),
    'normal' => __("Normal", "gd-taxonomies-tools"),
    'side' => __("Side", "gd-taxonomies-tools")
);

$custom_date_save_format = array(
    array('title' => __("For Date, Date/Time and Month", "gd-taxonomies-tools"), 'values' => array(
        'timestamp' => __("Timestamp", "gd-taxonomies-tools"),
        'mysql' => __("MySQL", "gd-taxonomies-tools").' "YYYY-MM-DD HH:MM:SS"'
    )),
    array('title' => __("For Date, Time and Month", "gd-taxonomies-tools"), 'values' => array(
        'colon' => __("Colon", "gd-taxonomies-tools").': YYYY:MM:DD / HH:MM:SS',
        'dotted' => __("Dotted", "gd-taxonomies-tools").': YYYY.MM.DD / HH.MM.SS',
        'dashed' => __("Dashed", "gd-taxonomies-tools").': YYYY-MM-DD / HH-MM-SS'
    ))
);

$custom_tax_values = array();
global $wp_taxonomies;
foreach ($wp_taxonomies as $taxonomy => $cnt) {
    $custom_tax_values[$taxonomy] = $cnt->labels->name;
}

$custom_fields_values = $gdtt_fields->get_fields_list();

?>

<script type='text/javascript'>
    jQuery(document).ready(function() {
        jQuery("#gdttcfedit").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 820, modal: true, buttons: {
                '<?php _e("Save", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.add_field(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttcfdelete").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 480, modal: true, buttons: {
                '<?php _e("Delete", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.delete_field(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttmbedit").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 820, modal: true, buttons: {
                '<?php _e("Save", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.add_metabox(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttmbdelete").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 480, modal: true, buttons: {
                '<?php _e("Delete", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.delete_metabox(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttmbptypes").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 480, modal: true, buttons: {
                '<?php _e("Save", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.attach_posttypes(); },
                '<?php _e("Clear", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.clear_posttypes(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttmbgedit").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 820, modal: true, buttons: {
                '<?php _e("Save", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.add_metabox_group(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttmbgdelete").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 480, modal: true, buttons: {
                '<?php _e("Delete", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.delete_metabox_group(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        jQuery("#gdttmbgptypes").dialog({ closeOnEscape: true, resizable: false,
            autoOpen: false, bgiframe: true, width: 480, modal: true, buttons: {
                '<?php _e("Save", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.attach_group_posttypes(); },
                '<?php _e("Clear", "gd-taxonomies-tools"); ?>': function() { gdCPTAdmin.editor.clear_group_posttypes(); },
                '<?php _e("Cancel", "gd-taxonomies-tools"); ?>': function() { jQuery(this).dialog('close'); }
            }
        });

        gdCPTAdmin.tmp.meta_boxes_count = <?php echo count($gdtt_meta['boxes']); ?>;
        gdCPTAdmin.tmp.custom_fields_count = <?php echo count($gdtt_meta['fields']); ?>;
        gdCPTAdmin.tmp.meta_box_groups_count = <?php echo count($gdtt_meta['groups']); ?>;

        gdCPTAdmin.tmp.custom_fields = <?php echo empty($gdtt_meta['fields']) ? '{}' : json_encode($gdtt_meta['fields']); ?>;

        gdCPTAdmin.tmp.post_types = <?php echo json_encode($post_types_list); ?>;

        gdCPTAdmin.tmp.meta_boxes = <?php echo empty($gdtt_meta['boxes']) ? '{}' : json_encode($gdtt_meta['boxes']); ?>;
        gdCPTAdmin.tmp.post_types_map = <?php echo empty($gdtt_meta['map']) ? '{}' : json_encode($gdtt_meta['map']); ?>;

        gdCPTAdmin.tmp.meta_box_groups = <?php echo empty($gdtt_meta['groups']) ? '{}' : json_encode($gdtt_meta['groups']); ?>;
        gdCPTAdmin.tmp.post_types_map_groups = <?php echo empty($gdtt_meta['map_groups']) ? '{}' : json_encode($gdtt_meta['map_groups']); ?>;

        gdCPTAdmin.tpl.mbe_row = '<tr class="gdtt-mbrow-%CODE%"><td><strong>%CODE%</strong></td><td>%NAME%</td><td>%FIELDS%</td><td class="gdtt-post-types">%POST_TYPES%</td><td>%LOCATION%</td><td>%DESCRIPTION%</td><td style="width: 128px; text-align: right;"><a class="ttoption-edit gdtt-mbo-edit" href="#%CODE%"><?php _e("edit", "gd-taxonomies-tools"); ?></a> | <a class="ttoption-del gdtt-mbo-delete" href="#%CODE%"><?php _e("delete", "gd-taxonomies-tools"); ?></a><br/><a class="ttoption-edit gdtt-mbo-postypes" href="#%CODE%"><?php _e("post types", "gd-taxonomies-tools"); ?></a></td></tr>';
        gdCPTAdmin.tpl.mbg_row = '<tr class="gdtt-mbgrow-%CODE%"><td><strong>%CODE%</strong></td><td>%NAME%</td><td>%BOXES%</td><td class="gdtt-post-types">%POST_TYPES%</td><td>%LOCATION%</td><td style="width: 128px; text-align: right;"><a class="ttoption-edit gdtt-mbg-edit" href="#%CODE%"><?php _e("edit", "gd-taxonomies-tools"); ?></a> | <a class="ttoption-del gdtt-mbg-delete" href="#%CODE%"><?php _e("delete", "gd-taxonomies-tools"); ?></a><br/><a class="ttoption-edit gdtt-mbg-postypes" href="#%CODE%"><?php _e("post types", "gd-taxonomies-tools"); ?></a></td></tr>';
        gdCPTAdmin.tpl.cfe_row = '<tr class="gdtt-cfrow-%CODE%"><td><strong>%CODE%</strong></td><td>%NAME%</td><td>%TYPE%</td><td>%REQUIRED%</td><td>%VALUES%</td><td>%LIMIT%</td><td>%DESCRIPTION%</td><td style="width:64px; text-align: right;">%COUNTER%</td><td style="width:128px;text-align:right;"><a class="ttoption-edit gdtt-cfo-edit" href="#%CODE%"><?php _e("edit", "gd-taxonomies-tools"); ?></a> | <a class="ttoption-edit gdtt-cfo-copy" href="#%CODE%"><?php _e("copy", "gd-taxonomies-tools"); ?></a> | <a class="ttoption-del gdtt-cfo-delete" href="#%CODE%"><?php _e("delete", "gd-taxonomies-tools"); ?></a></td></tr>';

        gdCPTAdmin.editor.init();
    });
</script>
<div class="gdcpt-settings">
<form action="" id="gdcpt-settings-form" method="post">
    <div id="tabs" class="gdtt-wide-tabs" style="width: 99.4%;">
        <ul>
            <li>
                <button class="gdtt-addnew-small" id="gdtt-mbe-addnew-small">Add New Metabox</button>
                <a href="#tabs-boxes"><?php _e("Meta Boxes", "gd-taxonomies-tools"); ?></a>
                <div><?php _e("to use for post types", "gd-taxonomies-tools"); ?></div>
            </li>
            <li>
                <button class="gdtt-addnew-small" id="gdtt-mbg-addnew-small">Add New Metabox Group</button>
                <a href="#tabs-groups"><?php _e("Metabox Groups", "gd-taxonomies-tools"); ?></a>
                <div><?php _e("to use for post types", "gd-taxonomies-tools"); ?></div>
            </li>
            <li>
                <button class="gdtt-addnew-small" id="gdtt-cfe-addnew-small">Add New Custom Field</button>
                <a href="#tabs-fields"><?php _e("Custom Fields", "gd-taxonomies-tools"); ?></a>
                <div><?php _e("to use in the meta boxes", "gd-taxonomies-tools"); ?></div>
            </li>
        </ul>
        <div id="tabs-boxes">
            <?php include GDTAXTOOLS_PATH.'forms/meta/boxes.php'; ?>
        </div>
        <div id="tabs-groups">
            <?php include GDTAXTOOLS_PATH.'forms/meta/groups.php'; ?>
        </div>
        <div id="tabs-fields">
            <?php include GDTAXTOOLS_PATH.'forms/meta/fields.php'; ?>
        </div>
    </div>
</form>
</div>
