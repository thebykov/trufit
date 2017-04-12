<?php

if (!defined('ABSPATH')) exit;

/**
 * Enhanced getting post meta value with same processing as custom field for shortcode implemented by plugin.
 *
 * @param int $post_id id for the post
 * @param string $field_name name for the custom field
 * @param array $atts extra attributes
 * @return string prepared / rendered result
 */
function gdtt_get_post_meta($post_id, $field_name, $atts = array()) {
    global $gdtt;

    $field = isset($gdtt->m['fields'][$field_name]) ? $gdtt->m['fields'][$field_name] : array('type' => 'text');
    $value = get_post_meta($post_id, $field_name, true);

    return $gdtt->prepare_cpt_field($field, $value, $atts);
}

/**
 * Get object with all meta box values and functions to get processed data
 * 
 * @param int $post_id ID of the post to get data for
 * @param string $meta_box code of the meta box
 * @return gdCPT_Meta|null object with meta value or null if metabox is non existant
 */
function gdtt_get_post_meta_box($post_id, $meta_box) {
    global $gdtt;

    if (isset($gdtt->m['boxes'][$meta_box])) {
        $meta = new gdCPT_Meta($post_id, (array)$gdtt->m['boxes'][$meta_box]);
        return $meta;
    } else {
        return null;
    }
}

?>