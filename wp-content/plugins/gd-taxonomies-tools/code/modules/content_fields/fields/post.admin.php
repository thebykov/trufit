<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Content_Post extends gdCPT_Core_Field_Admin {
    public $_name = 'post';
    public $_repeater = true;
    public $_js_hooks = array(
        'edit' => 'gdcpt_mod_ct_post_edit',
        'save' => 'gdcpt_mod_ct_post_save',
        'change' => 'gdcpt_mod_ct_post_change'
    );

    function __construct() {
        parent::__construct();

        $this->_class = __("Content Types", "gd-taxonomies-tools");
        $this->_label = __("Post", "gd-taxonomies-tools");
        $this->_description = __("Select post for post type field", "gd-taxonomies-tools");

        add_action('admin_footer-post.php', array(&$this, 'load_dialog'));
        add_action('admin_footer-post-new.php', array(&$this, 'load_dialog'));
    }

    public function load_dialog() {
        require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/post.dialog.php');
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function get_values($field, $functions_list = array()) {
        $cpt = get_post_type_object($field['values']);

        return '<strong>'.__("post type", "gd-taxonomies-tools").'</strong><br/><em>'.$cpt->labels->singular_name.'</em>';
    }

    public function shortcode_attributes() {
        return array(
            'display' => array('attr' => 'name', 'type' => 'dropdown', 'default' => 'name', 'label' => __("Display", "gd-taxonomies-tools"), 'description' => __("Select how to display selected value.", "gd-taxonomies-tools"), 'values' => array('name' => __("Name", "gd-taxonomies-tools"), 'id' => __("ID", "gd-taxonomies-tools"), 'slug' => __("Slug", "gd-taxonomies-tools"), 'link' => __("Link", "gd-taxonomies-tools")))
        );
    }

    public function render($value, $field, $id, $name) {
        $cpt = get_post_type_object($field['values']);

        $value = intval($value);
        $post_name = __("None Selected", "gd-taxonomies-tools");

        if ($value > 0) {
            $post = get_post($value);
            $post_name = $post->post_title;
        }

        $render = '<div class="gdtt-cf-icons">';
            $render.= '<div class="gdtt-ui-button"><span gdtt-id="'.$id.'" gdtt-cpt="'.$field['values'].'" title="'.__("Select post from", "gd-taxonomies-tools").': '.$cpt->labels->name.'" class="ui-icon ui-icon-note"></span></div>';
        $render.= '</div><div class="gdtt-cf-term">';
            $render.= '<span class="gdtt-field-title-half">'.__("Post ID", "gd-taxonomies-tools").':</span>';
            $render.= '<input class="gdtt-field-text gdtt-field-text-mini" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';
            $render.= '<span class="gdtt-field-spacer"></span>';
            $render.= '<span class="gdtt-text-block" id="'.$id.'__title">'.$post_name.'</span>';
        $render.= '</div>';

        return $render;
    }

    public function get_default($field) {
        return 0;
    }

    public function embed_html() { 
        $wp_post_types = gdtt_get_public_post_types(true);

        $custom_post_types = array();
        foreach ($wp_post_types as $cpt => $cnt) {
            $custom_post_types[$cpt] = $cnt->labels->name;
        }

        ?>
        <div class="gdtt-element-field gdtt-element-posttype">
            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
            <div class="gdtt-element-block">
                <label><?php _e("Post Type", "gd-taxonomies-tools"); ?>:</label>
                    <?php gdr2_UI::draw_select($custom_post_types, '', '', 'gdtt-cfe-cptname'); ?>
                <div class="clear"></div>
            </div>
        </div>
    <?php }

    public function embed_js() {
        parent::embed_js(); ?>

        function gdcpt_mod_ct_post_edit(to_edit) {
            jQuery(".gdtt-element-posttype").show();

            jQuery("#gdtt-cfe-cptname").val(gdCPTAdmin.tmp.custom_fields[to_edit].values);
            jQuery("#gdtt-cfe-values").val("");
        }

        function gdcpt_mod_ct_post_save(field) {
            field.values = jQuery("#gdtt-cfe-cptname").val().trim();
            return field;
        }

        function gdcpt_mod_ct_post_change() {
            jQuery(".gdtt-element-posttype").show();
        }

    <?php }
}

?>