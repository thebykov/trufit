<?php

if (!defined('ABSPATH')) exit;

class gdCPT_Tools {
    public $o;
    public $l;
    public $m;
    public $t;
    public $p;
    public $nn_t;
    public $nn_p;
    public $cf;
    public $mods;
    public $temp;
    public $imgs;

    public $jquery_code = array();

    public $system_keys = array('__core__', '__scope__', '__name__', '__date__');

    public $sf = array(
        'tax' => array(),
        'cpt' => array(), 
        'totals' => array('in_menu_block' => array()),
        'intersections' => array(),
        'query_vars' => array(),
        'yourls' => array(),
        'baseless' => array()
    );

    public $active = array(
        'cpt' => array(), 
        'tax' => array()
    );

    public $loaded = array(
        'cpt' => array(), 
        'tax' => array()
    );

    public $custom_fields = array();

    public $plugin_url;
    public $plugin_path;
    public $plugin_base;
    public $wp_version;
    public $edit_tax;
    public $edit_cpt;

    public $cache_modded = false;
    public $menu_items = array();

    public $default_options;
    public $reserved_names;
    public $post_type_caps;
    public $taxonomy_caps;
    public $post_features;
    public $post_features_special;
    public $taxonomy_features_special;

    public $loaded_fields = array();
    public $loaded_modules = array(
        'bbpress' => array('location' => '__internal__', 'scope' => 'site'),
        'content_fields' => array('location' => '__internal__', 'scope' => 'site'),
        'toolbar_menu' => array('location' => '__internal__', 'scope' => 'site')
    );

    function __construct($base_path, $base_file) {
        $this->plugin_path = $base_path.'/';
        $this->plugin_base = $base_file;

        $gdd = new gdCPTDefaults();
        $this->default_options = $gdd->default_options;
        $this->reserved_names = $gdd->reserved_names;
        $this->taxonomy_features_special = $gdd->taxonomy_features_special;
        $this->post_features = $gdd->post_features;
        $this->post_features_special = $gdd->post_features_special;
        $this->post_type_caps = $gdd->post_type_caps;
        $this->taxonomy_caps = $gdd->taxonomy_caps;

        define('GDTAXONOMIESTOOLS_INSTALLED', $this->default_options['version'].' '.$this->default_options['status']);
        define('GDTAXONOMIESTOOLS_VERSION', $this->default_options['version'].'_b'.($this->default_options['build'].'_pro'));

        $this->plugin_path_url();
        $this->install_plugin();
        $this->init_special_features();
        $this->actions_filters();

        unset($gdd);
    }

    function _c() {
        return $this->o['cache_active'] == 1;
    }

    function plugin_path_url() {
        $this->plugin_url = plugins_url('/gd-taxonomies-tools/');

        define('GDTAXTOOLS_URL', $this->plugin_url);
        define('GDTAXTOOLS_PATH', $this->plugin_path);
    }

    function install_plugin() {
        global $wp_version;
        $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);
        define('GDTAXTOOLS_WPV', intval($this->wp_version));

        $role = get_role('administrator');
        $role->add_cap('gdcpttools_basic');

        $this->o = get_option('gd-taxonomy-tools');
        $this->m = get_option('gd-taxonomy-tools-meta');
        $this->t = get_option('gd-taxonomy-tools-tax');
        $this->p = get_option('gd-taxonomy-tools-cpt');
        $this->cf = get_option('gd-taxonomy-tools-cache');
        $this->mods = get_option('gd-taxonomy-tools-modules');
        $this->imgs = get_option('gd-taxonomy-tools-im-tax');
        $this->nn_t = get_option('gd-taxonomy-tools-nn-tax');
        $this->nn_p = get_option('gd-taxonomy-tools-nn-cpt');

        if (!is_array($this->t)) {
            $this->t = array();
            update_option('gd-taxonomy-tools-tax', $this->t);
        }

        if (!is_array($this->p)) {
            $this->p = array();
            update_option('gd-taxonomy-tools-cpt', $this->p);
        }

        if (!is_array($this->o)) {
            $this->o = $this->default_options;
            update_option('gd-taxonomy-tools', $this->o);
        } else if ($this->o['build'] != $this->default_options['build'] ||
            $this->o['edition'] != $this->default_options['edition']) {
            $this->o = gdr2_Core::upgrade_settings($this->o, $this->default_options);

            if ($this->o['build'] < 4000) {
                $this->o['custom_fields_load_advanced'] = 1;
                $this->o['custom_fields_load_maps'] = 1;
                $this->o['custom_fields_load_units'] = 1;
            }

            $this->o['version'] = $this->default_options['version'];
            $this->o['date'] = $this->default_options['date'];
            $this->o['status'] = $this->default_options['status'];
            $this->o['build'] = $this->default_options['build'];
            $this->o['edition'] = $this->default_options['edition'];
            $this->o['revision'] = $this->default_options['revision'];

            $this->o['force_rules_flush'] = 1;

            $this->reindex_and_save();
        }

        if (!is_array($this->cf)) {
            $this->cf = array('tax' => array(), 'cpt' => array());
            update_option('gd-taxonomy-tools-cache', $this->cf);
        }

        if (!is_array($this->mods)) {
            $this->mods = array('__status__' => array());
            update_option('gd-taxonomy-tools-modules', $this->mods);
        }

        if (!is_array($this->nn_t)) {
            $this->nn_t = array('status' => array(), 'full' => array(), 'simple' => array());
            update_option('gd-taxonomy-tools-nn-tax', $this->nn_t);
        }

        if (!is_array($this->nn_p)) {
            $this->nn_p = array('status' => array(), 'full' => array(), 'simple' => array());
            update_option('gd-taxonomy-tools-nn-cpt', $this->nn_p);
        }

