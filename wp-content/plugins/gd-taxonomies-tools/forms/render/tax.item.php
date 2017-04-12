<?php

$links = array('ctrl' => array());

if ($tax_data->name == 'link_category') {
    $edit_term_url = 'edit-link-categories.php';
} else {
    $edit_term_url = 'edit-tags.php?taxonomy='.$tax_data->name;
}

$istaxtool = in_array($tax_data->name, $gdtttax);

$tt_url = 'admin.php?page=gdtaxtools_taxs&cpt=1&tid=';
$tt_url_default = 'admin.php?page=gdtaxtools_taxs&cpt=0&tname=';

$cpt_source = '';
$cpt_created = false;

$allow_deletion = apply_filters('gdcpt_uitax_allow_delete_'.$tax_name, true);

foreach ($gdtxall as $tax) {
    if (strtolower(sanitize_user($tax['name'], true)) == $tax_name) {
        $tt_url.= $tax['id'];
        $cpt_created = true;
        $cpt_source = isset($tax['source']) ? $tax['source'] : '';
        break;
    }
}

if ($cpt_created) {
    $links['ctrl'][] = '<a title="'.__("Show the function that generates this taxonomy.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=function">'.__("function", "gd-taxonomies-tools").'</a>';
    $links['ctrl'][] = '<a title="'.__("Help with settings up theme templates for this taxonomy.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=templates">'.__("templates", "gd-taxonomies-tools").'</a>';
}

$allow_edit = $cpt_created;

if (!$allow_edit) {
    if (!$default) {
        $allow_edit = true;
    } else {
        $allow_edit = $tax_data->public && $tax_data->name != 'post_format';
    }
}

if ($tax_data->name == "nav_menu") {
    $links['ctrl'][] = '<a title="'.__("Open navigation menues editor.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="nav-menus.php">'.__("menus", "gd-taxonomies-tools").'</a>';
} else if ($tax_data->name != "post_format") {
    $links['ctrl'][] = '<a title="'.__("Open editor screen for this taxonomy.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$edit_term_url.'">'.__("terms", "gd-taxonomies-tools").'</a>';
}

$links['ctrl'] = apply_filters('gdcpt_taxonomies_editor_links_control', $links['ctrl']);

$settings = array();
if ($tax_data->hierarchical) $settings[] = __("Hierarchical", "gd-taxonomies-tools");
if ($tax_data->public) $settings[] = __("Public", "gd-taxonomies-tools");
if ($tax_data->show_ui) $settings[] = __("Show UI", "gd-taxonomies-tools");
if ($tax_data->show_in_nav_menus) $settings[] = __("In Nav Menu", "gd-taxonomies-tools");
if ($tax_data->show_tagcloud) $settings[] = __("Tag Cloud", "gd-taxonomies-tools");
if (isset($tax_data->show_admin_column) && $tax_data->show_admin_column) $settings[] = __("Admin Column", "gd-taxonomies-tools");

?>

<tr<?php if ($cpt_created) echo ' gdcptid="tax-'.$tax['id'].'"'; ?> id="tax-<?php echo $tax_name; ?>" class="taxonomy <?php echo $tr_class; ?> author-self status-publish" valign="top">
    <td class="column-name"><h5>
            <span>
                <?php

                if ($cpt_created) {
                    echo '<a title="'.__("Edit taxonomy.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=edit">'.__("edit", "gd-taxonomies-tools").'</a>';
                    echo '|<a title="'.__("Create new post type from all same settings as this one. Name will be empty.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url.'&action=dupecpt">'.__("duplicate", "gd-taxonomies-tools").'</a>';

                    if ($allow_deletion) {
                        echo '|<a title="'.__("Delete taxonomy. Operation is not reversible.", "gd-taxonomies-tools").'" class="ttoption-del gdr2_confirm_alert gdr2-qtip-info-error" href="'.$tt_url.'&action=deltax">'.__("delete", "gd-taxonomies-tools").'</a>';
                    }
                } else {
                    if ($allow_edit) {
                        echo __("edit", "gd-taxonomies-tools").': ';
                        echo '<a title="'.__("Edit all taxonomy settings.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url_default.$tax_name.'&action=edit">'.__("full", "gd-taxonomies-tools").'</a>|';
                        echo '<a title="'.__("Edit only some taxonomy settings, including plugin enhanced features.", "gd-taxonomies-tools").'" class="ttoption gdr2-qtip-info" href="'.$tt_url_default.$tax_name.'&action=simple">'.__("simple", "gd-taxonomies-tools").'</a>';
                    }
                }

                ?>
            </span>
            <strong style="color: #cc0000; font-size: 14px;"><?php echo $tax_data->label; ?></strong><br/>
        </h5>
        <?php _e("code", "gd-taxonomies-tools"); ?>: <strong><?php echo $tax_data->name; ?></strong><br/>
        <?php _e("control", "gd-taxonomies-tools"); ?>: <?php echo join ('|', $links['ctrl']); ?>
    </td>
    <td style="text-align: left;">
        <div style="border-bottom: 1px solid #aaaaaa; padding-bottom: 3px; margin-bottom: 2px;">
        <?php if ($tax_data->rewrite === false) {
                _e("Rewrite Disabled", "gd-taxonomies-tools");
              } else {
                  $opt = array();

                  if ($tax_data->rewrite["with_front"]) $opt[] = __("front", "gd-taxonomies-tools");
                  if ($tax_data->rewrite["hierarchical"]) $opt[] = __("hierarchical", "gd-taxonomies-tools");

                  echo __("rewrite slug", "gd-taxonomies-tools").": <strong>".$tax_data->rewrite["slug"]."</strong><br/>";
                  echo join(', ', $opt);
        } ?></div>
        <?php if ($tax_data->query_var === false) {
            _e("Query Disabled", "gd-taxonomies-tools");
        } else {
            echo __("query var", "gd-taxonomies-tools").": <strong>".$tax_data->query_var."</strong>";
        } ?>
    </td>
    <td>
        <?php echo join('<br/>', array_unique(gdtt_get_post_types_for_taxonomies($tax_data->name, 'print'))); ?>
    </td>
    <td style="text-align: left;">
        <?php echo join(', ', $settings); ?>
    </td>
    <td style="text-align: right;">
        <strong><?php echo (!$inactive_item) ? wp_count_terms($tax_data->name) : "0"; ?></strong>
    </td>
</tr>
<?php $tr_class = $tr_class == '' ? 'alternate ' : ''; ?>