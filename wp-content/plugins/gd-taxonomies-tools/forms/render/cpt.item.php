<?php

global $gdtt_icons;

$meta_boxes = array();

if (isset($gdtt_meta['map'])) {
    foreach ($gdtt_meta['map'] as $box => $types) {
        $meta = $gdtt_meta['boxes'][$box];

        if (in_array($cpt_name, $types)) {
            $meta_boxes[] = __("Box", "gd-taxonomies-tools").': <strong>'.$meta['name'].'</strong>';
        }
    }
}

if (isset($gdtt_meta['map_groups'])) {
    foreach ($gdtt_meta['map_groups'] as $box => $types) {
        $meta = $gdtt_meta['groups'][$box];

        if (in_array($cpt_name, $types)) {
            $meta_boxes[] = __("Group", "gd-taxonomies-tools").': <strong>'.$meta['name'].'</strong>';
        }
    }
}

$links = array('ctrl' => array());

$tt_url = 'admin.php?page=gdtaxtools_postypes&amp;cpt=1&amp;pid=';
$tt_url_default = 'admin.php?page=gdtaxtools_postypes&amp;cpt=0&amp;pname=';

$cpt_icon = '';
$cpt_source = '';
$cpt_created = false;

$allow_deletion = apply_filters('gdcpt_uitax_allow_delete_'.$cpt_name, true);

foreach ($gdcpall as $cpt) {
    if (strtolower(sanitize_user($cpt['name'], true)) == $cpt_name) {
        $cpt_icon = isset($cpt['icon']) ? $cpt['icon'] : '';
        $tt_url.= $cpt['id'];
        $cpt_created = true;
        $cpt_source = isset($cpt['source']) ? $cpt['source'] : '';
        break;
    }
}

