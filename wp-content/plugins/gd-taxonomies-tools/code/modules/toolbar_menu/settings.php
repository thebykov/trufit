<?php

if (!defined('ABSPATH')) exit;

$gdcpt_panel_groups = array(
    new gdr2_Setting_Group('toolbar_menu_active', __("Integrate with Toolbar", "gd-taxonomies-tools"), __("Integrate plugin specific menu into WordPress toolbar.", "gd-taxonomies-tools"))
);

$gdcpt_panel_settings = array(
    new gdr2_Setting_Element('modules', 'active', 'toolbar_menu', 'toolbar_menu_active', __("Active", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('toolbar_menu', 'active')),
    new gdr2_Setting_Element('modules', 'icon', 'toolbar_menu', 'toolbar_menu_active', __("Menu Icon", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('toolbar_menu', 'icon')),
    new gdr2_Setting_Element('modules', 'create_new', 'toolbar_menu', 'toolbar_menu_active', __("Create New Links", "gd-taxonomies-tools"), '', gdr2_Setting_Type::BOOLEAN, gdtt_mod('toolbar_menu', 'create_new'))
);

?>