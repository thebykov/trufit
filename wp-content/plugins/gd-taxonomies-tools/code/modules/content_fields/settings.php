<?php

if (!defined('ABSPATH')) exit;

$gdcpt_panel_groups = array(
    new gdr2_Setting_Group('content_fields_fields', __("List of Included Custom Fields", "gd-taxonomies-tools"), __("Which of the custom fields to activate.", "gd-taxonomies-tools"))
);

$gdcpt_panel_settings = array(
    new gdr2_Setting_Element('modules', 'users', 'content_fields', 'content_fields_fields', __("Users", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('content_fields', 'users')),
    new gdr2_Setting_Element('modules', 'posts', 'content_fields', 'content_fields_fields', __("Posts", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('content_fields', 'posts')),
    new gdr2_Setting_Element('modules', 'terms', 'content_fields', 'content_fields_fields', __("Terms", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('content_fields', 'terms')),
);

?>