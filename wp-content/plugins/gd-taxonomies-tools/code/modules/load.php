<?php

if (!defined('ABSPATH')) exit;

/**
 * Is module already registered with the plugin.
 *
 * @param string $module name of the module
 * @return bool true if module is registered.
 */
function gdcpt_has_module($module) {
    global $gdtt;
    return $gdtt->has_module($module);
}

/**
 * Check if the module is active.
 *
 * @param string $module name of the module
 * @return bool true if module is active.
 */
function gdcpt_is_module_active($module) {
    global $gdtt;
    return $gdtt->is_module_active($module);
}

/**
 * Register new module that is writen as a plugin. This function must be called
 * inside 'gdcpt_modules_init' action.
 *
 * @param string $code code name of the module
 * @param string $plugin_name name of the plugin folder
 */
function gdcpt_register_module_in_plugin($code, $plugin_name, $scope = 'site') {
    global $gdtt;
    $gdtt->loaded_modules[$code] = array('location' => '__plugin__:'.$plugin_name, 'scope' => $scope);
}

/**
 * Register new module that is located in a folder. This function must be called
 * inside 'gdcpt_modules_init' action. Folder path must be relation to WordPress
 * wp-content folder.
 *
 * @param string $code code name of the module
 * @param string $folder_path path to module folder
 */
function gdcpt_register_module_in_folder($code, $folder_path, $scope = 'site') {
    global $gdtt;
    $gdtt->loaded_modules[$code] = array('location' => '__folder__:'.$folder_path, 'scope' => $scope);
}

/**
 * Register new custom field type. Classes for admin and display part of the 
 * custom field, must be loaded at this point.
 * 
 * @param string $code name of the custom field
 * @param string $class_admin name of the admin side class
 * @param string $class_display name of the display class
 * @param string $source source indentifier of the custom field
 */
function gdcpt_register_custom_field($code, $class_admin, $class_display, $source = '__builtin') {
    global $gdtt_fields;
    $gdtt_fields->register($code, $class_admin, $class_display, $source);
}

?>