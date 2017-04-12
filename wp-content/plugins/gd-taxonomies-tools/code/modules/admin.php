<?php

if (!defined('ABSPATH')) exit;

class gdCPTModules_Admin extends gdr2_Settings_Admin {
    public $modules = array();
    public $settings = array('__status__' => array());

    public function panels() {
        global $gdtt;

        $panels = array(
            new gdr2_Setting_Panel('__status__', __("Modules", "gd-taxonomies-tools"), __("activity status.", "gd-taxonomies-tools"), '', array(
                new gdr2_Setting_Group('internal', __("Internal Modules", "gd-taxonomies-tools"), __("Activation of the modules built into plugin.", "gd-taxonomies-tools")),
                new gdr2_Setting_Group('external', __("External Modules", "gd-taxonomies-tools"), __("Activation of the modules loaded from external sources.", "gd-taxonomies-tools"))
            )),
        );

        foreach ($gdtt->loaded_modules as $name => $info) {
            $location = $info['location'];

            if ($location == '__internal__') {
                $path = GDTAXTOOLS_PATH.'code/modules/'.$name.'/';
            } else if (substr($location, 0, 11) == '__plugin__:') {
                $location = substr($location, 11);
                $path = WP_PLUGIN_DIR.'/'.$location.'/code/';
            } else if (substr($location, 0, 11) == '__folder__:') {
                $location = trim(substr($location, 11), "/");
                $path = WP_CONTENT_DIR.'/'.$location.'/';
            }

            if (file_exists($path.'defaults.php')) {
                $this->modules[$name][] = $location != '__internal__' ? 'external' : 'internal';

                require_once($path.'defaults.php');

                $module = 'gdCPTModuleInfo_'.$name;
                $module = new $module();

                $this->modules[$name][] = $module->name;
                $this->modules[$name][] = $module->description;

                if (file_exists($path.'settings.php')) {
                    require_once($path.'settings.php');

                    $this->settings[$name] = $gdcpt_panel_settings;
                    $panels[] = new gdr2_Setting_Panel($name, $module->name, $module->subtitle, '', $gdcpt_panel_groups);
                }
            }
            
        }

        return $panels;
    }

    public function settings($panel = '') {
        foreach ($this->modules as $name => $loc) {
            $this->settings['__status__'][] = new gdr2_Setting_Element('modules', $name, '__status__', $loc[0], $loc[1], $loc[2], gdr2_Setting_Type::BOOLEAN, gdtt_mod('__status__', $name));
        }

        if ($panel == '') {
            $all = array();

            foreach ($this->settings as $key => $s) {
                $all = array_merge($all, $s);
            }
            return $all;
        } else {
            return $this->settings[$panel];
        }
    }
}

?>