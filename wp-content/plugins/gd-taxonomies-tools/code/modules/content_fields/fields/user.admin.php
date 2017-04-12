<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Content_User extends gdCPT_Core_Field_Admin {
    public $_name = 'user';
    public $_repeater = true;
    public $_js_hooks = array(
        'edit' => 'gdcpt_mod_ct_user_edit',
        'save' => 'gdcpt_mod_ct_user_save',
        'change' => 'gdcpt_mod_ct_user_change'
    );

    function __construct() {
        parent::__construct();

        $this->_class = __("Content Types", "gd-taxonomies-tools");
        $this->_label = __("User", "gd-taxonomies-tools");
        $this->_description = __("Select user field", "gd-taxonomies-tools");

        add_action('admin_footer-post.php', array(&$this, 'load_dialog'));
        add_action('admin_footer-post-new.php', array(&$this, 'load_dialog'));
    }

    public function load_dialog() {
        require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/user.dialog.php');
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function get_values($field, $functions_list = array()) {
        $custom_usercls_values = $this->get_valid_user_classes();

        return '<strong>'.__("user class", "gd-taxonomies-tools").'</strong><br/><em>'.$custom_usercls_values[$field['values']].'</em>';
    }

    public function shortcode_attributes() {
        return array(
            'display' => array('attr' => 'name', 'type' => 'dropdown', 'default' => 'name', 'label' => __("Display", "gd-taxonomies-tools"), 'description' => __("Select how to display selected value.", "gd-taxonomies-tools"), 'values' => array('name' => __("Name", "gd-taxonomies-tools"), 'id' => __("ID", "gd-taxonomies-tools"), 'user_name' => __("Username", "gd-taxonomies-tools"), 'link' => __("Link", "gd-taxonomies-tools"), 'url' => __("URL", "gd-taxonomies-tools")))
        );
    }

    private function get_valid_user_classes() {
        global $wp_roles;

        $usr_classes = array(
            'misc_all' => __("All Users", "gd-taxonomies-tools"),
            'misc_authors' => __("Only Authors", "gd-taxonomies-tools")
        );

        foreach ($wp_roles->role_names as $role => $title) {
            $usr_classes['role_'.$role] = __("Role", "gd-taxonomies-tools").': '.$title;
        }

        return apply_filters('gdcpt_mod_cf_user_classes', $usr_classes);
    }

    public function render($value, $field, $id, $name) {
        $usr = $this->get_valid_user_classes();

        $value = intval($value);
        $user_name = __("None Selected", "gd-taxonomies-tools");

        if ($value > 0) {
            $user = get_user_by('id', $value);
            $user_name = $user->data->user_nicename;
        }

        $render = '<div class="gdtt-cf-icons">';
            $render.= '<div class="gdtt-ui-button"><span gdtt-id="'.$id.'" gdtt-usr="'.$field['values'].'" title="'.__("Select user from", "gd-taxonomies-tools").': '.$usr[$field['values']].'" class="ui-icon ui-icon-contact"></span></div>';
        $render.= '</div><div class="gdtt-cf-term">';
            $render.= '<span class="gdtt-field-title-half">'.__("User ID", "gd-taxonomies-tools").':</span>';
            $render.= '<input class="gdtt-field-text gdtt-field-text-mini" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field['values']).'" />';
            $render.= '<span class="gdtt-field-spacer"></span>';
            $render.= '<span class="gdtt-text-block" id="'.$id.'__title">'.$user_name.'</span>';
        $render.= '</div>';

        return $render;
    }

    public function get_default($field) {
        return 0;
    }

    public function embed_html() { 
        $custom_usercls_values = $this->get_valid_user_classes();

        ?>
        <div class="gdtt-element-field gdtt-element-user">
            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
            <div class="gdtt-element-block">
                <label><?php _e("User Selection", "gd-taxonomies-tools"); ?>:</label>
                    <?php gdr2_UI::draw_select($custom_usercls_values, '', '', 'gdtt-cfe-usercls'); ?>
                <div class="clear"></div>
            </div>
        </div>
    <?php }

    public function embed_js() {
        parent::embed_js(); ?>

        function gdcpt_mod_ct_user_edit(to_edit) {
            jQuery(".gdtt-element-user").show();

            jQuery("#gdtt-cfe-usercls").val(gdCPTAdmin.tmp.custom_fields[to_edit].values);
            jQuery("#gdtt-cfe-values").val("");
        }

        function gdcpt_mod_ct_user_save(field) {
            field.values = jQuery("#gdtt-cfe-usercls").val().trim();
            return field;
        }

        function gdcpt_mod_ct_user_change() {
            jQuery(".gdtt-element-user").show();
        }

    <?php }
}

?>