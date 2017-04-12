<?php

if (!defined('ABSPATH')) exit;

class gdCPTModules_Save {
    public $bool_values = array(true, false);
    public $_settings_admin = null;

    function __construct() {
        global $gdtt;

        $settings = $this->modules();
        foreach ($settings as $el) {
            $key = $_POST['gdr2_base'].$el->panel.'_'.$el->name;
            $value = $this->parse_post_element($key, $el->input);
            $gdtt->mod_set($el->panel, $el->name, $value);
        }

        update_option('gd-taxonomy-tools-modules', $gdtt->mods);

        $response = new gdrClass(array("status" => "ok", 'title' => __("Settings", "gd-taxonomies-tools"), "msg" => __("Settings Saved.", "gd-taxonomies-tools")));

        die(json_encode($response));
    }

    public function modules() {
        if (is_null($this->_settings_admin)) {
            require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
            require_once(GDTAXTOOLS_PATH.'code/modules/admin.php');

            $this->_settings_admin = new gdCPTModules_Admin();
            $this->_settings_admin->panels();
        }

        return $this->_settings_admin->settings();
    }

    public function parse_post_element($key, $input) {
        $value = null;

        switch ($input) {
            case 'x_by_y':
                $value = $_POST[$key]['x'].'x'.$_POST[$key]['y'];
                break;
            case 'html':
            case 'text_rich':
                $value = stripslashes(htmlentities($_POST[$key], ENT_QUOTES, GDR2_CHARSET));
                break;
            case 'bool':
            case 'boolean':
                $value = isset($_POST[$key]) ? $this->bool_values[0] : $this->bool_values[1];
                break;
            case 'number':
                $value = intval($_POST[$key]);
                break;
            case 'text_list':
            case 'list':
                $value = gdr2_split_textarea($_POST[$key]);
                break;
            case 'media':
                $value = 0;
                if ($_POST[$key] != '') {
                    $value = intval(substr($_POST[$key], 3));
                }
                break;
            case 'skip':
            case 'info':
                $value = null;
                break;
            case 'select_check':
            case 'select_multi':
            case 'select_grouped_multi':
                $value = (array)$_POST[$key];
                if ($value[0] == '(all)') {
                    unset($value[0]);
                    $value = array_values($value);
                }
                break;
            default:
            case 'text':
            case 'image':
            case 'text_block':
            case 'hidden':
            case 'select':
            case 'select_radio':
            case 'select_grouped':
                $value = strip_tags($_POST[$key]);
                break;
        }

        return $value;
    }
}

$mod_save = new gdCPTModules_Save();

?>