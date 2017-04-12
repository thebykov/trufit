<?php

do_action('gdcpt_metabox_'.$meta['code'].'_header');

$_no_fields = apply_filters('gdcpt_not_allowed_field_bbpress', array('image', 'google_map', 'rewrite'));

$done = array();

if (gdtt_mod('bbpress', 'metabox_fieldset')) {
    echo '<fieldset class="bbp-form">';
    echo '<legend>'.$meta['name'].'</legend>';
}

foreach ($meta['fields'] as $f) {
    $field = $_F[$f];
    $to_show = true;

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

    $to_show = apply_filters('gdcpt_field_access_bbpress', $to_show, $field, $meta);

    if ($to_show) {
        if (!in_array($field['type'], $_no_fields)) {
            if (!isset($done[$f])) { $done[$f] = 0; } else { $done[$f]++; }

            $id = $_ID.$field['code'].'_'.$done[$f];
            $name = $_NAME.$field['code'].']['.$done[$f].']';
            $value = isset($posted_values[$f][$done[$f]]) ? $posted_values[$f][$done[$f]] : '';

            echo '<div class="gdtt-field gdtt-field-'.$field['code'].'"><label for="'.$id.'">';
                do_action('gdcpt_metabox_'.$meta['code'].'_field_header');
                do_action('gdcpt_metabox_'.$meta['code'].'_field_header_'.$field['code']);
                echo __($field['name']).($field['required'] ? ' <span>(*)</span>' : '');

                if ($field['description'] != '') {
                    echo ' - <em>'.$field['description'].'</em>';
                }

                echo ':</label><br/>';

                echo $gdtt_fields->meta_render($value, $field, $id, $name);

                do_action('gdcpt_metabox_'.$meta['code'].'_field_footer');
                do_action('gdcpt_metabox_'.$meta['code'].'_field_footer_'.$field['code']);
            echo '</div>';

            do_action('gdcpt_metabox_'.$meta['code'].'_field_after');
            do_action('gdcpt_metabox_'.$meta['code'].'_field_after_'.$field['code']);
        }
    }
}

do_action('gdcpt_metabox_'.$meta['code'].'_footer');

if (gdtt_mod('bbpress', 'metabox_fieldset')) {
    echo '</fieldset>';
}

?>