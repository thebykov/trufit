<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Setting_Group extends gdr2_Setting_Group {
    public function render_save_button($type = 'settings', $scope = 'site') {
        echo '<button name="gdr2-save" class="pressbutton">'.__("Save", "gd-taxonomies-tools").'</button>';
    }

    public function render_link_button($type = 'settings', $scope = 'site') {
        echo '<button href="'.$this->link.'" class="linkbutton">'.__("Information", "gd-taxonomies-tools").'</button>';
    }
}

class gdCPTRender extends gdr2_Settings_Render {
    var $base = "";

    public function render($el, $type = "settings", $scope = "site") {
        $id_base = "";
        $element = $el->name;
        $value = $el->value;
        $name_base = $this->base.$element;

        $call_function = array(&$this, "render_".$el->input);
        $this->call($call_function, $el, $value, $name_base, $id_base);
    }

    public function render_button($element, $value, $name_base, $id_base, $cls = "") {
        echo sprintf('<a class="pressbutton" href="%s">%s</a>', $element->args["href"], $element->args["link"]);
    }
}

?>