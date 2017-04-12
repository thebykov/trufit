<?php

if (!defined('ABSPATH')) exit;

class gdCPTAdmin {
    var $css_inline = array();
    var $o;
    var $p;
    var $t;
    var $sf;

    var $admin_plugin;
    var $admin_plugin_page;
    var $script;
    var $taxes;
    var $posts;
    var $indexer;

    var $remove_meta = array();
    var $page_ids = array();
    var $tutorials = array();

    var $script_debug = false;

    function __construct($o, $p, $t, $sf) {
        $this->o = $o;
        $this->p = $p;
        $this->t = $t;
        $this->sf = $sf;

        $this->init();

        $this->script = $_SERVER['PHP_SELF'];
        $this->script = explode('/', $this->script);
        $this->script = end($this->script);

        $this->taxes = array();
        $this->posts = array();

        foreach ($this->t as $tx) {
            $this->taxes[] = $tx['name'];
            $this->indexer['tax'][$tx['name']] = $tx['id'];
        }

        foreach ($this->p as $pt) {
            $this->posts[] = $pt['name'];
            $this->indexer['cpt'][$pt['name']] = $pt['id'];
        }
    }

    private function init() {
        add_action('wp_ajax_gdcpt_save_settings', array(&$this, 'save_settings'));

        add_action('plugins_loaded', array(&$this, 'load_modules'), 5);
        add_action('plugins_loaded', array(&$this, 'set_script_debug'));

        add_action('admin_init', array(&$this, 'admin_init_special'), 5);
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('admin_menu', array(&$this, 'admin_meta'));
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));

        add_action('admin_head', array(&$this, 'admin_head'));
        add_action('admin_footer', array(&$this, 'admin_footer'));

        add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);
        add_filter('plugin_action_links_gd-taxonomies-tools/gd-taxonomies-tools.php', array(&$this, 'plugin_actions'));
        add_filter('attachment_fields_to_edit', array(&$this, 'add_media_upload_controls'), 100, 2);
        add_action('admin_print_scripts-media-upload-popup', array(&$this, 'add_media_upload_javascript'), 100);
        add_filter('media_upload_tabs', array(&$this, 'remove_media_upload_tabs'));

        if (!empty($this->sf['totals']['in_menu_block'])) {
            add_action('custom_menu_order', array($this, 'admin_custom_menu_order'), 1500);
            add_action('menu_order', array($this, 'admin_menu_order'), 1500);
        }

        if (GDTAXTOOLS_WPV < 35 && $this->get('special_tax_edit_column') == 1) {
            add_action('manage_pages_columns', array(&$this, 'admin_page_columns'));
            add_action('manage_pages_custom_column', array(&$this, 'admin_columns_data'), 10, 2);
            add_action('manage_posts_columns', array(&$this, 'admin_post_columns'), 10, 2);
            add_action('manage_posts_custom_column', array(&$this, 'admin_columns_data'), 10, 2);
        }

        if ($this->get('special_cpt_right_now') == 1) {
            if (GDTAXTOOLS_WPV < 38) {
                add_action('right_now_content_table_end', array(&$this, 'dashboard_right_now'));
            } else {
                add_filter('dashboard_glance_items', array(&$this, 'dashboard_glance_items'));
            }
        }

        if ($this->o['special_cpt_disable_quickedit'] == 1) {
            add_filter('post_row_actions', array(&$this, 'post_row_actions'), 10, 2);
            add_filter('page_row_actions', array(&$this, 'post_row_actions'), 10, 2);
        }

        if ($this->get('special_cpt_menu_drafts') == 1) {
            add_action('admin_menu', array(&$this, 'admin_menu_futures'), 1000);
        }

        if ($this->get('special_cpt_menu_drafts') == 1) {
            add_action('admin_menu', array(&$this, 'admin_menu_drafts'), 1001);
        }

        if ($this->get('special_cpt_menu_archive') == 1) {
            add_action('admin_menu', array(&$this, 'admin_menu_archive'), 1002);
        }

        if ($this->get('special_tax_edit_filter') == 1) {
            add_action('restrict_manage_posts', array(&$this, 'restrict_manage_posts'));
        }

        if (GDTAXTOOLS_WPV < 39) {
            add_filter('mce_external_plugins', array(&$this, 'tinymce3_plugin'), 5);
            add_filter('mce_buttons', array(&$this, 'tinymce3_button'), 5);
            add_filter('tiny_mce_before_init', array(&$this, 'tinymce3_initarray'));
        } else {
            /* add_filter('mce_external_plugins', array(&$this, 'tinymce4_plugin'), 5);
            add_filter('mce_buttons', array(&$this, 'tinymce4_button'), 5);
            add_filter('tiny_mce_before_init', array(&$this, 'tinymce4_initarray')); */
        }

        foreach ($this->sf['tax'] as $tax => $features) {
            $content = false;

            if ($this->get('special_tax_term_id') == 1 && in_array('term_id', $features, true)) {
                add_filter('manage_edit-'.$tax.'_columns', array(&$this, 'tax_term_id_column'), 10, 1);
                $content = true;
            }

            if ($this->get('special_tax_term_image') == 1 && in_array('term_image', $features, true)) {
                add_filter('manage_edit-'.$tax.'_columns', array(&$this, 'tax_term_image_column'), 10, 1);
                $content = true;
            }

            if ($content) {
                add_filter('manage_'.$tax.'_custom_column', array(&$this, 'tax_term_columns_content'), 1000, 3);
            }
        }

        if ($this->get('special_tax_term_link') == 1) {
            foreach ($this->sf['tax'] as $tax => $features) {
                if (in_array('term_link', $features, true)) {
                    add_filter($tax.'_row_actions', array(&$this, 'tax_terms_links'), 10, 2);
                }
            }
        }

        add_filter('admin_body_class', array(&$this, 'admin_body_class'));
    }

    public function admin_body_class($classes) {
        if (GDTAXTOOLS_WPV > 38) {
            $classes.= ' wpv39 ';
        }

        return $classes;
    }
    
    private function tutorials() {
        $this->tutorials = array(
            array("id" => 10811, "icon" => "video",
                "title" => __("GD Custom Posts And Taxonomies Tools 3.5", "gd-taxonomies-tools"),
                "panels" => array("front", "postypes", "taxs", "settings", "tools", "roles", "metas")),

            array("id" => 5613, "icon" => "video",
                "title" => __("Walktrough GD Custom Posts And Taxonomies Tools 3.0", "gd-taxonomies-tools"),
                "panels" => array("front", "postypes", "taxs", "settings", "tools", "roles", "metas")),

            array("id" => 6718, "icon" => "video",
                "title" => __("Custom permalinks for custom post types", "gd-taxonomies-tools"),
                "panels" => array("postypes")),

            array("id" => 6720, "icon" => "video",
                "title" => __("Custom post types admin icons and location", "gd-taxonomies-tools"),
                "panels" => array("postypes")),

            array("id" => 5614, "icon" => "video",
                "title" => __("Custom Meta Boxes in GD CPT Tools 3.0", "gd-taxonomies-tools"),
                "panels" => array("metas")),

            array("id" => 7107, "icon" => "text",
                "title" => __("Enhanced custom post types URL rewriting", "gd-taxonomies-tools"),
                "panels" => array("postypes")),

            array("id" => 5616, "icon" => "video",
                "title" => __("Advanced Roles Capabilties in GD CPT Tools 3.0", "gd-taxonomies-tools"),
                "panels" => array("roles")),

            array("id" => 4593, "icon" => "video",
                "title" => __("Edit default post types and taxonomies settings", "gd-taxonomies-tools"),
                "panels" => array("postypes", "taxs")),

            array("id" => 4591, "icon" => "video",
                "title" => __("Enhance WordPress: Taxonomy terms Images", "gd-taxonomies-tools"),
                "panels" => array("settings", "taxs")),

            array("id" => 3745, "icon" => "text",
                "title" => __("Additional templates for posts and date archives", "gd-taxonomies-tools"),
                "panels" => array("settings")),

            array("id" => 3227, "icon" => "text",
                "title" => __("Custom post types archive templates", "gd-taxonomies-tools"),
                "panels" => array("settings")),

            array("id" => 4769, "icon" => "video",
                "title" => __("Migrating to GD Custom Posts And Taxonomies Tools", "gd-taxonomies-tools"),
                "panels" => array("front", "postypes", "taxs"))
        );
    }

    function set_script_debug() {
        $this->script_debug = GDTAXTOOLS_JS_DEV;

        if (!$this->script_debug) {
            $this->script_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;
        }
    }

    function load_modules() {
        global $gdtt;

        foreach ($gdtt->loaded_modules as $module => $info) {
            $location = $info['location'];

            if (gdcpt_is_module_active($module)) {
                $path = '';

                if ($location == '__internal__') {
                    $path = GDTAXTOOLS_PATH.'code/modules/'.$module.'/';
                } else if (substr($location, 0, 11) == '__plugin__:') {
                    $location = substr($location, 11);
                    $path = WP_PLUGIN_DIR.'/'.$location.'/code/';
                } else if (substr($location, 0, 11) == '__folder__:') {
                    $location = trim(substr($location, 11), '/');
                    $path = WP_CONTENT_DIR.'/'.$location.'/';
                }

                if (file_exists($path.'admin.php')) {
                    require_once($path.'admin.php');
                }
            }
        }
    }

    function save_settings() {
        check_ajax_referer('gd-cpt-tools');

        switch ($_POST['gdr2_action']) {
            case 'gdr2_modules':
                require_once(GDTAXTOOLS_PATH.'code/modules/save.php');
                break;
        }
    }

    function tinymce3_button($buttons) {
        array_push($buttons, 'separator', 'gdttTinyMain');

        return $buttons;
    }

    function tinymce3_plugin($plugin_array) {
        $plugin_array['gdttTinyMain'] = GDTAXTOOLS_URL.'tinymce/plugin3.js';

        return $plugin_array;
    }

    function tinymce3_initarray($init) {
        global $post_type;
        if (!$post_type) $post_type = 'post';

        $taxes = gdtt_get_taxonomies_for_post_types($post_type, 'object');
        $ids = $nms = $bse = array();
        foreach ($taxes as $tax) {
            if ($tax->show_ui) {
                $ids[] = $tax->name;
                $nms[] = $tax->label;
                $bse[] = $tax->hierarchical ? 1 : 0;
            }
        }
        $init['gdtt_wordpress_version'] = GDTAXTOOLS_WPV;
        $init['gdtt_post_type'] = $post_type;
        $init['gdtt_taxonomies_ids'] = join('|', $ids);
        $init['gdtt_taxonomies_names'] = join('|', $nms);
        $init['gdtt_taxonomies_base'] = join('|', $bse);

        return $init;
    }

    function tinymce4_button($buttons) {
        array_push($buttons, 'separator', 'gdcpt_terms_ctrl');

        return $buttons;
    }

    function tinymce4_plugin($plugin_array) {
        $plugin_array['gdcpt_terms'] = GDTAXTOOLS_URL.'tinymce/plugin4.js';

        return $plugin_array;
    }

    function tinymce4_initarray($init) {
        return $this->tinymce3_initarray($init);
    }

    function admin_custom_menu_order($menu_order) {
        if (!current_user_can('edit_posts')) return false;

        global $menu;
        $menu[] = array('', 'read', 'separator-gdcpt', '', 'wp-menu-separator');
	return true;
    }

    function admin_menu_order($menu_order) {
        $new_menu_order = array();

        foreach ($menu_order as $key => $item) {
            if ($item == 'separator2') {
                $new_menu_order[] = 'separator-gdcpt';
                foreach ($this->sf['totals']['in_menu_block'] as $own) {
                    $new_menu_order[] = $own;
                }
                $new_menu_order[] = $item;
            } else if ($item != 'separator-gdcpt' && !in_array($item, $this->sf['totals']['in_menu_block'])) {
                $new_menu_order[] = $item;
            }
        }

        return $new_menu_order;
    }

    function get($setting) {
        return $this->o[$setting];
    }

    function post_row_actions($actions, $post) {
        $post_type = $post->post_type;
        if (isset($this->sf['cpt'][$post_type])) {
            if (in_array('disable_quickedit', $this->sf['cpt'][$post_type], true)) {
                unset($actions['inline hide-if-no-js']);
            }
        }
        return $actions;
    }

    function tax_term_id_column($columns) {
        $i = 0;
        $new_columns = array();
        foreach ($columns as $key => $value) {
            if ($i == 1) $new_columns['gdcpt_term_id'] = __("ID", "gd-taxonomies-tools");
            $new_columns[$key] = $value;
            $i++;
        }
        return $new_columns;
    }

    function tax_term_image_column($columns) {
        $columns['gdcpt_image'] = __("Image", "gd-taxonomies-tools");
        return $columns;
    }

    function tax_term_columns_content($data, $column, $id) {
        if ($column == 'gdcpt_term_id') {
            $data = $id;
        }
        if ($column == 'gdcpt_image') {
            $taxonomy = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : (isset($_POST['taxonomy']) ? $_POST['taxonomy'] : "");
            if ($taxonomy == "") return "";

            $term = intval($id);
            $image_url = gdtt_get_term_image($taxonomy, $term, 'large', 'url');
            $image = gdtt_get_term_image($taxonomy, $term, array(64, 64));

            $data = '<div class="gdtt-term-image" id="gdtt-tid-'.$term.'">';
            if ($image !== false && !is_wp_error($image)) {
                $data.= $image;
            }
            if ($image_url === false || is_wp_error($image_url)) {
                $image_url = "";
            }
            $data.= '</div><div class="gdtt-term-controls">';
            $data.= '<a class="gtc-edit" href="#'.$term.'" rel="'.$taxonomy.'"><img alt="" src="'.GDTAXTOOLS_URL.'gfx/blank.gif" /></a>';
            $data.= '<a'.($image === false || is_wp_error($image) ? ' style="display: none;"' : "").' id="gdtt-tia-'.$term.'" class="gtc-delete" href="#'.$term.'" rel="'.$taxonomy.'"><img alt="" src="'.GDTAXTOOLS_URL.'gfx/blank.gif" /></a>';
            $data.= '<a'.($image === false || is_wp_error($image) ? ' style="display: none;"' : "").' id="gdtt-tip-'.$term.'" class="gtc-preview" href="'.$image_url.'"><img alt="" src="'.GDTAXTOOLS_URL.'gfx/blank.gif" /></a>';
            $data.= '</div>';
        }
        return $data;
    }

    function tax_terms_links($actions, $tag) {
        $actions['view'] = '<a href="'.get_term_link($tag, $tag->taxonomy).'">'.__("View", "gd-taxonomies-tools").'</a>';
        return $actions;
    }

    function restrict_manage_posts() {
        global $post_type, $wp_query;
        foreach ($this->sf["tax"] as $tax => $features) {
            if (in_array("edit_filter", $features, true)) {
                if (is_object_in_taxonomy($post_type, $tax)) {
                    $term = isset($wp_query->query[$tax]) ? $wp_query->query[$tax] : "";
                    $terms = wp_count_terms($tax);
                    if ($terms > 0) {
                        $taxonomy = get_taxonomy($tax);
                        $dropdown_options = array('selected' => $term, 'orderby' => 'name',
                            'taxonomy' => $tax, 'name' => $tax, 'hierarchical' => 1, 'hide_empty' => 0, 
                            'show_option_all' => __("View all", "gd-taxonomies-tools")." ".$taxonomy->labels->name,
                            'show_count' => 0, 'walker' => new gdttWalker_TaxonomyDropdown());
                        wp_dropdown_categories($dropdown_options);
                    }
                }
            }
        }
    }

    function add_media_upload_javascript() {
        if (isset($_REQUEST['gdtt_term'])) {
            $url = $this->script_debug ? GDTAXTOOLS_URL.'js/src/media.js' : GDTAXTOOLS_URL.'js/media.js';
            wp_enqueue_script('gdtt-media-upload-popup', $url, array('jquery'));
            wp_localize_script('gdtt-media-upload-popup', 'gdttMedia', array(
                'term' => isset($_GET['gdtt_term']) ? $_GET['gdtt_term'] : '',
                'taxonomy' => isset($_GET['gdtt_tax']) ? $_GET['gdtt_tax'] : '',
                'nonce' => wp_create_nonce('gd-cpt-tools')
            ));
        }
    }

    function remove_media_upload_tabs($tabs) {
        if (isset($_REQUEST['gdtt_term'])) {
            unset($tabs['type_url']);
        }

        return $tabs;
    }

    function add_media_upload_controls($links, $post) {
        if (isset($_REQUEST["gdtt_term"])) {
            $link = '<a rel="'.$post->ID.'" class="button-primary gtc-attach" href="#">'.__("Attach Image to Term", "gd-taxonomies-tools").'</a>';
            $link.= '<script type="text/javascript">jQuery(document).ready(function() { jQuery("td.savesend input.button").remove(); });</script>';
            $links['image-size']['extra_rows']['gdtt-media-upload-button']['html'] = $link;
        }
        return $links;
    }

    function dashboard_glance_items($items = array()) {
        foreach ($this->sf['cpt'] as $post_type => $features) {
            if (in_array('right_now', $features, true)) {
                $cpt = get_post_type_object($post_type);
                $num_posts = wp_count_posts($post_type);
                $num = number_format_i18n($num_posts->publish);
                $text = _n($cpt->labels->singular_name, $cpt->labels->name, intval($num_posts->publish));

                if (current_user_can($cpt->cap->edit_posts)) {
                    $items[] = "<a href='edit.php?post_type=".$post_type."'>".$num." ".$text."</a>";
                } else {
                    $items[] = $num." ".$text;
                }
            }
        }

        return $items;
    }

    function dashboard_right_now() {
        foreach ($this->sf['cpt'] as $post_type => $features) {
            if (in_array('right_now', $features, true)) {
                $cpt = get_post_type_object($post_type);
                $num_posts = wp_count_posts($post_type);
                $num = number_format_i18n($num_posts->publish);
                $text = _n($cpt->labels->singular_name, $cpt->labels->name, intval($num_posts->publish));
                if (current_user_can($cpt->cap->edit_posts)) {
                    $num = "<a href='edit.php?post_type=".$post_type."'>$num</a>";
                    $text = "<a href='edit.php?post_type=".$post_type."'>$text</a>";
                }
                echo '<tr><td class="first b b_pages">'.$num.'</td>';
                echo '<td class="t pages">'.$text.'</td></tr>';
            }
        }
    }

    function admin_page_columns($columns) {
        $post_type = 'page';
        return $this->admin_post_columns($columns, $post_type);
    }

    function admin_post_columns($columns, $post_type) {
        $new_columns = array();
        $taxonomies = gdtt_get_taxonomies_for_post_types($post_type);
        $added = array();
        $done = false;

        foreach ($columns as $key => $value) {
            if (!$done && ($key == 'comments' || $key == 'date')) {
                foreach ($taxonomies as $tax) {
                    if (isset($this->sf['tax'][$tax]) && is_array($this->sf['tax'][$tax])) {
                        $sf = array_values($this->sf['tax'][$tax]);

                        if (in_array('edit_column', $sf, true)) {
                            $to_show = get_taxonomy($tax);
                            $added[] = $tax;
                            $this->css_inline[] = ".fixed .column-gdtt_".$tax."{ width: 12%; }";
                            if (!isset($to_show->labels->name)) $new_columns['gdtt_'.$tax] = $to_show->label;
                            else $new_columns['gdtt_'.$tax] = $to_show->labels->name;
                        }

                        $done = true;
                    }
                }
            }

            $new_columns[$key] = $value;
        }
        if (!empty($added)) gdtt_custom_post_types_cache();
        return $new_columns;
    }

    function admin_columns_data($column, $id) {
        if (substr($column, 0, 5) == 'gdtt_') {
            $taxonomy = substr($column, 5);
            $post_type = get_post_type($id);
            $terms = wp_get_post_terms($id, $taxonomy);

            if (!empty($terms)) {
                $out = array();
                foreach ($terms as $t) {
                    $out[] = sprintf('<a href="edit.php?%s=%s&post_type=%s">%s</a>', $taxonomy, $t->slug, $post_type,
                            esc_html(sanitize_term_field('name', $t->name, $t->term_id, $t->taxonomy, 'display')));
                }
                echo join(', ', $out);

            } else {
                _e("No terms assigned", "gd-taxonomies-tools");
            }
        }
    }

    function admin_init_special() {
        global $gdtt;
        $this->sf = $gdtt->sf;
    }

    function localize_script() {
        $values = array(
            'url' => GDTAXTOOLS_URL, 'cookie_name' => '', 'wp_version' => GDTAXTOOLS_WPV,
            'nonce' => wp_create_nonce('gd-cpt-tools'), 'ui_enhance' => $this->o['accessibility_enhancements'],
            'txt_qtip_title' => __("Option Help", "gd-taxonomies-tools"),
            'txt_qtip_error' => __("Error Description", "gd-taxonomies-tools"),
            'txt_select_field' => __("Select Field", "gd-taxonomies-tools"),
            'txt_select_box' => __("Select Meta Box", "gd-taxonomies-tools"),
            'txt_attach_preview' => __("Selected Image Preview", "gd-taxonomies-tools"),
            'txt_editor_box_missing' => __("Code and Name are required, and at least one field set!", "gd-taxonomies-tools"),
            'txt_editor_group_missing' => __("Code and Name are required, and at least one meta box!", "gd-taxonomies-tools"),
            'txt_editor_field_missing' => __("Both Code and Name are required!", "gd-taxonomies-tools"),
            'txt_editor_nothing' => __("Nothing Selected!", "gd-taxonomies-tools"),
            'preview_url' => plugins_url('/gd-taxonomies-tools/forms/iframe/preview.php'),
            'preview_title' => __("Image Preview", "gd-taxonomies-tools"),
            'select_title' => __("Select Image", "gd-taxonomies-tools"),
            'yes' => __("Yes", "gd-taxonomies-tools"),
            'no' => __("No", "gd-taxonomies-tools")
        );

        $values = apply_filters('gdcpt_localize_script_plugin', $values);

        wp_localize_script('gdtt-admin', 'gdCPTTools', $values);
    }

    function admin_init() {
        if (isset($_GET['page'])) {
            if (substr($_GET['page'], 0, 10) == 'gdtaxtools') {
                $this->admin_plugin = true;
                $this->admin_plugin_page = substr($_GET['page'], 11);

                do_action('gdcpt_admin_init', $this->admin_plugin_page);
                do_action('gdcpt_admin_init_'.$this->admin_plugin_page);
            }
        }

        $this->init_operations();
        $this->settings_operations();
        $this->init_tools();
    }

    function admin_enqueue_scripts($hook) {
        if ($this->admin_plugin) {
            $js_url = $this->script_debug ? GDTAXTOOLS_URL.'js/src/admin.js' : GDTAXTOOLS_URL.'js/admin.js';

            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-form');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');

            wp_enqueue_script('gdtt-jquery-ui', GDTAXTOOLS_URL.'js/jquery-ui.js', array('jquery'));
            wp_enqueue_script('gdtt-utilities', GDTAXTOOLS_URL.'js/utilities.js', array('jquery', 'gdtt-jquery-ui'));
            wp_enqueue_script('gdtt-admin', $js_url, array('jquery', 'gdtt-utilities'));

            wp_enqueue_style('thickbox');
            wp_enqueue_style('gdtt-jquery-ui', GDTAXTOOLS_URL.'css/jquery_ui.css');
            wp_enqueue_style('gdtt-main', GDTAXTOOLS_URL.'css/admin_main.css', array('gdtt-jquery-ui'));

            $this->localize_script();

            do_action('gdcpt_admin_enqueue_plugin', $this->script_debug);
        } else {
            switch ($hook) {
                case 'widgets.php':
                    wp_enqueue_style('gdtt-widgets', GDTAXTOOLS_URL.'css/admin_widgets.css', array());

                    do_action('gdcpt_admin_enqueue_widgets', $this->script_debug);
                    break;
                case 'post.php':
                case 'post-new.php':
                    global $gdtt;
                    $js_url = $this->script_debug ? GDTAXTOOLS_URL.'js/src/meta.js' : GDTAXTOOLS_URL.'js/meta.js';

                    $t = array(
                        'internal' => $this->o['post_edit_tag_internal'], 
                        'yahoo' => $this->o['post_edit_tag_yahoo'] == 1 && $this->o['tagger_yahoo_api_id'] != '' ? 1 : 0,
                        'alchemy' => $this->o['post_edit_tag_alchemy'] == 1 && $this->o['tagger_alchemy_api_key'] != '' ? 1 : 0,
                        'opencalais' => $this->o['post_edit_tag_opencalais'] == 1 && $this->o['tagger_opencalais_api_key'] != '' ? 1 : 0,
                        'zemanta' => $this->o['post_edit_tag_zemanta'] == 1 && $this->o['tagger_zemanta_api_key'] != '' ? 1 : 0
                    );

                    wp_enqueue_script('jquery');

                    $depends = array('jquery');

                    if (GDTAXTOOLS_WPV > 38) {
                        wp_enqueue_script('wpdialogs');
                        wp_enqueue_style('wp-jquery-ui-dialog');

                        $depends[] = 'wpdialogs';
                    }

                    if ($this->o['custom_fields_load_maps'] == 1) {
                        $load_gmaps = false;
                        if ($this->o['google_maps_load_admin'] == 1) {
                            $load_gmaps = true;
                        }

                        $gdtt->enqueue_gmaps(true, $load_gmaps);

                        if ($load_gmaps) {
                            $depends[] = 'gdtt-maps';
                        }

                        $depends[] = 'gdtt-gmap3';
                    }

                    if (GDTAXTOOLS_WPV < 35) {
                        wp_enqueue_script('gdtt-meta-jqueryui', GDTAXTOOLS_URL.'js/legacy/meta.jqueryui.js', array('jquery'), null, true);
                    } else {
                        wp_enqueue_script('jquery-ui-button');
                        wp_enqueue_script('jquery-ui-datepicker');
                        wp_enqueue_script('jquery-ui-slider');

                        $depends[] = 'jquery-ui-button';
                        $depends[] = 'jquery-ui-datepicker';
                    }

                    wp_enqueue_script('gdtt-meta', $js_url, $depends, null, true);

                    wp_localize_script('gdtt-meta', 'gdttMetas', array(
                        'nonce' => wp_create_nonce('gdcptools'),
                        'wp_version' => GDTAXTOOLS_WPV,
                        'preview_url' => plugins_url('/gd-taxonomies-tools/forms/iframe/preview.php'),
                        'preview_title' => __("Image Preview", "gd-taxonomies-tools"),
                        'select_title' => __("Select Image", "gd-taxonomies-tools"),
                        'no_tags_found' => __("No tags found", "gd-taxonomies-tools"),
                        'getting_tags' => __("Please wait, getting tags...", "gd-taxonomies-tools"),
                        'close' => __("Close", "gd-taxonomies-tools"),
                        'refresh' => __("Refresh", "gd-taxonomies-tools"),
                        'add_all' => __("Add All", "gd-taxonomies-tools"),
                        'clear_all' => __("Clear ALL", "gd-taxonomies-tools"),
                        'internal' => __("Internal", "gd-taxonomies-tools"),
                        'suggest' => __("Suggest terms based on content", "gd-taxonomies-tools"),
                        'suggest_active' => $t['internal'] + $t['yahoo'] + $t['opencalais'] + $t['alchemy'] + $t['zemanta'],
                        'suggest_internal' => $t['internal'],
                        'suggest_yahoo' => $t['yahoo'],
                        'suggest_alchemy' => $t['alchemy'],
                        'suggest_opencalais' => $t['opencalais'],
                        'suggest_zemanta' => $t['zemanta'],
                        'clear_tags' => $this->o['post_edit_tag_delete'],
                        'chosen_select' => $this->get('transform_chosen_single_meta'),
                        'chosen_multi' => $this->get('transform_chosen_multi_meta')
                    ));

                    if ($this->get('load_chosen_meta') == 1) {
                        $js_url = GDTAXTOOLS_URL.'js/chosen.jquery.min.js';
                        wp_enqueue_script('gdtt-chosen', $js_url, array('jquery'));
                        wp_enqueue_style('gdtt-chosen', GDTAXTOOLS_URL.'css/chosen.css', array());
                    }

                    if (GDTAXTOOLS_WPV < 35) {
                        wp_enqueue_style('gdtt-meta-jqueryui', GDTAXTOOLS_URL.'css/legacy/meta.jqueryui.css', array());
                    } else {
                        wp_enqueue_style('gdtt-meta-jqueryui', GDTAXTOOLS_URL.'css/meta.jqueryui.css', array());
                    }

                    wp_enqueue_style('gdtt-meta', GDTAXTOOLS_URL.'css/admin_meta.css', array());

                    do_action('gdcpt_admin_enqueue_meta', $this->script_debug);
                    break;
                case 'nav-menus.php':
                    $js_url = $this->script_debug ? GDTAXTOOLS_URL.'js/src/menus.js' : GDTAXTOOLS_URL.'js/menus.js';

                    wp_enqueue_script('gdtt-nav-menus', $js_url, array('jquery'));
                    wp_localize_script('gdtt-nav-menus', 'gdCPTMenus_Data', array(
                        'wp_version' => GDTAXTOOLS_WPV,
                        'nonce' => wp_create_nonce('gd-cpt-tools')
                    ));
                    break;

                    do_action('gdcpt_admin_enqueue_menus', $this->script_debug);
                case 'edit-tags.php':
                    if ($this->get('special_tax_term_image') == 1) {
                        $js_url = $this->script_debug ? GDTAXTOOLS_URL.'js/src/terms.js' : GDTAXTOOLS_URL.'js/terms.js';

                        wp_enqueue_script('jquery');
                        wp_enqueue_script('jquery-form');
                        wp_enqueue_script('media-upload');
                        wp_enqueue_script('thickbox');
                        wp_enqueue_style('thickbox');

                        wp_enqueue_script('gdtt-term-images', $js_url, array('jquery'));

                        wp_localize_script('gdtt-term-images', 'gdttImages', array(
                            'nonce' => wp_create_nonce('gd-cpt-tools'),
                            'wp_version' => GDTAXTOOLS_WPV,
                            'preview_url' => plugins_url('/gd-taxonomies-tools/forms/iframe/preview.php'),
                            'attach_preview' => __("Attached Image Preview", "gd-taxonomies-tools")
                        ));

                        wp_enqueue_style('gdtt-terms', GDTAXTOOLS_URL.'css/admin_terms.css', array());
                    }

                    do_action('gdcpt_admin_enqueue_terms', $this->script_debug);
                    break;
            }
        }
    }

    function init_operations() {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            $url = esc_url_raw(remove_query_arg('action'));
            switch ($action) {
                case 'delcpt':
                    $id = gdtt_find_custompost_pos($_GET['pid']);
                    if ($id > -1) {
                        unset($this->p[$id]);
                        $this->p = array_values($this->p);
                        update_option('gd-taxonomy-tools-cpt', $this->p);

                        $this->o['force_rules_flush'] = 1;
                        update_option('gd-taxonomy-tools', $this->o);
                    }
                    $url = remove_query_arg('pid', $url);
                    wp_redirect($url);
                    exit;
                    break;
                case 'deltax':
                    $id = gdtt_find_taxonomy_pos($_GET['tid']);
                    if ($id > -1) {
                        $tax_name = $this->t[$id]['name'];
                        unset($this->t[$id]);
                        $this->t = array_values($this->t);
                        update_option('gd-taxonomy-tools-tax', $this->t);
                        gdCPTDB::delete_taxonomy_terms($tax_name);

                        $this->o['force_rules_flush'] = 1;
                        update_option('gd-taxonomy-tools', $this->o);
                    }
                    $url = remove_query_arg('tid', $url);
                    wp_redirect($url);
                    exit;
                    break;
            }
        }
    }

    function settings_operations() {
        if (isset($_POST['gdtt_saving'])) {
            $this->o['tagger_internal_limit'] = intval($_POST['tagger_internal_limit']);
            $this->o['tagger_yahoo_api_id'] = $_POST['tagger_yahoo_api_id'];
            $this->o['tagger_alchemy_api_key'] = $_POST['tagger_alchemy_api_key'];
            $this->o['tagger_opencalais_api_key'] = $_POST['tagger_opencalais_api_key'];
            $this->o['tagger_zemanta_api_key'] = $_POST['tagger_zemanta_api_key'];
            $this->o['accessibility_enhancements'] = $_POST['accessibility_enhancements'];
            $this->o['load_chosen_meta'] = isset($_POST['load_chosen_meta']) ? 1 : 0;
            $this->o['google_maps_load_admin'] = isset($_POST['google_maps_load_admin']) ? 1 : 0;
            $this->o['google_maps_load_front'] = isset($_POST['google_maps_load_front']) ? 1 : 0;
            $this->o['transform_chosen_single_meta'] = isset($_POST['transform_chosen_single_meta']) ? 1 : 0;
            $this->o['transform_chosen_multi_meta'] = isset($_POST['transform_chosen_multi_meta']) ? 1 : 0;
            $this->o['cache_active'] = isset($_POST['cache_active']) ? 1 : 0;
            $this->o['delete_taxonomy_db'] = isset($_POST['delete_taxonomy_db']) ? 1 : 0;
            $this->o['tinymce_auto_create'] = isset($_POST['tinymce_auto_create']) ? 1 : 0;
            $this->o['tinymce_use_shortcode'] = isset($_POST['tinymce_use_shortcode']) ? 1 : 0;
            $this->o['widget_terms_cloud'] = isset($_POST['widget_terms_cloud']) ? 1 : 0;
            $this->o['widget_terms_list'] = isset($_POST['widget_terms_list']) ? 1 : 0;
            $this->o['widget_posttypes_list'] = isset($_POST['widget_posttypes_list']) ? 1 : 0;
            $this->o['post_edit_tag_delete'] = isset($_POST['post_edit_tag_delete']) ? 1 : 0;
            $this->o['post_edit_tag_yahoo'] = isset($_POST['post_edit_tag_yahoo']) ? 1 : 0;
            $this->o['post_edit_tag_alchemy'] = isset($_POST['post_edit_tag_alchemy']) ? 1 : 0;
            $this->o['post_edit_tag_opencalais'] = isset($_POST['post_edit_tag_opencalais']) ? 1 : 0;
            $this->o['post_edit_tag_zemanta'] = isset($_POST['post_edit_tag_zemanta']) ? 1 : 0;
            $this->o['post_edit_tag_internal'] = isset($_POST['post_edit_tag_internal']) ? 1 : 0;
            $this->o['rewrite_intersects_active'] = isset($_POST['rewrite_intersects_active']) ? 1 : 0;
            $this->o['rewrite_permalinks_active'] = isset($_POST['rewrite_permalinks_active']) ? 1 : 0;
            $this->o['special_cpt_home_page'] = isset($_POST['special_cpt_home_page']) ? 1 : 0;
            $this->o['special_cpt_rss_feed'] = isset($_POST['special_cpt_rss_feed']) ? 1 : 0;
            $this->o['special_cpt_favorites'] = isset($_POST['special_cpt_favorites']) ? 1 : 0;
            $this->o['special_cpt_right_now'] = isset($_POST['special_cpt_right_now']) ? 1 : 0;
            $this->o['special_cpt_post_template'] = isset($_POST['special_cpt_post_template']) ? 1 : 0;
            $this->o['special_cpt_disable_quickedit'] = isset($_POST['special_cpt_disable_quickedit']) ? 1 : 0;
            $this->o['special_cpt_menu_archive'] = isset($_POST['special_cpt_menu_archive']) ? 1 : 0;
            $this->o['special_cpt_menu_drafts'] = isset($_POST['special_cpt_menu_drafts']) ? 1 : 0;
            $this->o['special_cpt_menu_futures'] = isset($_POST['special_cpt_menu_futures']) ? 1 : 0;
            $this->o['special_cpt_s2_notify'] = isset($_POST['special_cpt_s2_notify']) ? 1 : 0;
            $this->o['special_tax_edit_column'] = isset($_POST['special_tax_edit_column']) ? 1 : 0;
            $this->o['special_tax_edit_filter'] = isset($_POST['special_tax_edit_filter']) ? 1 : 0;
            $this->o['special_tax_term_link'] = isset($_POST['special_tax_term_link']) ? 1 : 0;
            $this->o['special_tax_term_id'] = isset($_POST['special_tax_term_id']) ? 1 : 0;
            $this->o['special_tax_term_image'] = isset($_POST['special_tax_term_image']) ? 1 : 0;
            $this->o['special_tax_metaboxes'] = isset($_POST['special_tax_metaboxes']) ? 1 : 0;
            $this->o['tpl_expand_archives'] = isset($_POST['tpl_expand_archives']) ? 1 : 0;
            $this->o['tpl_expand_intersect'] = isset($_POST['tpl_expand_intersect']) ? 1 : 0;
            $this->o['tpl_expand_single'] = isset($_POST['tpl_expand_single']) ? 1 : 0;
            $this->o['tpl_expand_date'] = isset($_POST['tpl_expand_date']) ? 1 : 0;
            $this->o['tpl_expand_date_cpt'] = isset($_POST['tpl_expand_date_cpt']) ? 1 : 0;
            $this->o['tpl_expand_date_cpt_priority'] = isset($_POST['tpl_expand_date_cpt_priority']) ? 1 : 0;
            $this->o['meta_post_type_change'] = isset($_POST['meta_post_type_change']) ? 1 : 0;
            $this->o['metabox_clean_title'] = isset($_POST['metabox_clean_title']) ? 1 : 0;
            $this->o['custom_fields_load_datetime'] = isset($_POST['custom_fields_load_datetime']) ? 1 : 0;
            $this->o['custom_fields_load_advanced'] = isset($_POST['custom_fields_load_advanced']) ? 1 : 0;
            $this->o['custom_fields_load_maps'] = isset($_POST['custom_fields_load_maps']) ? 1 : 0;
            $this->o['custom_fields_load_units'] = isset($_POST['custom_fields_load_units']) ? 1 : 0;

            update_option('gd-taxonomy-tools', $this->o);
            wp_redirect(esc_url_raw(add_query_arg('message', 'saved')));
            exit();
        }
    }

    function init_tools() {
        if (isset($_POST['gdtt_reset_data'])) {
            global $gdtt;

            $data = $_POST['gdtt_reset'];

            if (isset($data['settings'])) {
                delete_option('gd-taxonomy-tools');
            } else {
                $to_save = false;

                if (isset($data['ocpt'])) {
                    $to_save = true;
                    $gdtt->o['cpt_reorder'] = array();
                }

                if (isset($data['otax'])) {
                    $to_save = true;
                    $gdtt->o['tax_reorder'] = array();
                }

                if ($to_save) {
                    $gdtt->rebuild_order(true, true);
                }
            }

            if (isset($data['meta'])) {
                delete_option('gd-taxonomy-tools-meta');
            }

            if (isset($data['ccpt'])) {
                delete_option('gd-taxonomy-tools-cpt');
            }

            if (isset($data['ctax'])) {
                delete_option('gd-taxonomy-tools-tax');
            }

            if (isset($data['ocpt'])) {
                delete_option('gd-taxonomy-tools-nn-cpt');
            }

            if (isset($data['otax'])) {
                delete_option('gd-taxonomy-tools-nn-tax');
            }

            wp_redirect_self();
            exit;
        }

        if (isset($_POST['gdtt_reset_cache'])) {
            $data = $_POST['gdtt_cache'];

            if (isset($data['plugin'])) {
                delete_option('gd-taxonomy-tools-cache');
            }

            if (isset($data['rewrite'])) {
                gdr2_wp_flush_rewrite_rules();
            }

            wp_redirect_self();
            exit;
        }

        if (isset($_POST['gdtt_settings_import'])) {
            if (is_uploaded_file($_FILES['gdtt_settings_file']['tmp_name'])) {
                global $gdtt;

                include(GDTAXTOOLS_PATH.'code/internal/impexp.php');

                $data = file_get_contents($_FILES['gdtt_settings_file']['tmp_name']);
                $settings = $_POST['gdtt_settings_info'];
                $result = gdtt_import_settings($data, $settings);
                $url = esc_url_raw(add_query_arg('message', $result));

                wp_redirect($url);
                exit;
            }
        }

        if (isset($_POST['gdtt_tools_import'])) {
            if (is_uploaded_file($_FILES['gdtt_import_file']['tmp_name'])) {

                include(GDTAXTOOLS_PATH.'code/internal/impexp.php');

                $terms = file($_FILES['gdtt_import_file']['tmp_name']);
                $taxonomy = $_POST['gdtt_import_tax'];
                $hierarchy = is_taxonomy_hierarchical($taxonomy);
                $counter = gdtt_import_terms($taxonomy, $terms, $hierarchy);

                if (is_int($counter)) {
                    $url = esc_url_raw(add_query_arg('message', 'imported'));
                    $url = add_query_arg('terms', $counter, $url);

                    wp_redirect($url);
                    exit;
                }
            }
        }
    }

    function is_taxonomy_valid($tax_name) {
        global $wp_taxonomies;
        $tax_names = array_keys($wp_taxonomies);
        return !in_array(strtolower($tax_name), $tax_names);
    }

    function meta_box_group_custom($post, $args) {
        global $gdtt, $gdtt_fields;

        $gdtt_fields->load_admin();
        gdtt_update_custom_fields(false);

        $group = $args['args'];
        $load_boxes = array();
        
        foreach ($group['boxes'] as $box) {
            $load_boxes[$box] = gdtt_get_meta_box($box);
        }

        include(GDTAXTOOLS_PATH.'forms/metaboxes/custom_group.php');
    }

    function meta_box_custom($post, $args) {
        global $gdtt, $gdtt_fields, $gdr2_units;

        $gdtt_fields->load_admin();
        gdtt_update_custom_fields(false);

        $meta = $args['args'];
        $values = $gdtt->meta_box_current_values($post->ID, $meta['code']);

        $_ID = 'gdtt_box_'.$meta['code'].'_';
        $_NAME = 'gdtt_box['.$meta['code'].'][';
        $_F = $gdtt->m['fields'];

        include(GDTAXTOOLS_PATH.'forms/metaboxes/custom_meta.php');
    }

    function meta_box_custom_nav_post_types() {
        include(GDTAXTOOLS_PATH.'forms/metaboxes/nav_menus.php');
    }

    function admin_meta() {
        global $gdtt;

        $loaded_meta_boxes = array();
        $embed_shortcoder = false;

        if (gdtt_get('navmenu_metabox_active') == 1) {
            add_meta_box('gdtt-custom-cpt', __("Post Types Archives", "gd-taxonomies-tools"), array(&$this, 'meta_box_custom_nav_post_types'), 'nav-menus', 'side', 'high');  
        }

        if (is_array($gdtt->m['groups']) && !empty($gdtt->m['groups'])) {
            foreach ($gdtt->m['groups'] as $group => $obj) {
                $meta = (array)$obj;

                if (isset($gdtt->m['map_groups'][$group]) && !empty($gdtt->m['map_groups'][$group])) {
                    foreach ($gdtt->m['map_groups'][$group] as $pt) {
                        $to_show = true;

                        if ($meta['user_access'] == 'role') {
                            $roles = explode(',', $meta['user_roles']);
                            $to_show = gdr2_is_current_user_roles($roles);
                        } else if ($meta['user_access'] == 'caps') {
                            $caps = explode(',', $meta['user_caps']);
                            $to_show = false;

                            foreach ($caps as $cap) {
                                if (current_user_can($cap)) {
                                    $to_show = true;
                                }
                            }
                        }

                        $to_show = apply_filters('gdcpt_metabox_group_access', $to_show, $meta);

                        if ($to_show) {
                            $loaded_meta_boxes = array_merge($loaded_meta_boxes, $meta['boxes']);

                            $location = isset($meta['location']) && $meta['location'] != '' ? $meta['location'] : 'advanced';
                            $title = $this->o['metabox_clean_title'] == 1 ? '' : 'GD CPT Tools: ';
                            $title.= __($meta['name']);

                            add_meta_box('gdtt_mbg_'.$meta['code'], $title, array($this, 'meta_box_group_custom'), $pt, $location, 'default', $meta);

                            $embed_shortcoder = true;
                        }
                    }
                }
            }
        }

        if (is_array($gdtt->m['boxes']) && !empty($gdtt->m['boxes'])) {
            foreach ($gdtt->m['boxes'] as $box => $obj) {
                $meta = (array)$obj;

                if (!in_array($box, $loaded_meta_boxes) && isset($gdtt->m['map'][$box]) && !empty($gdtt->m['map'][$box])) {
                    foreach ($gdtt->m['map'][$box] as $pt) {
                        $to_show = true;

                        if (isset($meta['user_access'])) {
                            if ($meta['user_access'] == 'role') {
                                $roles = explode(',', $meta['user_roles']);
                                $to_show = gdr2_is_current_user_roles($roles);
                            } else if ($meta['user_access'] == 'caps') {
                                $caps = explode(',', $meta['user_caps']);
                                $to_show = false;

                                foreach ($caps as $cap) {
                                    if (current_user_can($cap)) {
                                        $to_show = true;
                                    }
                                }
                            }
                        }

                        $to_show = apply_filters('gdcpt_metabox_access', $to_show, $meta);

                        if ($to_show) {
                            $location = isset($meta['location']) && $meta['location'] != '' ? $meta['location'] : 'advanced';
                            $title = $this->o['metabox_clean_title'] == 1 ? '' : 'GD CPT Tools: ';
                            $title.= __($meta['name']);

                            add_meta_box('gdtt_mb_'.$meta['code'], $title, array($this, 'meta_box_custom'), $pt, $location, 'default', $meta);

                            $embed_shortcoder = true;
                        }
                    }
                }
            }
        }

        if (isset($_GET['post'])) {
            $post_id = (int)$_GET['post'];
            $post_type = get_post_type($post_id);

            if (isset($post_type) && !is_null($post_type) && !empty($post_type)) {
                if ($this->o['special_cpt_post_template'] == 1) {
                    foreach ($this->sf['cpt'] as $cpt => $feats) {
                        if (in_array('post_template', $feats, true) && $post_type == $cpt) {
                            add_meta_box('gdcpt_post_tpl_box', __("Post Template", "gd-taxonomies-tools"), array($this, 'meta_post_template'), $cpt, 'side');
                        }
                    }
                }

                if ($this->o['meta_post_type_change'] == 1) {
                    add_meta_box('gdcpt_post_type_box', __("Post Type", "gd-taxonomies-tools"), array($this, 'meta_post_type'), $post_type, 'side');
                }
            }
        }

        if ($embed_shortcoder) {
            add_action('admin_footer', array(&$this, 'load_shortcode_dialog'));
        }
    }

    function admin_menu_archive() {
        foreach ($this->sf['cpt'] as $cpt => $feats) {
            if (in_array('menu_archive', $feats, true)) {
                $post_type = get_post_type_object($cpt);
                if ($post_type->has_archive) {
                    $parent_url = 'edit.php';
                    if ($cpt != 'post') {
                        $parent_url.= '?post_type='.$cpt;
                    }
                    $menu_url = get_post_type_archive_link($cpt);

                    add_submenu_page($parent_url, __("Archive", "gd-taxonomies-tools"), __("Archive", "gd-taxonomies-tools"), $post_type->cap->edit_posts, $menu_url);
                }
            }
        }
    }

    function admin_menu_futures() {
        $status = get_post_status_object('future');

        foreach ($this->sf['cpt'] as $cpt => $feats) {
            if (in_array('menu_futures', $feats, true)) {
                if (post_type_exists($cpt)) {
                    $counts = wp_count_posts($cpt, 'readable');
                    $futures = intval($counts->future);

                    if ($futures > 0) {
                        $post_type = get_post_type_object($cpt);
                        $parent_url = 'edit.php';
                        if ($cpt != 'post') {
                            $parent_url.= '?post_type='.$cpt;
                        }
                        $menu_url = add_query_arg('post_status', 'future', $parent_url);

                        add_submenu_page($parent_url, $post_type->labels->view_item.': '.__("Scheduled", "gd-taxonomies-tools"), 
                                            sprintf(translate_nooped_plural($status->label_count, $futures), $futures), 
                                            $post_type->cap->edit_posts, $menu_url);
                    }
                }
            }
        }
    }

    function admin_menu_drafts() {
        $status = get_post_status_object('draft');

        foreach ($this->sf['cpt'] as $cpt => $feats) {
            if (in_array('menu_drafts', $feats, true)) {
                if (post_type_exists($cpt)) {
                    $counts = wp_count_posts($cpt, 'readable');
                    $drafts = intval($counts->draft);

                    if ($drafts > 0) {
                        $post_type = get_post_type_object($cpt);
                        $parent_url = 'edit.php';
                        if ($cpt != 'post') {
                            $parent_url.= '?post_type='.$cpt;
                        }
                        $menu_url = add_query_arg('post_status', 'draft', $parent_url);

                        add_submenu_page($parent_url, $post_type->labels->view_item.': '.__("Drafts", "gd-taxonomies-tools"), 
                                            sprintf(translate_nooped_plural($status->label_count, $drafts), $drafts), 
                                            $post_type->cap->edit_posts, $menu_url);
                    }
                }
            }
        }
    }

    function admin_menu() {
        $this->page_ids[] = add_menu_page('GD CPT Tools', 'GD CPT Tools', 'gdcpttools_basic', 'gdtaxtools_front', array(&$this,'admin_front'), plugins_url('gd-taxonomies-tools/gfx/menu/icon_16.png'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Front Page", "gd-taxonomies-tools"), __("Front Page", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_front', array(&$this,'admin_front'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("About Plugin", "gd-taxonomies-tools"), __("About Plugin", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_about', array(&$this, 'admin_about'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Post Types", "gd-taxonomies-tools"), __("Post Types", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_postypes', array(&$this, 'admin_postypes'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("New Post Type", "gd-taxonomies-tools"), __("New Post Type", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_postypes&action=addnew', array(&$this, 'admin_postypes'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Taxonomies", "gd-taxonomies-tools"), __("Taxonomies", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_taxs', array(&$this, 'admin_taxs'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("New Taxonomy", "gd-taxonomies-tools"), __("New Taxonomy", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_taxs&action=addnew', array(&$this, 'admin_taxs'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Meta Boxes", "gd-taxonomies-tools"), __("Meta Boxes", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_metas', array(&$this, 'admin_metas'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Roles &amp; Capabilities", "gd-taxonomies-tools"), __("Roles &amp; Caps", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_roles', array(&$this, 'admin_roles'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Modules", "gd-taxonomies-tools"), __("Modules", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_modules', array(&$this, 'admin_modules'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Settings", "gd-taxonomies-tools"), __("Settings", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_settings', array(&$this, 'admin_settings'));
        $this->page_ids[] = add_submenu_page('gdtaxtools_front', 'GD CPT Tools: '.__("Tools", "gd-taxonomies-tools"), __("Tools", "gd-taxonomies-tools"), 'gdcpttools_basic', 'gdtaxtools_tools', array(&$this, 'admin_tools'));

        $this->admin_load_hooks();
    }

    function admin_load_hooks() {
        foreach ($this->page_ids as $id) {
            add_action('load-'.$id, array(&$this, 'load_admin_page'));
        }
    }

    function load_admin_page() {
        $screen = get_current_screen();
        $page_id = $screen->id == 'toplevel_page_gdtaxtools_front' ? 'front' : substr($screen->id, 29);
        $this->tutorials();

        $screen->set_help_sidebar('
            <p><strong>Dev4Press:</strong></p>
            <p><a target="_blank" href="http://www.dev4press.com/">'.__("Website", "gd-taxonomies-tools").'</a></p>
            <p><a target="_blank" href="http://twitter.com/dev4press">'.__("On Twitter", "gd-taxonomies-tools").'</a></p>
            <p><a target="_blank" href="http://facebook.com/dev4press">'.__("On Facebook", "gd-taxonomies-tools").'</a></p>');

        $screen->add_help_tab(array(
            "id" => "gdpt-screenhelp-help",
            "title" => __("Get Help", "gd-taxonomies-tools"),
            "content" => '<h5>'.__("General plugin information", "gd-taxonomies-tools").'</h5>
                <p><a href="http://www.gdcpttools.com/" target="_blank">'.__("Plugin Website", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.gdcpttools.com/faq/" target="_blank">'.__("Frequently asked questions", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.gdcpttools.com/development-roadmap/" target="_blank">'.__("Development roadmap", "gd-taxonomies-tools").'</a></p>
                <h5>'.__("Support for the plugin on Dev4Press", "gd-taxonomies-tools").'</h5>
                <p><a href="http://www.dev4press.com/plugins/gd-taxonomies-tools/support/" target="_blank">'.__("Support Overview", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.dev4press.com/forums/forum/plugins/gd-taxonomies-tools/" target="_blank">'.__("Support Forum", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.dev4press.com/documentation/product/plg-gd-taxonomies-tools/" target="_blank">'.__("Documentation", "gd-taxonomies-tools").'</a> | 
                <a href="http://www.dev4press.com/category/tutorials/plugins/gd-taxonomies-tools/" target="_blank">'.__("Tutorials", "gd-taxonomies-tools").'</a></p>'));

        $counter = 0;
        $tutorials = "";
        foreach ($this->tutorials as $tut) {
            if (in_array($page_id, $tut["panels"])) {
                $tutorials.= '<a class="gdsr-icon-tutorial gdsr-icon-tutorial-'.$tut["icon"].'" href="http://www.dev4press.com/?p='.$tut["id"].'" target="_blank">'.$tut["title"].'</a>';
                $counter++;
            }
        }

        if ($counter == 0) {
            $tutorials.= '<p>'.__("Nothing found.", "gd-taxonomies-tools").'</p>';
        }

        $screen->add_help_tab(array(
            'id' => 'gdpt-screenhelp-modules',
            'title' => __("Modules", "gd-taxonomies-tools"),
            'content' => '<h5>'.__("List of available Modules", "gd-taxonomies-tools").'</h5>
                <p><a href="http://www.gdcpttools.com/module/basic/index/" target="_blank"><strong>'.__("Index", "gd-taxonomies-tools").'</strong></a> - '.__("Add letter or number based indexes and index archive pages for posts belonging to any post type.", "gd-taxonomies-tools").'</p>'));

        $screen->add_help_tab(array(
            'id' => 'gdpt-screenhelp-tutorials',
            'title' => __("Tutorials", "gd-taxonomies-tools"),
            'content' => '<h5>'.__("Panel specific tutorials", "gd-taxonomies-tools").'</h5>'.$tutorials));

        $screen->add_help_tab(array(
            'id' => 'gdpt-screenhelp-website',
            'title' => 'Dev4Press', 'sfc',
            'content' => '<p>'.__("On Dev4Press website you can find many useful plugins, themes and tutorials, all for WordPress. Please, take a few minutes to browse some of these resources, you might find some of them very useful.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/plugins/" target="_blank"><strong>'.__("Plugins", "gd-taxonomies-tools").'</strong></a> - '.__("We have more than 10 plugins available, some of them are commercial and some are available for free.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/themes/" target="_blank"><strong>'.__("Themes", "gd-taxonomies-tools").'</strong></a> - '.__("All our themes are based on our own xScape Theme Framework, and only available as premium.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/category/tutorials/" target="_blank"><strong>'.__("Tutorials", "gd-taxonomies-tools").'</strong></a> - '.__("Premium and free tutorials for our plugins themes, and many general and practical WordPress tutorials.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/documentation/" target="_blank"><strong>'.__("Central Documentation", "gd-taxonomies-tools").'</strong></a> - '.__("Growing collection of functions, classes, hooks, constants with examples for our plugins and themes.", "gd-taxonomies-tools").'</p>
                <p><a href="http://www.dev4press.com/forums/" target="_blank"><strong>'.__("Support Forums", "gd-taxonomies-tools").'</strong></a> - '.__("Premium support forum for all with valid licenses to get help. Also, report bugs and leave suggestions.", "gd-taxonomies-tools").'</p>'));
    }

    function load_shortcode_dialog() {
        require_once(GDTAXTOOLS_PATH.'forms/metaboxes/shortcoder.php');
    }

    function remove_ordered_meta_boxes($result) {
        if (!empty($this->remove_meta)) {
            foreach ($result as $key => $list) {
                if (is_string($list)) {
                    $values = array_diff(explode(',', $list), $this->remove_meta);
                    $result[$key] = join(',', $values);
                }
            }
        }

        return $result;
    }

    function modify_tax_metaboxes() {
        global $wp_meta_boxes, $post_type;

        if (isset($post_type) && !is_null($post_type) && !empty($post_type)) {
            foreach ($this->sf['tax'] as $tax => $values) {
                // add_filter('get_user_option_meta-box-order_'.$post_type, array(&$this, 'remove_ordered_meta_boxes'));

                if ($values['metabox_code'] == 'hide') {
                    $this->remove_meta[] = $values['metabox_name'];
                    if (isset($wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']])) {
                        unset($wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']]);
                    }
                } else if ($values['metabox_code'] == 'limited_single') {
                    if (isset($wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']])) {
                        $wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']]['callback'] = array($this, 'meta_tax_limited');
                        $wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']]['args']['selection'] = 'single';
                    }
                } else if ($values['metabox_code'] == 'limited_multi') {
                    if (isset($wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']])) {
                        $wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']]['callback'] = array($this, 'meta_tax_limited');
                        $wp_meta_boxes[$post_type]['side']['core'][$values['metabox_name']]['args']['selection'] = 'multi';
                    }
                }
            }
        }
    }

    function admin_head() {
        global $parent_file;

        if ($this->o['special_tax_metaboxes'] == 1) {
            $this->modify_tax_metaboxes();
        }

        /* if (GDTAXTOOLS_WPV > 38 && $parent_file == 'edit.php') { ?>
            <style type="text/css">
                i.mce-i-gdcpt_terms_ctrl:before {
                    content: "\f323";
                    display: inline-block;
                    -webkit-font-smoothing: antialiased;
                    text-align: center;
                    font: 400 20px/1 dashicons !important;
                    speak: none;
                    vertical-align: top;
                }
            </style>
        <?php } */

        if (GDTAXTOOLS_WPV < 39 && $parent_file == 'edit.php') { ?>
            <style type="text/css">
                input[type="radio"] {
                    margin: -3px 2px 0 0; 
                }

                .mcegdttMenuItem .mceText {
                    padding: 0 10px !important;
                }
            </style>
        <?php }

        if ($this->admin_plugin) {
            do_action('gdcpt_admin_head', $this->admin_plugin_page);
            do_action('gdcpt_admin_head_'.$this->admin_plugin_page);
        }
    }

    function admin_footer() {
        global $parent_file;

        if (GDTAXTOOLS_WPV > 38 && $parent_file == 'edit.php') {
            /* include(GDTAXTOOLS_PATH.'tinymce/plugin4.php'); */
        }

        if (GDTAXTOOLS_WPV < 39 && $parent_file == 'edit.php') {
            include(GDTAXTOOLS_PATH.'tinymce/plugin3.php');
        }

        if (!empty($this->css_inline)) {
            echo '<style type="text/css">';
            echo join("\n\r", $this->css_inline);
            echo '</style>';
        }

        if ($this->admin_plugin) {
            do_action('gdcpt_admin_footer', $this->admin_plugin_page);
            do_action('gdcpt_admin_footer_'.$this->admin_plugin_page);
        }
    }

    function plugin_links($links, $file) {
        if ($file == 'gd-taxonomies-tools/gd-taxonomies-tools.php'){
            $links[] = '<a href="admin.php?page=gdtaxtools_taxs">'.__("Taxonomies", "gd-taxonomies-tools").'</a>';
            $links[] = '<a href="admin.php?page=gdtaxtools_postypes">'.__("Post Types", "gd-taxonomies-tools").'</a>';
            $links[] = '<a href="admin.php?page=gdtaxtools_metas">'.__("Meta Boxes", "gd-taxonomies-tools").'</a>';
            $links[] = '<a href="http://www.dev4press.com/plugins/gd-taxonomies-tools/faq/">'.__("FAQ", "gd-taxonomies-tools").'</a>';
        }
        return $links;
    }

    function plugin_actions($links) {
        $settings_link = '<a href="admin.php?page=gdtaxtools_settings">' . __("Settings", "gd-taxonomies-tools") . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    function meta_tax_limited($post, $box) {
        $defaults = array('taxonomy' => 'category', 'selection' => 'multi');
        $args = !isset($box['args']) || !is_array($box['args']) ? array() : $box['args'];
        extract(wp_parse_args($args, $defaults), EXTR_SKIP);
        $tax = get_taxonomy($taxonomy);
        include(GDTAXTOOLS_PATH.'forms/metaboxes/tax_limited.php');
    }

    function meta_post_template() {
        include(GDTAXTOOLS_PATH.'gdr2/gdr2.ui.php');
        include(GDTAXTOOLS_PATH.'forms/metaboxes/template.php');
    }

    function meta_post_type() {
        include(GDTAXTOOLS_PATH.'gdr2/gdr2.ui.php');
        include(GDTAXTOOLS_PATH.'forms/metaboxes/post_type.php');
    }

    function admin_front() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_front();
    }

    function admin_about() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_about();
    }

    function admin_modules() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_modules();
    }

    function admin_settings() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_settings();
    }

    function admin_tools() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_tools();
    }

    function admin_roles() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_roles();
    }

    function admin_metas() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_metas();
    }
    
    function admin_postypes() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_postypes();
    }

    function admin_taxs() {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');
        gdCPTAdmin_Panels::admin_taxs();
    }
}

class gdCPTIcons extends gdr2_MenuIcons {
    function init_links() {
        $this->url_blank = GDTAXTOOLS_URL.'gfx/blank.gif';
        $this->url_types = GDTAXTOOLS_URL.'gfx/ui/post_types.png';
    }

    function init_icons() {
        $this->icons = array(
            'address-book-blue',
            'address-book',
            'alarm-clock',
            'android',
            'application-monitor',
            'asterisk',
            'auction-hammer',
            'balance',
            'bank',
            'battery-charge',
            'bauble',
            'bean',
            'bell',
            'binocular',
            'block',
            'blog-blue',
            'blog-posterous',
            'blog',
            'blogs',
            'book-open-text-image',
            'book',
            'bookmark',
            'books-brown',
            'books',
            'box',
            'brain',
            'briefcase',
            'broom',
            'bug',
            'burn',
            'calculator',
            'calendar-blue',
            'cards-bind-address',
            'chain',
            'chart-pie',
            'chart',
            'clapperboard',
            'clipboard-task',
            'database',
            'disc-blue',
            'envelope',
            'film',
            'flag-black',
            'flag-blue',
            'flag-green',
            'flag-pink',
            'flag-purple',
            'flag-white',
            'flag-yellow',
            'flag',
            'gear',
            'globe',
            'guitar',
            'hammer-left',
            'headphone',
            'heart',
            'home',
            'hourglass',
            'ice',
            'image-select',
            'images-flickr',
            'jar',
            'key',
            'keyboard',
            'leaf',
            'lifebuoy',
            'luggage',
            'mail-open-table',
            'mail-open',
            'map',
            'marker',
            'music-beam-16',
            'newspaper',
            'palette',
            'paper-clip',
            'pencil',
            'photo-album-blue',
            'photo-album',
            'piano',
            'piggy-bank',
            'pill',
            'pin',
            'pipette',
            'playing-card',
            'plug-disconnect',
            'plug',
            'present',
            'price-tag-label',
            'puzzle',
            'spray',
            'stamp',
            'star',
            'store',
            'target',
            'umbrella',
            'users',
            'wand',
            'weather-cloudy',
            'wooden-box',
            'wrench'
        );
    }
}

?>