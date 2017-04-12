<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Field_Admin_Content_Term extends gdCPT_Core_Field_Admin {
    public $_name = 'term';
    public $_repeater = true;
    public $_js_hooks = array(
        'edit' => 'gdcpt_mod_ct_term_edit',
        'save' => 'gdcpt_mod_ct_term_save',
        'change' => 'gdcpt_mod_ct_term_change'
    );

    function __construct() {
        parent::__construct();

        $this->_class = __("Content Types", "gd-taxonomies-tools");
        $this->_label = __("Taxonomy Term", "gd-taxonomies-tools");
        $this->_description = __("Select term from taxonomy field", "gd-taxonomies-tools");

        add_action('admin_footer-post.php', array(&$this, 'load_dialog'));
        add_action('admin_footer-post-new.php', array(&$this, 'load_dialog'));
    }

    public function load_dialog() {
        require_once(GDTAXTOOLS_PATH.'code/modules/content_fields/fields/term.dialog.php');
    }

    public function clean($value, $field) {
        return trim(strip_tags($value));
    }

    public function check($value, $field) {
        return $value !== '';
    }

    public function get_values($field, $functions_list = array()) {
        $tax = get_taxonomy($field['values']);

        return '<strong>'.__("taxonomy", "gd-taxonomies-tools").'</strong><br/><em>'.$tax->labels->singular_name.'</em>';
    }

    public function shortcode_attributes() {
        return array(
            'display' => array('attr' => 'name', 'type' => 'dropdown', 'default' => 'name', 'label' => __("Display", "gd-taxonomies-tools"), 'description' => __("Select how to display selected value.", "gd-taxonomies-tools"), 'values' => array('name' => __("Name", "gd-taxonomies-tools"), 'id' => __("ID", "gd-taxonomies-tools"), 'slug' => __("Slug", "gd-taxonomies-tools"), 'link' => __("Link", "gd-taxonomies-tools")))
        );
    }

    public function render($value, $field, $id, $name) {
        $tax = get_taxonomy($field['values']);

        $value = intval($value);
        $term_name = __("None Selected", "gd-taxonomies-tools");

        if ($value > 0) {
            $term = get_term($value, $field['values']);
            $term_name = $term->name;
        }

        $render = '<div class="gdtt-cf-icons">';
            $render.= '<div class="gdtt-ui-button"><span gdtt-id="'.$id.'" gdtt-tax="'.$field['values'].'" title="'.__("Select term from", "gd-taxonomies-tools").': '.$tax->labels->name.'" class="ui-icon ui-icon-tag"></span></div>';
        $render.= '</div><div class="gdtt-cf-term">';
            $render.= '<span class="gdtt-field-title-half">'.__("Term ID", "gd-taxonomies-tools").':</span>';
            $render.= '<input class="gdtt-field-text gdtt-field-text-mini" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" gdtt-reset="'.$this->get_default($field).'" />';
            $render.= '<span class="gdtt-field-spacer"></span>';
            $render.= '<span class="gdtt-text-block" id="'.$id.'__title">'.$term_name.'</span>';
        $render.= '</div>';

        return $render;
    }

    public function get_default($field) {
        return 0;
    }

    public function embed_html() { 
        global $wp_taxonomies;

        $custom_tax_values = array();
        foreach ($wp_taxonomies as $taxonomy => $cnt) {
            $custom_tax_values[$taxonomy] = $cnt->labels->name;
        }

        ?>
        <div class="gdtt-element-field gdtt-element-taxonomy">
            <h4 class="ui-widget-header"><?php _e("Field Settings", "gd-taxonomies-tools") ?>:</h4>
            <div class="gdtt-element-block">
                <label><?php _e("Taxonomy", "gd-taxonomies-tools"); ?>:</label>
                    <?php gdr2_UI::draw_select($custom_tax_values, '', '', 'gdtt-cfe-taxname'); ?>
                <div class="clear"></div>
            </div>
        </div>
    <?php }

    public function embed_js() {
        parent::embed_js(); ?>

        function gdcpt_mod_ct_term_edit(to_edit) {
            jQuery(".gdtt-element-taxonomy").show();

            jQuery("#gdtt-cfe-taxname").val(gdCPTAdmin.tmp.custom_fields[to_edit].values);
            jQuery("#gdtt-cfe-values").val("");
        }

        function gdcpt_mod_ct_term_save(field) {
            field.values = jQuery("#gdtt-cfe-taxname").val().trim();
            return field;
        }

        function gdcpt_mod_ct_term_change() {
            jQuery(".gdtt-element-taxonomy").show();
        }

    <?php }
}

?>