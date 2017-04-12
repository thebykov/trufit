<?php

function gdcpt_bbpress_mod_forum_settings($forum_id, $deep = false, $args = array()) {
    $default = array('topic' => '__parent__', 'reply' => '__parent__',
        'location_topic' => '__parent__', 'location_reply' => '__parent__');

    if (!gdr2_post_has_parent($forum_id)) {
        $default = array( 'topic' => '__default__', 'reply' => '__default__',
            'location_topic' => '__default__', 'location_reply' => '__default__');
    }

    $data = get_post_meta($forum_id, '_gdtt_bbpress_forum_settings', true);
    $data = $data == '' ? array() : (array)$data;
    $data = wp_parse_args($data, $default);

    if (!empty($args)) {
        $data = wp_parse_args($args, $data);
    }

    if ($deep && gdr2_post_has_parent($forum_id)) {
        $args = array();
        $drill = false;

        foreach ($data as $key => $value) {
            if ($value != '__parent__') {
                $args[$key] = $value;
            } else {
                $drill = true;
            }
        }

        if ($drill) {
            $parent_id = gdr2_get_post_parent($forum_id);
            $data = gdcpt_bbpress_mod_forum_settings($parent_id, true, $args);
        }
    }

    return $data;
}

?>