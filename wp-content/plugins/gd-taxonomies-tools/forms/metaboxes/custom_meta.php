<div class="gdtt-mb-holder gdtt-wpv-<?php echo GDTAXTOOLS_WPV; ?>">
<?php if ($meta['description'] != '') {
    echo '<p class="gdtt-mb-description">'.__($meta['description']).'</p>';
} ?>

<input type="hidden" name="<?php echo $_NAME ?>__nonce__][0]" value="<?php echo wp_create_nonce('gdcpttools'); ?>" />

<?php

do_action('gdcpt_metabox_'.$meta['code'].'_header');

$done = array();
$rich_editor = array();

if (!isset($meta['repeater'])) {
    $meta['repeater'] = 'no';
}

foreach ($meta['fields'] as $f) {
    $field = $_F[$f];
    $to_show = true;

    if (isset($meta['user_access'])) {
        if ($field['user_access'] == 'role') {
            $roles = explode(',', $field['user_roles']);
            $to_show = gdr2_is_current_user_roles($roles);
        } else if ($field['user_access'] == 'caps') {
            $caps = explode(',', $field['user_caps']);
            $to_show = false;

            foreach ($caps as $cap) {
                if (current_user_can($cap)) {
                    $to_show = true;
                }
            }
        }
    }

    $to_show = apply_filters('gdcpt_field_access', $to_show, $field, $meta);

    if ($to_show) {
        echo '<div class="gdtt-cf-block gdtt-cf-type-'.$field['type'].($field['required'] ? ' gdtt-required' : '').($meta['repeater'] == 'yes' ? ' gdtt-with-repeater' : '').'">';
            do_action('gdcpt_metabox_'.$meta['code'].'_field_header');
            do_action('gdcpt_metabox_'.$meta['code'].'_field_header_'.$field['code']);

            echo '<div class="gdtt-cf-icons">';
                echo '<div class="gdtt-ui-button"><span gdtt-shortcode="'.$gdtt_fields->get_shortcode($field['type']).'" gdtt-type="'.$field['type'].'" gdtt-field="'.$f.'" title="'.__("Insert shortcode for this custom field.", "gd-taxonomies-tools").'" class="ui-icon ui-icon-script"></span></div>';
            echo '</div><div class="gdtt-cf-information">';
            echo '<div class="gdtt-cf-label gdtt-label-'.$field['type'].'"><strong>'.__($field['name']).($field['required'] ? " <span>(*)</span>" : "").':</strong></div>';

            if ($field['description'] != '') { 
                echo '<p class="gdtt-cf-description">'.__($field['description']).'</p>';
            }

            echo '</div><div class="gdtt-cf-fields" id="'.$_ID.$field['code'].'_fields">';

            $counter = 0;

            if (empty($values[$f])) {
                $values[$f][] = '';
            }

            foreach ($values[$f] as $value) {
                $done[$f] = $counter;

                $id = $_ID.$field['code']."_".$done[$f];
                $name = $_NAME.$field['code']."][".$done[$f]."]";

                if ($field['type'] != 'editor') {
                    echo '<div class="gdtt-cf-field"'.($counter > 0 ? ' style="margin-top: 2px"' : '').'>';
                    echo '<div class="gdtt-cf-repeater">';

                    if ($gdtt_fields->is_repeatable($field['type']) && $meta['repeater'] == 'yes') {
                        echo '<div class="gdtt-ui-button gdtt-ui-button-repeater gdtt-repeater-plus"'.($counter > 0 ? ' style="display: none"' : '').'><span title="'.__("Add new element.", "gd-taxonomies-tools").'" class="ui-icon ui-icon-plus"></span></div>';
                        echo '<div class="gdtt-ui-button gdtt-ui-button-repeater gdtt-repeater-minus"'.($counter == 0 ? ' style="display: none"' : '').'><span title="'.__("Remove element.", "gd-taxonomies-tools").'" class="ui-icon ui-icon-minus"></span></div>';
                    } else {
                        echo '&nbsp;';
                    }

                    echo '</div>';
                    echo '<div class="gdtt-cf-control">';
                }

                echo $gdtt_fields->meta_render($value, $field, $id, $name);

                if ($field['type'] != 'editor') {
                    echo '</div>';
                    echo '</div>';
                }

                $counter++;

                if (!$gdtt_fields->is_repeatable($field['type']) || $meta['repeater'] != 'yes') {
                    continue;
                }
            }

            echo '</div>';

            do_action('gdcpt_metabox_'.$meta['code'].'_field_footer');
            do_action('gdcpt_metabox_'.$meta['code'].'_field_footer_'.$field['code']);

            echo '<input type="hidden" id="'.$_ID.$field['code'].'_counter" value="'.$counter.'" />';
            echo '<div class="gdtt-clear"></div>';
        echo '</div>';

        do_action('gdcpt_metabox_'.$meta['code'].'_field_after');
        do_action('gdcpt_metabox_'.$meta['code'].'_field_after_'.$field['code']);
    }
}

do_action('gdcpt_metabox_'.$meta['code'].'_footer');

?>
</div>
