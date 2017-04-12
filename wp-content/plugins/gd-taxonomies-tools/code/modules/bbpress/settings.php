<?php

if (!defined('ABSPATH')) exit;

global $gdtt, $wp_roles, $gdtt_bbpress_load;

if (is_object($gdtt_bbpress_load) && isset($gdtt_bbpress_load->embed_locations)) {
    $embed_topic = $gdtt_bbpress_load->embed_locations['topic'];
    $embed_reply = $gdtt_bbpress_load->embed_locations['reply'];
} else {
    $embed_topic = apply_filters('gdcpt_bbpress_embed_locations_topic', array());
    $embed_reply = apply_filters('gdcpt_bbpress_embed_locations_reply', array());
}

$bbp_boxes = array('__none__' => __("Do not use any", "gd-taxonomies-tools"));

foreach ($gdtt->m['boxes'] as $box_name => $box) {
    $_box = (array)$box;
    $fields_count = count($_box['fields']);
    $bbp_boxes[$box_name] = $_box['name']." (".$fields_count." "._n("field", "fields", $fields_count, "gd-taxonomies-tools").")";
}

$bbp_locations_topic = array_merge(array(
    'bbp_theme_before_topic_form_title' => __("Before Title", "gd-taxonomies-tools"),
    'bbp_theme_after_topic_form_title' => __("After Title", "gd-taxonomies-tools"),
    'bbp_theme_after_topic_form_content' => __("After Content", "gd-taxonomies-tools"),
    'bbp_theme_after_topic_form_tags' => __("After Tags", "gd-taxonomies-tools"),
    'bbp_theme_before_topic_form_submit_wrapper' => __("Form End", "gd-taxonomies-tools")
), $embed_topic);

$bbp_locations_reply = array_merge(array(
    'bbp_theme_before_reply_form_content' => __("Before Content", "gd-taxonomies-tools"),
    'bbp_theme_after_reply_form_content' => __("After Content", "gd-taxonomies-tools"),
    'bbp_theme_after_reply_form_tags' => __("After Tags", "gd-taxonomies-tools"),
    'bbp_theme_before_reply_form_submit_wrapper' => __("Form End", "gd-taxonomies-tools")
), $embed_reply);

$gdcpt_panel_groups = array(
    new gdr2_Setting_Group('bbpress_active', __("bbPress Integration", "gd-taxonomies-tools"), __("Plugin will allow assigining meta boxes to individual forums to expand the topic and reply forms with extra fields and data.", "gd-taxonomies-tools")),
    new gdr2_Setting_Group('bbpress_default', __("Default Meta Boxes", "gd-taxonomies-tools"), __("All forums will use these meta boxes if you don't change it for individual forums from their edit panels.", "gd-taxonomies-tools")),
    new gdr2_Setting_Group('bbpress_embed', __("Embedding Content", "gd-taxonomies-tools"), __("Auto embed meta boxes results into topic and replies and control visibility of custom data.", "gd-taxonomies-tools")),
    new gdr2_Setting_Group('bbpress_js_css', __("Styling and JavaScript", "gd-taxonomies-tools"), __("Auto embed meta boxes results into topic and replies and control visibility of custom data.", "gd-taxonomies-tools"))
);

$gdcpt_panel_settings = array(
    new gdr2_Setting_Element('modules', 'active', 'bbpress', 'bbpress_active', __("Active", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'active')),
    new gdr2_Setting_Element('modules', 'metabox_topic', 'bbpress', 'bbpress_default', __("Topic Metabox", "gd-taxonomies-tools"), '', gdr2_Setting_Type::SELECT, gdtt_mod('bbpress', 'metabox_topic'), 'array', $bbp_boxes),
    new gdr2_Setting_Element('modules', 'metabox_location_topic', 'bbpress', 'bbpress_default', __("Topic Location", "gd-taxonomies-tools"), '', gdr2_Setting_Type::SELECT, gdtt_mod('bbpress', 'metabox_location_topic'), 'array', $bbp_locations_topic),
    new gdr2_Setting_Element('modules', 'metabox_reply', 'bbpress', 'bbpress_default', __("Reply Metabox", "gd-taxonomies-tools"), '', gdr2_Setting_Type::SELECT, gdtt_mod('bbpress', 'metabox_reply'), 'array', $bbp_boxes),
    new gdr2_Setting_Element('modules', 'metabox_location_reply', 'bbpress', 'bbpress_default', __("Reply Location", "gd-taxonomies-tools"), '', gdr2_Setting_Type::SELECT, gdtt_mod('bbpress', 'metabox_location_reply'), 'array', $bbp_locations_reply),
    new gdr2_Setting_Element('modules', 'metabox_fieldset', 'bbpress', 'bbpress_default', __("Fieldset Container", "gd-taxonomies-tools"), __("Metabox will be placed in the form fieldset, with metabox name as fieldset label.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'metabox_fieldset')),
    new gdr2_Setting_Element('modules', 'embed_active', 'bbpress', 'bbpress_embed', __("Active", "gd-taxonomies-tools"), __("Auto embed extra fields into the topics and replies.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'embed_active')),
    new gdr2_Setting_Element('modules', 'embed_author', 'bbpress', 'bbpress_embed', __("For Author", "gd-taxonomies-tools"), __("Topic or reply author will be able to see embedded data.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'embed_author')),
    new gdr2_Setting_Element('modules', 'embed_anyone', 'bbpress', 'bbpress_embed', __("For Anyone", "gd-taxonomies-tools"), __("Anyone will be able to see embedded data.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'embed_anyone')),
    new gdr2_Setting_Element('modules', 'embed_roles', 'bbpress', 'bbpress_embed', __("For User Roles", "gd-taxonomies-tools"), __("User roles that will be able to see embedded data.", "gd-taxonomies-tools"), gdr2_Setting_Type::SELECT_CHECK, gdtt_mod('bbpress', 'embed_roles'), 'array', $wp_roles->role_names),
    new gdr2_Setting_Element('modules', 'embed_js', 'bbpress', 'bbpress_js_css', __("Embed JavaScript", "gd-taxonomies-tools"), __("Additional jQueryUI powered code used by some of the control. It will be added to footer.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'embed_js')),
    new gdr2_Setting_Element('modules', 'embed_css', 'bbpress', 'bbpress_js_css', __("Embed CSS", "gd-taxonomies-tools"), __("Default CSS will be added to page header. If you want to include your own styling, you can disable this option.", "gd-taxonomies-tools"), gdr2_Setting_Type::BOOLEAN, gdtt_mod('bbpress', 'embed_css'))
);

?>