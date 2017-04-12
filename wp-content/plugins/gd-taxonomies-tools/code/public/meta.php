<?php

if (!defined('ABSPATH')) exit;

/**
 * Get custom field settings.
 * 
 * @param string $field name of the custom field
 * @return array|null
 */
function gdcpt_get_custom_field($field) {
    global $gdtt;

    if (isset($gdtt->m['fields'][$field])) {
        return (array)$gdtt->m['fields'][$field];
    } else {
        return null;
    }
}

/**
 * Get object with all meta box values and functions to get processed data
 * 
 * @param int $post_id ID of the post to get data for
 * @param string $meta_box code of the meta box
 * @return gdCPT_MetaBox|null object with meta value or null if metabox is non existant
 */
function gdcpt_post_meta($post_id, $meta_box) {
    global $gdtt;

    if (isset($gdtt->m['boxes'][$meta_box])) {
        return new gdCPT_MetaBox_Data($post_id, (array)$gdtt->m['boxes'][$meta_box]);
    } else {
        return null;
    }
}

?>