if ($cpt_created) {
    $links['ctrl'][] = '<a title="'.__("Show the function that generates this post type.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=function">'.__("function", "gd-taxonomies-tools").'</a>';
    $links['ctrl'][] = '<a title="'.__("Help with settings up theme templates for this post type.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=templates">'.__("templates", "gd-taxonomies-tools").'</a>';
}

if ($cpt_name != "attachment" && $cpt_name != "revision" && $cpt_name != "nav_menu_item") {
    $links['ctrl'][] = '<a title="'.__("Open editor screen for this post type.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="edit.php?post_type='.$cpt_data->name.'">'.__("open", "gd-taxonomies-tools").'</a>';
} else if ($cpt_name == "nav_menu_item") {
    $links['ctrl'][] = '<a title="'.__("Open navigation menues editor.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="nav-menus.php">'.__("open", "gd-taxonomies-tools").'</a>';
} else if ($cpt_name == "attachment") {
    $links['ctrl'][] = '<a title="'.__("Open media library.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="upload.php">'.__("open", "gd-taxonomies-tools").'</a>';
}

if ($cpt_data->has_archive) {
    $links['ctrl'][] = '<a title="'.__("Open website front archive page.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.get_post_type_archive_link($cpt_data->name).'">'.__("archive", "gd-taxonomies-tools").'</a>';
}

$links['ctrl'] = apply_filters('gdcpt_post_types_editor_links_control', $links['ctrl'], $cpt_name);

$taxes = gdtt_get_taxonomies_for_post_types($cpt_name, 'print');

$settings = array();

if ($cpt_data->hierarchical) $settings[] = __("Hierarchical", "gd-taxonomies-tools");
if ($cpt_data->public) $settings[] = __("Public", "gd-taxonomies-tools");
if ($cpt_data->show_ui) $settings[] = __("Show UI", "gd-taxonomies-tools");
if ($cpt_data->show_in_nav_menus) $settings[] = __("In Nav Menu", "gd-taxonomies-tools");
if ($cpt_data->exclude_from_search) $settings[] = __("Excluded from Search", "gd-taxonomies-tools");
if ($cpt_data->can_export) $settings[] = __("Can Export", "gd-taxonomies-tools");
if ($cpt_data->show_in_menu) $settings[] = __("In Admin Menu", "gd-taxonomies-tools");

?>

<tr<?php if ($cpt_created) echo ' gdcptid="cpt-'.$cpt['id'].'"'; ?> id="cpt-<?php echo $cpt_name; ?>" class="post_type <?php echo $tr_class; ?> author-self status-publish" valign="top">
    <?php if ($cpt_made) { ?>
    <td>
        <?php
            $img = $cpt_data->menu_icon;

            if ($img == '') {
                if ($cpt_icon != '') {
                    $img = $gdtt_icons->get_img($cpt_icon);
                }
            } else {
                $img = '<img width="16" height="16" src="'.$img.'" />';
            }

            if ($img == '') {
                $img = '<img width="16" height="16" src="'.GDTAXTOOLS_URL.'gfx/blank.gif" />';
            }

            echo $img;
        ?>
    </td>
    <?php } ?>
    <td class="column-name"><h5>
            <span>
                <?php
                
                if ($cpt_created) {
                    echo '<a title="'.__("Edit post type.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=edit">'.__("edit", "gd-taxonomies-tools").'</a>';
                    echo '|<a title="'.__("Create new post type from all same settings as this one. Name will be empty.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=dupecpt">'.__("duplicate", "gd-taxonomies-tools").'</a>';

                    if ($allow_deletion) {
                        echo '|<a title="'.__("Delete post type. Operation is not reversible. Exisiting posts for it will remain in the database.", "gd-taxonomies-tools").'" class="ttoption-del gdr2_confirm_alert gdr2-qtip-info-error" href="'.$tt_url.'&action=delcpt">'.__("delete", "gd-taxonomies-tools").'</a>';
                    }
                }

                if (!$cpt_created && $cpt_data->public) {
                    echo __("edit", "gd-taxonomies-tools").': ';
                    echo '<a title="'.__("Edit all post type settings.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url_default.$cpt_name.'&action=edit">'.__("full", "gd-taxonomies-tools").'</a>|';
                    echo '<a title="'.__("Edit only some post type settings, including plugin enhanced features.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url_default.$cpt_name.'&action=simple">'.__("simple", "gd-taxonomies-tools").'</a>';
                }
                
                ?>
            </span>
            <?php echo $cpt_data->label; ?>
        </h5>
        <?php _e("code", "gd-taxonomies-tools"); ?>: <strong><?php echo $cpt_data->name; ?></strong><br/>
        <?php _e("control", "gd-taxonomies-tools"); ?>: <?php echo join ('|', $links['ctrl']); ?>
    </td>
    <td style="text-align: left;">
        <?php if (!$cpt_data->_builtin) {
            echo '<div style="border-bottom: 1px solid #aaaaaa; padding-bottom: 3px; margin-bottom: 2px;">';
            if ($cpt_data->rewrite === false) {
                _e("Rewrite Disabled", "gd-taxonomies-tools");
            } else {
                $opt = array();

                if ($cpt_data->rewrite['with_front']) $opt[] = __("front", "gd-taxonomies-tools");
                if ($cpt_data->rewrite['feeds']) $opt[] = __("feeds", "gd-taxonomies-tools");
                if ($cpt_data->rewrite['pages']) $opt[] = __("pages", "gd-taxonomies-tools");

                echo __("rewrite slug", "gd-taxonomies-tools").': <strong>'.$cpt_data->rewrite['slug'].'</strong><br/>';
                echo join(', ', $opt);
            }
            echo '</div>';
        ?>
        <?php
            if ($cpt_data->query_var === false) {
                echo __("Query Disabled", "gd-taxonomies-tools");
            } else {
                echo __("query var", "gd-taxonomies-tools").": <strong>".$cpt_data->query_var."</strong>";
            }
        } ?>
    </td>
    <td style="text-align: left;">
        <?php echo join('<br/>', $taxes); ?>
    </td>
    <td style="text-align: left;">
        <?php echo join("<br/>", $meta_boxes); ?>
    </td>
    <td style="text-align: left;">
        <?php echo join(", ", $settings); ?>
    </td>
    <td style="text-align: right;">
        <?php echo !isset($post_count[$cpt_data->name]) ? 0 : intval($post_count[$cpt_data->name]); ?>
    </td>
</tr>
<?php $tr_class = $tr_class == '' ? 'alternate ' : ''; ?>