        if (!is_array($this->m)) {
            $this->m = array('boxes' => array(), 'fields' => array(), 'map' => array(), 'groups' => array(), 'map_groups' => array());
            update_option('gd-taxonomy-tools-meta', $this->m);
        } else  {
            $this->update_meta_boxes_data();
        }

        if (!is_array($this->imgs)) {
            $this->imgs = array();
            update_option('gd-taxonomy-tools-im-tax', $this->imgs);
        }

        if (empty($this->o['cpt_reorder']) || empty($this->o['tax_reorder'])) {
            $this->rebuild_order(true, true);
        }
    }

    function update_meta_boxes_data() {
        $to_save = false;

        if (!isset($this->m['map_groups'])) {
            $this->m['map_groups'] = array();
            $to_save = true;
        }

        if (!isset($this->m['groups'])) {
            $this->m['groups'] = array();
            $to_save = true;
        }

        foreach ($this->m['fields'] as $code => $field) {
            if (is_object($field)) {
                $this->m['fields'][$code] = (array)$field;
                $to_save = true;
            }
        }

        foreach ($this->m['boxes'] as $code => $box) {
            if (is_object($box)) {
                $this->m['boxes'][$code] = (array)$box;
                $to_save = true;
            }
        }

        if ($to_save) {
            update_option('gd-taxonomy-tools-meta', $this->m);
        }
    }
    
    function init_special_features() {
        foreach ($this->nn_p['status'] as $cpt_name => $status) {
            if ($status != 'no') {
                $cpt = $this->nn_p[$status][$cpt_name];

                $this->sf['cpt'][$cpt['name']] = isset($cpt['special']) ? $cpt['special'] : array();

                if (isset($cpt['yourls_active_link']) && $cpt['yourls_active_link'] == 'yes') { $this->sf['cpt'][$cpt['name']][] = 'yourls_link'; }

                if (isset($cpt['intersections'])) {
                    if ($cpt['intersections'] != 'no') {
                        $this->sf['cpt'][$cpt['name']][] = 'intersections';
                    }

                    if ($cpt['intersections'] == 'max' || ($cpt['intersections'] == 'adv' && $cpt['intersections_structure'] != '')) {
                        $this->sf['intersections'][$cpt['name']] = $cpt['intersections_structure'];
                    }
                }

                if (isset($cpt['menu_position']) && $cpt['menu_position'] == '__block__') {
                    $this->sf['totals']['in_menu_block'][] = 'edit.php?post_type='.$cpt['name'];
                }
            }
        }

        foreach ($this->o['cpt_reorder'] as $key) {
            foreach ($this->p as $cpt) {
                if ($cpt['id'] == $key) {
                    if ($cpt['active'] == 1) {
                        $this->sf['cpt'][$cpt['name']] = isset($cpt['special']) ? $cpt['special'] : array();

                        if (isset($cpt['yourls_active_link']) && $cpt['yourls_active_link'] == 'yes') { $this->sf['cpt'][$cpt['name']][] = 'yourls_link'; }

                        if (isset($cpt['intersections'])) {
                            if ($cpt['intersections'] != 'no') {
                                $this->sf['cpt'][$cpt['name']][] = 'intersections';
                            }

                            if ($cpt['intersections'] == 'max' || ($cpt['intersections'] == 'adv' && $cpt['intersections_structure'] != '')) {
                                $this->sf['intersections'][$cpt['name']] = $cpt['intersections_structure'];
                            }
                        }

                        if (isset($cpt['menu_position']) && $cpt['menu_position'] == '__block__') {
                            $this->sf['totals']['in_menu_block'][] = 'edit.php?post_type='.$cpt['name'];
                        }
                    }

                    break;
                }
            }
        }

        foreach ($this->nn_t['status'] as $tax_name => $status) {
            if ($status != 'no') {
                $tax = $this->nn_t[$status][$tax_name];

                $this->sf['tax'][$tax['name']] = isset($tax['special']) ? $tax['special'] : array();
                $this->sf['tax'][$tax['name']]['index'] = array();

                if (isset($tax['index_normal']) && $tax['index_normal'] != 'no') {
                    $this->sf['tax'][$tax['name']]['index'][] = 'normal';
                }

                if (isset($tax['index_intersect']) && $tax['index_intersect'] != 'no') {
                    $this->sf['tax'][$tax['name']]['index'][] = 'intersect';
                }
            }
        }

        foreach ($this->t as $tax) {
            if (isset($tax['active'])) {
                $this->sf['tax'][$tax['name']] = isset($tax['special']) ? $tax['special'] : array();
                $this->sf['tax'][$tax['name']]['index'] = array();

                if (isset($tax['index_normal']) && $tax['index_normal'] != 'no') {
                    $this->sf['tax'][$tax['name']]['index'][] = 'normal';
                }

                if (isset($tax['index_intersect']) && $tax['index_intersect'] != 'no') {
                    $this->sf['tax'][$tax['name']]['index'][] = 'intersect';
                }
            }
        }
    }

    function actions_filters() {
        add_action('plugins_loaded', array(&$this, 'init_modules'), 1);
        add_action('plugins_loaded', array(&$this, 'load_modules'), 2);

        if (is_admin()) {
            add_action('admin_head', array(&$this, 'admin_menu_items'));
        }

        add_action('init', array(&$this, 'init'));
        add_action('widgets_init', array(&$this, 'widgets_init'));

        add_action('init', array(&$this, 'register_custom_posts'), 1);
        add_action('init', array(&$this, 'register_custom_taxonomies'), 2);
        add_action('init', array(&$this, 'register_for_object_types'), 3);
        add_action('init', array(&$this, 'update_special_features'), 4);

        add_action('init', array(&$this, 'override_post_types'), 101);
        add_action('init', array(&$this, 'override_taxonomies'), 102);
        add_action('init', array(&$this, 'override_special_features'), 103);

        add_action('generate_rewrite_rules', array(&$this, 'generate_rewrite_rules'), 8);
        add_filter('query_vars', array(&$this, 'query_vars'));
        add_action('request', array(&$this, 'wp_request'));
        add_filter('post_type_link', array(&$this, 'post_type_link'), 10, 3);

        add_action('save_post', array(&$this, 'save_custom_meta'), 10, 2);
        add_action('save_post', array(&$this, 'save_post_meta'), 1, 2);
        add_filter('presstools_debugger_panels', array(&$this, 'debugger_panel'));

        add_action('gdcpt_custom_fields_init', array(&$this, 'custom_fields_init'));
        add_action('gdcpt_custom_fields_load_admin', array(&$this, 'custom_fields_admin_require'));

        if ($this->o['custom_fields_load_maps'] == 1) {
            add_action('wp_footer', array(&$this, 'init_enqueue_gmaps'), 10);
        }

        add_action('wp_footer', array(&$this, 'embed_jquery_code'), 20);
    }

    function rebuild_order($cpt = false, $tax = false, $clear = false) {
        if ($clear) {
            $this->o['cpt_reorder'] = array();
            $this->o['tax_reorder'] = array();
        }

        $to_save = false;

        if ($cpt) {
            foreach ($this->p as $post_type) {
                if (!in_array($post_type['id'], $this->o['cpt_reorder'])) {
                    $this->o['cpt_reorder'][] = $post_type['id'];
                    $to_save = true;
                }
            }
        }

        if ($tax) {
            foreach ($this->t as $taxonomy) {
                if (!in_array($taxonomy['id'], $this->o['tax_reorder'])) {
                    $this->o['tax_reorder'][] = $taxonomy['id'];
                    $to_save = true;
                }
            }
        }

        if ($to_save) {
            update_option('gd-taxonomy-tools', $this->o);
        }
    }

    function reindex_and_save() {
        $count_cpt = 0; $count_tax = 0;

        foreach ($this->p as $p) {
            if ((int)$p['id'] > $count_cpt) $count_cpt = (int)$p['id'];
        }

        foreach ($this->t as $p) {
            if ((int)$p['id'] > $count_tax) $count_tax = (int)$p['id'];
        }

        $this->o['cpt_internal'] = $count_cpt;
        $this->o['tax_internal'] = $count_tax;

        update_option('gd-taxonomy-tools', $this->o);
    }

    function get($setting) {
        return $this->o[$setting];
    }

    function mod_get_all($module) {
        $data = array();

        if (isset($this->mods[$module])) {
            foreach ($this->mods[$module] as $key => $value) {
                if (!in_array($key, $this->system_keys)) {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }

    function mod_get($module, $setting) {
        if (!isset($this->mods[$module])) return null;

        return isset($this->mods[$module][$setting]) ? $this->mods[$module][$setting] : false;
    }

    function mod_set($module, $setting, $value) {
        $this->mods[$module][$setting] = $value;
    }

    function get_sf($name, $feature, $type = 'cpt') {
        return isset($this->sf[$type][$name]) && 
               is_array($this->sf[$type][$name]) && 
               in_array($feature, $this->sf[$type][$name], true);
    }

    function get_sf_list($feature, $type = 'cpt') {
        $list = array();
        foreach ($this->sf[$type] as $name => $features) {
            if (is_array($features) && in_array($feature, $features, true)) {
                $list[] = $name;
            }
        }
        return $list;
    }

    function get_defaults_count() {
        return 5;
    }

    function get_term_id($term) {
        if (empty($term)) {
            return false;
        }

        if (is_object($term)) {
            $term = $term->term_id;
        }

        return intval($term);
    }

    function generate_rewrite_rules($wp_rewrite) {
        global $wp_taxonomies;
        
        $rules = array();
        $taxonomies = array();

        $rewrite = new gdCPTRewrite();

        foreach ($wp_taxonomies as $_tax => $_obj) {
            if (isset($this->sf['tax'][$_tax])) {
                if (in_array('normal', $this->sf['tax'][$_tax]['index'])) {
                    $rules = array_merge($rewrite->generate_terms_index($_tax, $_obj, $wp_rewrite), $rules);
                }

                foreach ($_obj->object_type as $_cpt) {
                    $taxonomies[$_cpt][] = $_tax;
                }
            }
        }

        foreach ($this->nn_p['status'] as $cpt_name => $status) {
            if ($status != 'no' && post_type_exists($cpt_name)) {
                $cpt = $this->nn_p[$status][$cpt_name];

                if (gdtt_has_archives($cpt['name'])) {
                    if (isset($cpt['date_archives']) && $cpt['date_archives'] == 'yes') {
                        $rules = array_merge($rewrite->generate_date_archives($cpt, $wp_rewrite), $rules);
                    }

                    if (isset($cpt['intersections']) && isset($taxonomies[$cpt['name']]) && !empty($taxonomies[$cpt['name']])) {
                        if ($cpt['intersections'] == 'max' || ($cpt['intersections'] == 'adv' && $cpt['intersections_structure'] != '')) {
                            $rules = array_merge($rules, $rewrite->generate_advanced_intersection($cpt, $wp_rewrite));
                        }

                        if ($cpt['intersections'] == 'max' || $cpt['intersections'] == 'yes') {
                            $rules = array_merge($rewrite->generate_intersection($cpt, $taxonomies[$cpt['name']], $wp_rewrite), $rules);
                        }

                        $rules = array_merge($rewrite->generate_standard_overrides($cpt, $wp_rewrite), $rules);
                    }
                }
            }
        }

        foreach ($this->o['cpt_reorder'] as $key) {
            foreach ($this->p as $cpt) {
                if ($cpt['id'] == $key) {
                    if (!isset($cpt['active']) || (isset($cpt['active']) && $cpt['active'] == 1)) {
                        if (gdtt_has_archives($cpt['name'])) {
                            if (isset($cpt['date_archives']) && $cpt['date_archives'] == 'yes') {
                                $rules = array_merge($rewrite->generate_date_archives($cpt, $wp_rewrite), $rules);
                            }

                            if (isset($cpt['intersections'])) {
                                if ($cpt['intersections'] == 'max' || ($cpt['intersections'] == 'adv' && $cpt['intersections_structure'] != '')) {
                                    $rules = array_merge($rules, $rewrite->generate_advanced_intersection($cpt, $wp_rewrite));
                                }

                                if (isset($taxonomies[$cpt['name']])) {
                                    if ($cpt['intersections'] == 'max' || $cpt['intersections'] == 'yes') {
                                        $rules = array_merge($rewrite->generate_intersection($cpt, $taxonomies[$cpt['name']], $wp_rewrite), $rules);
                                    }
                                }

                                $rules = array_merge($rewrite->generate_standard_overrides($cpt, $wp_rewrite), $rules);
                            }
                        }
                    }

                    break;
                }
            }
        }

        if (!empty($rules)) {
            $wp_rewrite->rules = $rules + $wp_rewrite->rules;
        }
    }

    function query_vars($qv) {
        if (is_array($this->sf['query_vars']) && !empty($this->sf['query_vars'])) {
            $qv = array_merge($qv, $this->sf['query_vars']);
        }

        foreach (array_keys($this->m['fields']) as $field) {
            $qv[] = 'cpt_cf_'.$field;
        }

        $qv[] = 'taxindex';

        return $qv;
    }

    function wp_request($query_vars) {
        $new_query_vars = array();

        foreach ($query_vars as $key => $value) {
            if (substr($key, 0, 4) == 'cpt_') {
                $parts = explode('_', $key, 3);

                switch ($parts[1]) {
                    case 'postid':
                        $new_query_vars['post_type'] = $parts[2];
                        $new_query_vars['p'] = $value;
                        break;
                }
            } else {
                $new_query_vars[$key] = $value;
            }
        }

        return $new_query_vars;
    }

    function post_type_link($post_link, $post, $leavename) {
        $rewritecode = array(
            '%year%', '%monthnum%', '%day%',
            '%hour%', '%minute%', '%second%',
            $leavename ? '' : '%'.$post->post_type.'%',
            '%post_id%', '%'.$post->post_type.'_id%');

        $date = explode(' ', date('Y m d H i s', strtotime($post->post_date)));
        $rewritereplace = array(
            $date[0], $date[1], $date[2],
            $date[3], $date[4], $date[5],
            $post->post_name,
            $post->ID, $post->ID);

        if (strpos($post_link, '%author%') !== false) {
            $authordata = get_userdata($post->post_author);
            $rewritecode[] = '%author%';
            $rewritereplace[] = $authordata->user_nicename;
        }

        foreach ($this->m['fields'] as $field => $obj) {
            $_field = (array)$obj;

            if ($_field['type'] == 'rewrite') {
                $code = '%cf_'.$field.'%';

                if (strpos($post_link, $code) !== false) {
                    $meta_value = get_post_meta($post->ID, $field, true);

                    if ($meta_value == '' && $_field['rewrite'] != '__none__') {
                        $val = get_post_meta($post->ID, $_field['rewrite'], true);
                        $new = gdr2_sanitize_custom($val);
                        update_post_meta($post->ID, $field, $new);

                        $meta_value = $new;
                    }

                    $value = $meta_value == '' ? '-' : $meta_value;

                    $rewritecode[] = $code;
                    $rewritereplace[] = gdr2_sanitize_custom($value);
                }
            }
        }

        $taxonomies = get_taxonomies(array('public' => true));
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $t = get_taxonomy($taxonomy);
                $tax = '%'.$taxonomy.'%';

                if (strpos($post_link, $tax) !== false) {
                    $term = null;
                    $term_slug = '-';
                    $terms = get_the_terms($post->ID, $taxonomy);

                    if ($terms) {
                        usort($terms, '_usort_terms_by_ID');
                        $term = $terms[0];
                        $term_slug = $term->slug;
                    }

                    if (is_object($term) && is_taxonomy_hierarchical($taxonomy) && $t->rewrite["hierarchical"]) {
                        $hierarchical_slugs = array();
                        $ancestors = get_ancestors($term->term_id, $taxonomy);

                        foreach ((array)$ancestors as $ancestor) {
                            $ancestor_term = get_term($ancestor, $taxonomy);
                            $hierarchical_slugs[] = $ancestor_term->slug;
			}

                        $hierarchical_slugs = array_reverse($hierarchical_slugs);
			$hierarchical_slugs[] = $term_slug;
                        $term_slug = implode('/', $hierarchical_slugs);
                    }

                    $rewritecode[] = $tax;
                    $rewritereplace[] = $term_slug;
                }
            }
        }

        $post_link = str_replace($rewritecode, $rewritereplace, $post_link);
        $post_link = user_trailingslashit($post_link, 'single');

        return $post_link;
    }

    function admin_menu_items() {
        if (!empty($this->menu_items)) {
            global $gdtt_icons;
            $items = $this->menu_items;

            echo '<style type="text/css">';

            foreach ($items as $name => $icon) {
                echo $gdtt_icons->get_css($icon, $name);
            }

            echo '</style>';
        }
    }

    function prepare_cpt_field($field, $value, $atts) {
        global $gdtt_fields;
        return $gdtt_fields->meta_value($field, $value, $atts);
    }

    function debugger_panel($panels) {
        require_once(GDTAXTOOLS_PATH.'code/internal/debug.php');
        $panels[] = 'gdCPTTools';
        return $panels;
    }

    function tax_term_attach_image($taxonomy, $term, $image_id) {
        $term_id = $this->get_term_id($term);

        if ($term_id !== false) {
            $image_id = intval($image_id);
            $this->imgs[$taxonomy][$term_id] = $image_id;
            update_option('gd-taxonomy-tools-im-tax', $this->imgs);
        }
    }

    function tax_term_dettach_image($taxonomy, $term) {
        if (isset($this->imgs[$taxonomy])) {
            $term_id = $this->get_term_id($term);

            if ($term_id !== false) {
                if (isset($this->imgs[$taxonomy][$term_id])) {
                    unset($this->imgs[$taxonomy][$term_id]);
                    update_option('gd-taxonomy-tools-im-tax', $this->imgs);
                }
            }
        }
    }

    function tax_get_term_image($taxonomy, $term, $size = 'thumbnail', $get = 'img') {
        if (isset($this->imgs[$taxonomy])) {
            $term_id = $this->get_term_id($term);

            if ($term_id !== false) {
                if (isset($this->imgs[$taxonomy][$term_id])) {
                    $attachemnt_id = $this->imgs[$taxonomy][$term_id];

                    switch ($get) {
                        case 'id':
                            return $attachemnt_id;
                            break;
                        case 'url':
                            $img = wp_get_attachment_image_src($attachemnt_id, $size);
                            return $img[0];
                            break;
                        default:
                        case 'img':
                            return wp_get_attachment_image($attachemnt_id, $size);
                            break;
                    }
                }
            }
        }

        return false;
    }

    function override_special_features() {
        foreach ($this->nn_t['status'] as $tax_name => $status) {
            if (taxonomy_exists($tax_name) && $status != 'no') {
                $tax = $this->nn_t[$status][$tax_name];
                $this->sf['tax'][$tax['name']]['hierarchical'] = is_taxonomy_hierarchical($tax['name']);
                $this->sf['tax'][$tax['name']]['metabox_name'] = is_taxonomy_hierarchical($tax['name']) ? $tax['name'].'div' : 'tagsdiv-'.$tax['name'];
                $this->sf['tax'][$tax['name']]['metabox_code'] = isset($tax['metabox']) ? $tax['metabox'] : 'auto';
            }
        }

        foreach ($this->p as $cpt) {
            $baseless = isset($cpt['intersections_baseless']) && $cpt['intersections_baseless'] != '' ? $cpt['intersections_baseless'] : '';

            if ($baseless != '') {
                $this->sf['baseless'][$cpt['name']] = $baseless;
            }
        }
    }

    function get_baseless_taxonomy($post_type) {
        if (isset($this->sf['baseless'][$post_type])) {
            return $this->sf['baseless'][$post_type];
        } else {
            return '';
        }
    }

    function update_special_features() {
        foreach ($this->sf['tax'] as $name => $data) {
            if (!taxonomy_exists($name)) {
                unset($this->sf['tax'][$name]);
            }
        }

        foreach ($this->sf['cpt'] as $name => $data) {
            if (!post_type_exists($name)) {
                unset($this->sf['cpt'][$name]);
            }
        }

        foreach ($this->t as $tax) {
            if (isset($tax['active'])) {
                $this->sf['tax'][$tax['name']]['hierarchical'] = is_taxonomy_hierarchical($tax['name']);
                $this->sf['tax'][$tax['name']]['metabox_name'] = is_taxonomy_hierarchical($tax['name']) ? $tax['name'].'div' : 'tagsdiv-'.$tax['name'];
                $this->sf['tax'][$tax['name']]['metabox_code'] = isset($tax['metabox']) ? $tax['metabox'] : 'auto';

                if (isset($tax['show_admin_column']) && $tax['show_admin_column'] == 'yes') {
                    $this->sf['tax'][$tax['name']][] = 'edit_column';
                }
            }
        }

        foreach ($this->p as $cpt) {
            if (!isset($cpt['active']) || (isset($cpt['active']) && $cpt['active'] == 1)) {
                $this->sf['query_vars'][] = 'cpt_postid_'.$cpt['name'];
            }
        }

        foreach ($this->nn_p['status'] as $cpt_name => $status) {
            if ($status != 'no' && post_type_exists($cpt_name)) {
                $cpt = $this->nn_p[$status][$cpt_name];

                if (gdtt_has_archives($cpt_name) && isset($cpt['permalinks_active']) && $cpt['permalinks_active'] == 'yes') {
                    $this->sf['query_vars'][] = 'cpt_postid_'.$cpt_name;
                }
            }
        }
    }

    function meta_box_current_values($post_id, $meta_box_id) {
        $current = array();

        $meta = (array)$this->m['boxes'][$meta_box_id];
        $fields = array_unique($meta['fields']);

        foreach ($fields as $f) {
            $current[$f] = (array)get_post_meta($post_id, $f, false);
        }

        return $current;
    }

    function save_custom_meta($post_id, $post) {
        if (isset($_POST['gdtt_box'])) {
            global $gdtt_fields;
            $gdtt_fields->meta_save($post_id, $post);
        }
    }

    function save_post_meta($post_id, $post) {
        if (isset($_POST['cpt_post_noonce']) && wp_verify_nonce($_POST['cpt_post_noonce'], 'gdcpttools')) {
            $tpl = $_POST['cpt_post_templates'];

            if ($tpl == '__default__') {
                delete_post_meta($post->ID, '_wp_post_template');
            } else {
                update_post_meta($post->ID, '_wp_post_template', $tpl);
            }
        }

        if ($post->post_type != 'revision' && isset($_POST['cpt_postype_noonce']) && wp_verify_nonce($_POST['cpt_postype_noonce'], 'gdcpttools')) {
            $post_type = $_POST['cpt_post_type'];

            if ($post->post_type != $post_type) {
                global $wpdb;

                $wpdb->update($wpdb->posts, array('post_type' => $post_type), array('ID' => $post_id));
            }
        }
    }

    function unset_cache($type, $name) {
        if (isset($this->cf[$type][$name])) {
            unset($this->cf[$type][$name]);
        }

        update_option('gd-taxonomy-tools-cache', $this->cf);
    }

    function register_custom_posts() {
        global $wp_rewrite;

        $permalink_index = GDTAXTOOLS_WPV < 34 ? 0 : 'struct';

        if (empty($this->o['cpt_reorder']) && !empty($this->p)) {
            $this->rebuild_order(true);
        } else if (count($this->p) != count($this->o['cpt_reorder'])) {
            $to_save = false;

            foreach ($this->p as $cpt) {
                if (!in_array($cpt['id'], $this->o['cpt_reorder'])) {
                    $to_save = true;
                    $this->o['cpt_reorder'][] = $cpt['id'];
                }
            }

            if ($to_save) {
                update_option('gd-taxonomy-tools', $this->o);
            }
        }

        foreach ($this->o['cpt_reorder'] as $key) {
            foreach ($this->p as $cpt) {
                if ($cpt['id'] == $key) {
                    if (!isset($cpt['active']) || (isset($cpt['active']) && $cpt['active'] == 1)) {
                        if ($this->_c() && isset($this->cf['cpt'][$cpt['name']]) && !empty($this->cf['cpt'][$cpt['name']])) {
                            $options = $this->cf['cpt'][$cpt['name']];
                        } else {
                            $options = gdtt_generate_custom_posts_options($cpt);
                            $options = apply_filters('gdcpt_register_post_type', $options, $cpt['name']);
                            $options = apply_filters('gdcpt_register_post_type_'.$cpt['name'], $options);

                            if ($this->_c()) {
                                $this->cf['cpt'][$cpt['name']] = $options;
                                $this->cache_modded = true;
                            }
                        }

                        $this->active['cpt'][] = $cpt['name'];
                        $this->loaded['cpt'][$cpt['name']] = $cpt['id'];
                        register_post_type($cpt['name'], $options);

                        $wp_rewrite->add_rewrite_tag('%'.$cpt['name'].'_id%', '([0-9]+)', 'cpt_postid_'.$cpt['name'].'=');
                        foreach ($this->m['fields'] as $field => $obj) {
                            $_field = (array)$obj;
                            if ($_field['type'] == 'rewrite') {
                                $wp_rewrite->add_rewrite_tag('%cf_'.$field.'%', '([^/]+)', 'cpt_cf_'.$field.'=');
                            }
                        }

                        if (gdtt_has_archives($cpt['name']) && isset($cpt['permalinks_active']) && $cpt['permalinks_active'] == 'yes') {
                            $wp_rewrite->extra_permastructs[$cpt['name']][$permalink_index] = $cpt['permalinks_structure'];
                        }

                        if (isset($cpt['icon']) && !is_null($cpt['icon']) && !empty($cpt["icon"]) && $cpt['menu_icon'] == '') {
                            $this->menu_items[$cpt['name']] = $cpt['icon'];
                        }
                    }

                    break;
                }
            }
        }

        do_action('gdcpt_register_post_types');
    }

    function register_custom_taxonomies() {
        if (empty($this->o['tax_reorder']) && !empty($this->t)) {
            $this->rebuild_order(false, true);
        } else if (count($this->t) != count($this->o['tax_reorder'])) {
            $to_save = false;

            foreach ($this->t as $tax) {
                if (!in_array($tax['id'], $this->o['tax_reorder'])) {
                    $to_save = true;
                    $this->o['tax_reorder'][] = $tax['id'];
                }
            }

            if ($to_save) {
                update_option('gd-taxonomy-tools', $this->o);
            }
        }

        foreach ($this->o['tax_reorder'] as $key) {
            foreach ($this->t as $tax) {
                if ($tax['id'] == $key) {
                    if (isset($tax['active'])) {
                        $domains = array_filter(explode(',', $tax['domain']));

                        if ($this->_c() && isset($this->cf['tax'][$tax['name']]) && !empty($this->cf['tax'][$tax['name']])) {
                            $options = $this->cf['tax'][$tax['name']];
                        } else {
                            $options = gdtt_generate_custom_taxonomies_options($tax);
                            $options = apply_filters('gdcpt_register_taxonomy', $options, $tax['name']);
                            $options = apply_filters('gdcpt_register_taxonomy_'.$tax['name'], $options);

                            if ($this->_c()) {
                                $this->cf['tax'][$tax['name']] = $options;
                                $this->cache_modded = true;
                            }
                        }

                        $this->active['tax'][] = $tax['name'];
                        $this->loaded['tax'][$tax['name']] = $tax['id'];

                        register_taxonomy($tax['name'], $domains, $options);
                    }

                    break;
                }
            }
        }

        do_action('gdcpt_register_taxonomies');
    }

    function register_for_object_types() {
        foreach ($this->t as $tax) {
            if (isset($tax['active'])) {
                $domains = explode(',', $tax['domain']);

                foreach ($domains as $post_type) {
                    register_taxonomy_for_object_type($tax['name'], $post_type);
                }
            }
        }

        foreach ($this->p as $cpt) {
            if (!isset($cpt['active']) || (isset($cpt['active']) && $cpt['active'] == 1)) {
                if (is_array($cpt['taxonomies'])) {
                    foreach ($cpt['taxonomies'] as $tax) {
                        register_taxonomy_for_object_type($tax, $cpt['name']);
                    }
                }
            }
        }

        do_action('gdcpt_register_taxonomies_for_post_types');
    }

    function apply_override_posttypes_taxonomies($post_type, $taxonomies) {
        foreach ($taxonomies as $taxonomy) {
            register_taxonomy_for_object_type($taxonomy, $post_type);
        }
    }

    function apply_override_posttypes_features($post_type, $features) {
        $all = array_keys($this->post_features);

        foreach ($all as $feat) {
            if (in_array($feat, $features, true)) {
                add_post_type_support($post_type, $feat);
            } else {
                remove_post_type_support($post_type, $feat);
            }
        }
    }

    function override_post_types() {
        global $wp_rewrite;

        $permalink_index = GDTAXTOOLS_WPV < 34 ? 0 : 'struct';

        foreach ($this->nn_p['status'] as $cpt_name => $status) {
            if ($status != 'no' && post_type_exists($cpt_name)) {
                $cpt = $this->nn_p[$status][$cpt_name];

                if ($status == 'full') {
                    $options = gdtt_generate_custom_posts_options($cpt);
                    $options['_builtin'] = $cpt['name'] == 'post' || $cpt['name'] == 'page' || $cpt['name'] == 'attachment';

                    register_post_type($cpt['name'], $options);
                } else if ($status == 'simple') {
                    $this->apply_override_posttypes_features($cpt_name, $cpt['supports']);
                    $this->apply_override_posttypes_taxonomies($cpt_name, $cpt['taxonomies']);
                }

                if (gdtt_has_archives($cpt_name) && isset($cpt['permalinks_active']) && $cpt['permalinks_active'] == 'yes') {
                    $wp_rewrite->extra_permastructs[$cpt_name][$permalink_index] = $cpt['permalinks_structure'];
                }
            }
        }

        do_action('gdcpt_override_post_types');
    }

    function override_taxonomies() {
        foreach ($this->nn_t['status'] as $tax_name => $status) {
            if ($status != 'no' && taxonomy_exists($tax_name)) {
                $tax = $this->nn_t[$status][$tax_name];
                $domains = explode(',', $tax['domain']);

                if ($status == 'full') {
                    $options = gdtt_generate_custom_taxonomies_options($tax);
                    $options['_builtin'] = $tax['name'] == 'category' || $tax['name'] == 'post_tag';

                    register_taxonomy($tax['name'], $domains, $options);
                } else if ($status == 'simple') {
                    global $wp_taxonomies;

                    if (isset($wp_taxonomies[$tax_name])) {
                        $wp_taxonomies[$tax_name]->object_type = $domains;
                        $wp_taxonomies[$tax_name]->show_in_nav_menus = isset($tax['nav_menus']) && $tax['nav_menus'] == 'yes';
                        $wp_taxonomies[$tax_name]->show_tagcloud = isset($tax['cloud']) && $tax['cloud'] == 'yes';
                        $wp_taxonomies[$tax_name]->show_admin_column = isset($tax['show_admin_column']) ? $tax['show_admin_column'] == 'yes' : false;
                    }
                }
            }
        }

        do_action('gdcpt_override_taxonomies');
    }

    function list_names_tax() {
        $found = array();

        foreach ($this->t as $tax) {
            $found[] = $tax['name'];
        }

        return $found;
    }

    function list_names_cpt() {
        $found = array();

        foreach ($this->p as $cpt) {
            $found[] = $cpt['name'];
        }

        return $found;
    }

    function prepare_inactive_cpt() {
        $found = array();

        foreach ($this->p as $cpt) {
            if (isset($cpt['active']) && $cpt['active'] == 0) {
                $found[$cpt['name']] = new gdtt_CustomPost($cpt);
            }
        }

        return $found;
    }

    function prepare_inactive_tax() {
        $found = array();

        foreach ($this->t as $tax) {
            if (!isset($tax['active'])) {
                $found[$tax['name']] = new gdtt_Taxonomy($tax);
            }
        }

        return $found;
    }

    function init() {
        $this->l = get_locale();

        if (!empty($this->l)) {
            load_plugin_textdomain('gd-taxonomies-tools', false, 'gd-taxonomies-tools/languages');
        }

        if ($this->o['force_rules_flush'] == 1) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();

            if (function_exists('gdsr_rebuild_ratings')) {
                gdsr_rebuild_ratings();
            }

            $this->o['force_rules_flush'] = 0;
            update_option('gd-taxonomy-tools', $this->o);
        }
    }

    function widgets_init() {
        if ($this->o['widget_terms_cloud']) {
            require_once(GDTAXTOOLS_PATH.'widgets/gdtt-terms-cloud.php');
            register_widget('gdttTermsCloud');
        }

        if ($this->o['widget_terms_list']) {
            require_once(GDTAXTOOLS_PATH.'widgets/gdtt-terms-list.php');
            register_widget('gdttTermsList');
        }

        if ($this->o['widget_posttypes_list']) {
            require_once(GDTAXTOOLS_PATH.'widgets/gdtt-posttypes-list.php');
            register_widget('gdttPostTypesList');
        }
    }

    function custom_fields_init() {
        if ($this->o['custom_fields_load_datetime'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/display/datetime.php');

            gdcpt_register_custom_field('date', 'gdCPT_Field_Admin_Date', 'gdCPT_Field_Display_Date');
            gdcpt_register_custom_field('month', 'gdCPT_Field_Admin_Month', 'gdCPT_Field_Display_Month');
            gdcpt_register_custom_field('time', 'gdCPT_Field_Admin_Time', 'gdCPT_Field_Display_Time');
            gdcpt_register_custom_field('date_time', 'gdCPT_Field_Admin_Datetime', 'gdCPT_Field_Display_Datetime');
            gdcpt_register_custom_field('period', 'gdCPT_Field_Admin_Period', 'gdCPT_Field_Display_Period');
        }

        if ($this->o['custom_fields_load_advanced'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/display/advanced.php');

            gdcpt_register_custom_field('color', 'gdCPT_Field_Admin_Color', 'gdCPT_Field_Display_Color');
            gdcpt_register_custom_field('editor', 'gdCPT_Field_Admin_Editor', 'gdCPT_Field_Display_Editor');
            gdcpt_register_custom_field('image', 'gdCPT_Field_Admin_Image', 'gdCPT_Field_Display_Image');
            gdcpt_register_custom_field('rewrite', 'gdCPT_Field_Admin_Rewrite', 'gdCPT_Field_Display_Rewrite');
        }

        if ($this->o['custom_fields_load_maps'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/display/maps.php');

            gdcpt_register_custom_field('google_map', 'gdCPT_Field_Admin_GoogleMap', 'gdCPT_Field_Display_GoogleMap');
        }

        if ($this->o['custom_fields_load_units'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/display/units.php');

            gdcpt_register_custom_field('unit', 'gdCPT_Field_Admin_Unit', 'gdCPT_Field_Display_Unit');
            gdcpt_register_custom_field('currency', 'gdCPT_Field_Admin_Currency', 'gdCPT_Field_Display_Currency');
            gdcpt_register_custom_field('resolution', 'gdCPT_Field_Admin_Resolution', 'gdCPT_Field_Display_Resolution');
            gdcpt_register_custom_field('dimensions', 'gdCPT_Field_Admin_Dimensions', 'gdCPT_Field_Display_Dimensions');
        }
    }

    function custom_fields_admin_require() {
        if ($this->o['custom_fields_load_datetime'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/admin/datetime.php');
        }

        if ($this->o['custom_fields_load_advanced'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/admin/advanced.php');
        }

        if ($this->o['custom_fields_load_maps'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/admin/maps.php');
        }

        if ($this->o['custom_fields_load_units'] == 1) {
            require_once(GDTAXTOOLS_PATH.'code/meta/admin/units.php');
        }
    }

    function has_module($module) {
        if (!isset($this->mods)) {
            return false;
        } else {
            return isset($this->mods[$module]);
        }
    }

    function is_module_active($module) {
        if (!isset($this->mods)) {
            return false;
        } else {
            return isset($this->mods['__status__'][$module]) && $this->mods['__status__'][$module];
        }
    }

    function init_modules() {
        do_action('gdcpt_modules_init');

        $this->loaded_modules = apply_filters('gdcpt_loaded_modules', $this->loaded_modules);

        $new_module = false;
        foreach ($this->loaded_modules as $module => $info) {
            $location = $info['location'];
            $internal = false;
            $path = '';

            if ($location == '__internal__') {
                $internal = true;
                $path = GDTAXTOOLS_PATH.'code/modules/'.$module.'/';
            } else if (substr($location, 0, 11) == '__plugin__:') {
                $location = substr($location, 11);
                $path = WP_PLUGIN_DIR.'/'.$location.'/code/';
            } else if (substr($location, 0, 11) == '__folder__:') {
                $location = trim(substr($location, 11), '/');
                $path = WP_CONTENT_DIR.'/'.$location.'/';
            }

            if ($path != '' && file_exists($path.'defaults.php')) {
                require_once($path.'defaults.php');

                $mod = 'gdCPTModuleDefault_'.$module;
                $mod = new $mod();
                $defaults = $mod->get_defaults();

                if (!gdcpt_has_module($module)) {
                    $new_module = true;
                    $auto_load = isset($mod->auto_load) ? $mod->auto_load : false;

                    $this->mods['__status__'][$module] = $internal ? true : $auto_load;
                    $this->mods[$module] = $defaults;
                } else {
                    if ($defaults['__core__']['build'] != $this->mods[$module]['__core__']['build']) {
                        $new_module = true;

                        $this->mods[$module] = $mod->update($this->mods[$module]);
                    }
                }
            }
        }

        if ($new_module) {
            update_option('gd-taxonomy-tools-modules', $this->mods);
        }
    }

    function load_modules() {
        foreach ($this->loaded_modules as $module => $info) {
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

                if (file_exists($path.'load.php')) {
                    require_once($path.'load.php');
                }
            }
        }
    }

    function embed_jquery_code() {
        if (!empty($this->jquery_code)) {
            echo '<script type="text/javascript">'.GDR2_EOL;
            echo 'jQuery(document).ready(function() {'.GDR2_EOL;

            echo join(GDR2_EOL, $this->jquery_code);

            echo GDR2_EOL.'});'.GDR2_EOL;
            echo '</script>'.GDR2_EOL;
        }
    }

    function init_enqueue_gmaps() {
        $load_gmaps = false;
        if ($this->o['google_maps_load_front'] == 1) {
            $load_gmaps = true;
        }

        $this->enqueue_gmaps(false, $load_gmaps);
    }

    function enqueue_gmaps($force = false, $google_maps = true) {
        $filter = apply_filters('gdcpt_enqueue_google_maps', false);

        if ($force || $filter) {
            wp_enqueue_script('jquery');

            $depend = array('jquery');

            if ($google_maps) {
                $protocol = is_ssl() ? 'https' : 'http';
                wp_enqueue_script('gdtt-maps', $protocol.'://maps.google.com/maps/api/js?sensor=true', array(), null, true);

                $depend[] = 'gdtt-maps';
            }

            wp_enqueue_script('gdtt-gmap3', GDTAXTOOLS_URL.'js/gmap3.min.js', $depend, null, true);
        }
    }
}

class gdttMetaBox {}
class gdttCustomField {}

?>