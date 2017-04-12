<?php

if (!defined('ABSPATH')) exit;

class gdCPTAdmin_AJAX {
    function __construct() {
        add_action('plugins_loaded', array(&$this, 'load_modules'), 7);

        add_action('wp_ajax_gdtt_meta_search_tags', array(&$this, 'meta_search_tags'));

        add_action('wp_ajax_gd_cpt_navmenus_post_type_archives', array(&$this, 'navmenus_post_type_archives'));

        add_action('wp_ajax_gd_cpt_tinymce_check', array(&$this, 'ajax_check_term'));
        add_action('wp_ajax_gd_cpt_tinymce_search', array(&$this, 'ajax_search_term'));

        add_action('wp_ajax_gd_cpt_dettach_image', array(&$this, 'ajax_detach_term_image'));
        add_action('wp_ajax_gd_cpt_attach_image', array(&$this, 'ajax_attach_term_image'));

        add_action('wp_ajax_gd_cpt_save_caps', array(&$this, 'ajax_save_caps'));

        add_action('wp_ajax_gd_cpt_change_order', array(&$this, 'ajax_change_order'));

        add_action('wp_ajax_gd_cpt_meta_add_field', array(&$this, 'ajax_add_custom_field'));
        add_action('wp_ajax_gd_cpt_meta_delete_field', array(&$this, 'ajax_delete_custom_field'));

        add_action('wp_ajax_gd_cpt_meta_add_metabox', array(&$this, 'ajax_add_metabox'));
        add_action('wp_ajax_gd_cpt_meta_delete_metabox', array(&$this, 'ajax_delete_metabox'));
        add_action('wp_ajax_gd_cpt_meta_attach_metabox', array(&$this, 'ajax_attach_metabox'));
        add_action('wp_ajax_gd_cpt_meta_clear_metabox', array(&$this, 'ajax_clear_metabox'));

        add_action('wp_ajax_gd_cpt_meta_add_metabox_group', array(&$this, 'ajax_add_metabox_group'));
        add_action('wp_ajax_gd_cpt_meta_delete_metabox_group', array(&$this, 'ajax_delete_metabox_group'));
        add_action('wp_ajax_gd_cpt_meta_attach_metabox_group', array(&$this, 'ajax_attach_metabox_group'));

        add_action('wp_ajax_gd_cpt_save_settings', array(&$this, 'ajax_save_settings'));
        add_action('wp_ajax_gd_cpt_meta_shortcodes', array(&$this, 'ajax_meta_shortcodes'));
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

                if (file_exists($path.'ajax.php')) {
                    require_once($path.'ajax.php');
                }
            }
        }
    }

    function navmenus_post_type_archives() {
        check_ajax_referer('gd-cpt-tools');
        require_once(ABSPATH.'wp-admin/includes/nav-menu.php');

        $list = (array)$_POST['list'];

        $items = array();
        foreach ($list as $post_type) {
            $post_type_obj = get_post_type_object($post_type);
            
            if (!$post_type_obj) continue;

            $menu_item = array(
                'menu-item-title' => esc_attr($post_type_obj->labels->name),
                'menu-item-type' => 'gdtt_cpt_archive',
                'menu-item-object' => esc_attr($post_type)
            );

            $items[] = wp_update_nav_menu_item(0, 0, $menu_item);  
        }

        $menu_items = array();
        foreach ($items as $item) {
            $menu = get_post($item);

            if (!empty($menu->ID)) {
                $menu = wp_setup_nav_menu_item($menu);
                $menu->label = $menu->title;
                $menu_items[] = $menu;
            }
        }

        if (!empty($menu_items)) {
            $args = array(
                'after' => '', 
                'before' => '',
                'link_after' => '',
                'link_before' => '',
                'walker' => new Walker_Nav_Menu_Edit()
            );

            die(walk_nav_menu_tree($menu_items, 0, (object)$args));
        }

        die('');
    }

    function is_term_valid($term, $check_empty = false) {
        if (trim($term) == "" && $check_empty) return false;
        return strtolower($term) == sanitize_title_with_dashes($term);
    }

    function save_yourls_options($type, $link = false) {
        $wp_ozh_yourls = get_option('ozh_yourls');
        $wp_ozh_yourls['generate_on_'.$type] = $link ? 1 : 0;

        update_option('ozh_yourls', $wp_ozh_yourls);
    }

    function add_cpt_caps($name) {
        if ($name == 'post') {
            return;
        }

        $caps = get_option('gd-taxonomy-tools-caps');
        if (!is_array($caps)) {
            $caps = array('cpt' => array(), 'tax' => array());
        }

        if (!isset($caps['cpt'][$name])) {
            $caps['cpt'][$name] = new gdtt_Caps($name);
        }

        update_option('gd-taxonomy-tools-caps', $caps);
    }

    function add_tax_caps($name) {
        if ($name == 'categories') {
            return;
        }

        $caps = get_option('gd-taxonomy-tools-caps');
        if (!is_array($caps)) {
            $caps = array('cpt' => array(), 'tax' => array());
        }

        if (!isset($caps['tax'][$name])) {
            $caps['tax'][$name] = new gdtt_Caps($name, 'tax');
        }

        update_option('gd-taxonomy-tools-caps', $caps);
    }

    function meta_search_tags() {
        global $gdtt;

        check_ajax_referer('gdcptools');
        $tags = array('tags' => array());

        $api = stripslashes($_POST['api']);
        $content = stripslashes($_POST['content']);
        $title = stripslashes($_POST['title']);

        require_once(GDTAXTOOLS_PATH.'gdr2/gdr2.seo.php');
        $gdr2_seo_core = new gdr2_SEO();

        switch ($api) {
            default:
            case 'internal':
                $list = (array)$gdr2_seo_core->get_tags_from_internal($title, $content);
                if (count($list) > $gdtt->o['tagger_internal_limit']) {
                    $list = array_slice($list, 0, $gdtt->o['tagger_internal_limit']);
                }
                $tags['tags'] = $list;
                break;
            case 'yahoo':
                $tags['tags'] = (array)$gdr2_seo_core->get_tags_from_yahoo($title, $content, 60, $gdtt->o['tagger_yahoo_api_id']);
                break;
            case 'alchemy':
                $tags['tags'] = (array)$gdr2_seo_core->get_tags_from_alchemy($title, $content, 60, $gdtt->o['tagger_alchemy_api_key']);
                break;
            case 'opencalais':
                $tags['tags'] = (array)$gdr2_seo_core->get_tags_from_opencalais($title, $content, 60, $gdtt->o['tagger_opencalais_api_key']);
                break;
            case 'zemanta':
                $tags['tags'] = (array)$gdr2_seo_core->get_tags_from_zemanta($title, $content, 60, $gdtt->o['tagger_zemanta_api_key']);
                break;
        }

        $response = json_encode($tags);
        die($response);
    }

    function ajax_meta_shortcodes() {
        global $gdtt_fields;

        check_ajax_referer('gdcptools');

        $gdtt_fields->load_admin();
        $shortcodes = $gdtt_fields->get_field_shortcode_elements($_POST['field']);

        $render = '';
        foreach ($shortcodes as $block => $codes) {
            if ($block == 'standard') {
                $render.= '<h3 class="gdcpt-sh-title">'.__("Standard Attributes", "gd-taxonomies-tools").':</h3>';
            } else if ($block == 'advanced') {
                $render.= '<div class="clear"></div><h3 class="gdcpt-sh-title">'.__("Advanced Attributes", "gd-taxonomies-tools").':</h3>';
            } else if ($block == 'repeater') {
                $render.= '<div class="clear"></div><h3 class="gdcpt-sh-title">'.__("Multiple Values Handling Attributes", "gd-taxonomies-tools").':</h3>';
            }

            if (empty($codes)) {
                $render.= '<p class="gdcpt-sh-none">'.__("None Available", "gd-taxonomies-tools").'</p>';
            } else {
                $i = 1;
                foreach ($codes as $attr => $obj) {
                    $render.= '<div class="gdcpt-sh-field gdctp-sh-field-'.($i == 1 ? 'left': ($i == 2 ? 'middle' : 'right')).'"><label title="'.$obj['description'].'">'.$obj['label'].':</label>';

                    switch ($obj['type']) {
                        default:
                        case 'input':
                            $render.= '<input gdcpt-attr="'.$obj['attr'].'" value="'.$obj['default'].'" gdctp-code="'.$attr.'" class="gdcpt-sh-input gdcpt-sh-text" type="text" />';
                            break;
                        case 'number':
                            $render.= '<input gdcpt-attr="'.$obj['attr'].'" value="'.$obj['default'].'" gdctp-code="'.$attr.'" class="gdcpt-sh-input gdcpt-sh-number" type="text" />';
                            break;
                        case 'dropdown':
                            $render.= '<select gdcpt-attr="'.$obj['attr'].'" gdctp-code="'.$attr.'" class="gdcpt-sh-select gdcpt-sh-dropdown">';
                            foreach ($obj['values'] as $val => $title) {
                                $render.= '<option value="'.$val.'"'.($val == $obj['default'] ? ' selected="selected"' : '').'>'.$title.'</option>';
                            }
                            $render.= '</select>';
                            break;
                    }

                    $render.= '</div>';

                    $i++; if ($i == 4) $i = 1;
                }
            }
        }

        die($render);
    }

    function ajax_search_term() {
        global $gdtt;

        $post = $_REQUEST;
        $terms = get_terms($post['tax'], array('hide_empty' => false, 'search' => $post['term'], 'number' => $gdtt->o['tinymce_search_limit']));

        if (count($terms) == 0) {
            die("{ \"status\": \"ok\", \"result\": \"notfound\" }");
        } else {
            $result = sprintf("{ \"status\": \"ok\", \"result\": \"found\", \"taxonomy\": \"%s\"", $post["tax"]);
            $tx = array();

            foreach ($terms as $term) {
                $tx[] = sprintf("{ \"permalink\": \"%s\", \"termname\": \"%s\", \"termslug\": \"%s\" }",
                    get_term_link($term, $post["tax"]), $term->name, $term->slug);
            }

            $result.= ", \"terms\": [".join(", ", $tx)."] }";

            die($result);
        }
    }

    function ajax_check_term() {
        $post = $_REQUEST;
        $create = isset($post['autocreate']) ? $post['autocreate'] : $post['create'];
        $term = get_term_by('name', $post['term'], $post['tax']);

        if (!$term) {
            if ($create == 1) {
                $term = wp_insert_term($post['term'], $post['tax']);
                $term = get_term($term['term_id'], $post['tax']);
            } else {
                die("{ \"status\": \"ok\", \"result\": \"notfound\" }");
            }
        }

        die(sprintf("{ \"status\": \"ok\", \"result\": \"ok\", \"taxonomy\": \"%s\", \"permalink\": \"%s\", \"termname\": \"%s\", \"termslug\": \"%s\" }",
            $post["tax"], get_term_link($term, $post["tax"]), $term->name, $term->slug));
    }

    function ajax_detach_term_image() {
        check_ajax_referer('gd-cpt-tools');

        $taxonomy = $_POST['taxonomy'];
        $term_id = $_POST['term_id'];

        gdtt_term_dettach_image($taxonomy, $term_id);

        die("{\"status\": \"ok\"}");
    }

    function ajax_attach_term_image() {
        check_ajax_referer('gd-cpt-tools');

        $taxonomy = $_POST['taxonomy'];
        $term_id = $_POST['term_id'];
        $image_id = $_POST['image_id'];

        gdtt_term_attach_image($taxonomy, $term_id, $image_id);

        $image = gdtt_get_term_image($taxonomy, $term_id, array(64, 64));
        $image = str_replace("\"", "'", $image);
        $image_url = gdtt_get_term_image($taxonomy, $term_id, 'large', 'url');

        die("{\"status\": \"ok\", \"image\": \"".$image."\", \"preview\": \"".$image_url."\"}");
    }

    function ajax_change_order() {
        check_ajax_referer('gd-cpt-tools');

        $order = array_map('intval', $_POST['list']);
        $type = $_POST['type'].'_reorder';

        global $gdtt;

        $gdtt->o[$type] = $order;
        update_option('gd-taxonomy-tools', $gdtt->o);

        die('ok');
    }

    function ajax_save_caps() {
        check_ajax_referer('gd-cpt-tools');

        $default = array(
            'cpt' => array('edit_post', 'edit_posts', 'edit_private_posts', 'edit_published_posts', 'edit_others_posts', 'publish_posts', 'read_post', 'read_private_posts', 'delete_post', 'delete_posts', 'delete_published_posts', 'delete_private_posts', 'delete_others_posts'),
            'tax' => array('manage_terms', 'edit_terms', 'delete_terms', 'assign_terms')
        );

        $mode = $_POST['mode'];
        $data = $_POST[$mode];

        $caps = get_option('gd-taxonomy-tools-caps');
        $caps[$mode][$data['info']['name']]->caps[$data['info']['role']] = array_keys($data['caps']);
        $caps[$mode][$data['info']['name']]->active[$data['info']['role']] = isset($data['info']['active']);
        update_option('gd-taxonomy-tools-caps', $caps);

        $caps_it = $caps[$mode][$data['info']['name']]->get_caps($data['info']['role'], $default[$mode]);
        $caps_al = $caps[$mode][$data['info']['name']]->make_caps($default[$mode]);

        $role_it = get_role($data['info']['role']);

        foreach ($caps_al as $cap) $role_it->remove_cap($cap);
        if ($caps[$mode][$data['info']['name']]->is_active($data['info']['role'])) {
            foreach ($caps_it as $cap) $role_it->add_cap($cap);
        }

        die(json_encode($caps));
    }

    function ajax_delete_custom_field() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok', 'delete' => 'no');
        $code = $_POST['code'];

        if ($_POST['data'] == '1') {
            global $gdtt_fields;
            $gdtt_fields->delete_custom_field_data($code);
        }

        if ($_POST['definition'] == '1') {
            if (isset($gdtt->m['fields'][$code])) {
                unset($gdtt->m['fields'][$code]);
                update_option('gd-taxonomy-tools-meta', $gdtt->m);
                $result['del_row'] = 'yes';
            }
        }

        die(json_encode(new gdrClass($result)));
    }

    function ajax_delete_metabox_group() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok', 'del_row' => 'no');
        $code = $_POST['code'];

        if ($_POST['definition'] == '1') {
            if (isset($gdtt->m['groups'][$code])) {
                unset($gdtt->m['groups'][$code]);

                if (isset($gdtt->m['map_groups'][$code])) {
                    unset($gdtt->m['map_groups'][$code]);
                }

                update_option('gd-taxonomy-tools-meta', $gdtt->m);
                $result['del_row'] = 'yes';
            }
        }

        die(json_encode(new gdrClass($result)));
    }

    function ajax_delete_metabox() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok', 'del_row' => 'no');
        $code = $_POST['code'];

        if ($_POST['definition'] == '1') {
            if (isset($gdtt->m['boxes'][$code])) {
                unset($gdtt->m['boxes'][$code]);

                if (isset($gdtt->m['map'][$code])) {
                    unset($gdtt->m['map'][$code]);
                }

                update_option('gd-taxonomy-tools-meta', $gdtt->m);
                $result['del_row'] = 'yes';
            }
        }

        die(json_encode(new gdrClass($result)));
    }

    function ajax_clear_metabox_group() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $code = $_POST['code'];
        $gdtt->m['map_groups'][$code] = array();
        update_option('gd-taxonomy-tools-meta', $gdtt->m);

        $result['code'] = $code;
        $result['map'] = array();
        die(json_encode(new gdrClass($result)));
    }

    function ajax_attach_metabox_group() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $code = $_POST['code'];
        $post_types = array_unique((array)$_POST['post_types']);
        $gdtt->m['map_groups'][$code] = $post_types;
        update_option('gd-taxonomy-tools-meta', $gdtt->m);

        $result['code'] = $code;
        $result['map'] = $post_types;
        die(json_encode(new gdrClass($result)));
    }

    function ajax_clear_metabox() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $code = $_POST['code'];
        $gdtt->m['map'][$code] = array();
        update_option('gd-taxonomy-tools-meta', $gdtt->m);

        $result['code'] = $code;
        $result['map'] = array();
        die(json_encode(new gdrClass($result)));
    }

    function ajax_attach_metabox() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $code = $_POST['code'];
        $post_types = array_unique((array)$_POST['post_types']);
        $gdtt->m['map'][$code] = $post_types;
        update_option('gd-taxonomy-tools-meta', $gdtt->m);

        $result['code'] = $code;
        $result['map'] = $post_types;
        die(json_encode(new gdrClass($result)));
    }

    function ajax_add_metabox_group() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $box = gdr2_array_map('stripslashes', $_POST);
        $box = gdr2_array_map('strip_tags', $box);
        $box = gdr2_array_map('trim', $box);

        $box['code'] = gdr2_sanitize_full($box['code']);
        $box['boxes'] = (array)$_POST['boxes'];
        $box['boxes'] = array_unique($box['boxes']);

        if ($box['method'] == 'edit') {
            $_m = new gdCPT_MetaBoxGroup($box);

            $gdtt->m['groups'][$box['code']] = $_m->get_data();

            update_option('gd-taxonomy-tools-meta', $gdtt->m);
        } else {
            if ($box['code'] != '__none__' && $box['code'] != '' && !in_array($box['code'], array_keys($gdtt->m['groups']))) {
                $_m = new gdCPT_MetaBoxGroup($box);

                $gdtt->m['groups'][$box['code']] = $_m->get_data();
                $gdtt->m['map_groups'][$box['code']] = array();

                update_option('gd-taxonomy-tools-meta', $gdtt->m);
            } else {
                $result['status'] = 'error';
                $result['error'] = __("Meta box group with this code already exists.", "gd-taxonomies-tools");
            }
        }

        if ($result['status'] == 'ok') {
            $result['group'] = $gdtt->m['groups'][$box['code']];
            $result['map'] = $gdtt->m['map_groups'][$box['code']];
        }

        die(json_encode(new gdrClass($result)));
    }

    function ajax_add_metabox() {
        global $gdtt;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $box = gdr2_array_map('stripslashes', $_POST);
        $box = gdr2_array_map('strip_tags', $box);
        $box = gdr2_array_map('trim', $box);

        $box['code'] = gdr2_sanitize_full($box['code']);
        $box['fields'] = (array)$_POST['fields'];
        $box['fields'] = array_unique($box['fields']);

        if ($box['method'] == 'edit') {
            $_m = new gdCPT_MetaBox($box);

            $gdtt->m['boxes'][$box['code']] = $_m->get_data();

            update_option('gd-taxonomy-tools-meta', $gdtt->m);
        } else {
            if ($box['code'] != '__none__' && $box['code'] != '' && !in_array($box['code'], array_keys($gdtt->m['boxes']))) {
                $_m = new gdCPT_MetaBox($box);

                $gdtt->m['boxes'][$box['code']] = $_m->get_data();
                $gdtt->m['map'][$box['code']] = array();

                update_option('gd-taxonomy-tools-meta', $gdtt->m);
            } else {
                $result['status'] = 'error';
                $result['error'] = __("Meta box with this code already exists.", "gd-taxonomies-tools");
            }
        }

        if ($result['status'] == 'ok') {
            $result['box'] = $gdtt->m['boxes'][$box['code']];
            $result['map'] = $gdtt->m['map'][$box['code']];
        }

        die(json_encode(new gdrClass($result)));
    }

    function ajax_add_custom_field() {
        global $gdtt, $gdtt_fields;

        check_ajax_referer('gd-cpt-tools');
        $result = array('status' => 'ok');

        $field = gdr2_array_map('stripslashes', $_POST);
        $field = gdr2_array_map('strip_tags', $field);
        $field = gdr2_array_map('trim', $field);
        $field['code'] = gdr2_sanitize_full($field['code']);

        $gdtt_fields->load_admin();

        if ($field['method'] == 'edit') {
            $_f = new gdCPT_CustomField($field);
            $gdtt->m['fields'][$field['code']] = $_f->get_data();
            update_option('gd-taxonomy-tools-meta', $gdtt->m);
            $gdtt->m['fields'][$field['code']]['counter'] = $gdtt_fields->count_custom_field_posts($field['code']);
        } else {
            if ($field['code'] != '__none__' && !in_array($field['code'], array_keys($gdtt->m['fields']))) {
                $_f = new gdCPT_CustomField($field);
                $gdtt->m['fields'][$field['code']] = $_f->get_data();
                update_option('gd-taxonomy-tools-meta', $gdtt->m);
                $gdtt->m['fields'][$field['code']]['counter'] = 0;
            } else {
                $result['status'] = 'error';
                $result['error'] = __("Field with this code already exists.", "gd-taxonomies-tools");
            }
        }

        if ($gdtt->m['fields'][$field['code']]['type'] == 'select') {
            $values = array();

            foreach ($gdtt->m['fields'][$field['code']]['assoc_values'] as $key => $value) {
                $values[] = $key.'|'.$value;
            }

            $gdtt->m['fields'][$field['code']]['assoc_values'] = $values;

            if ($gdtt->m['fields'][$field['code']]['values'] == '') {
                $gdtt->m['fields'][$field['code']]['values'] = array();
            }
        }

        $result['field'] = $gdtt->m['fields'][$field['code']];
        die(json_encode(new gdrClass($result)));
    }

    function generate_cpt_labels($cpt) {
        $cpt['labels']['add_new'] = __("Add New", "gd-taxonomies-tools");
        $cpt['labels']['edit'] = __("Edit", "gd-taxonomies-tools");
        $cpt['labels']['add_new_item'] = __("Add New", "gd-taxonomies-tools").' '.$cpt['labels']['singular_name'];
        $cpt['labels']['edit_item'] = __("Edit", "gd-taxonomies-tools").' '.$cpt['labels']['singular_name'];
        $cpt['labels']['new_item'] = __("New", "gd-taxonomies-tools").' '.$cpt['labels']['singular_name'];
        $cpt['labels']['view_item'] = __("View", "gd-taxonomies-tools").' '.$cpt['labels']['singular_name'];
        $cpt['labels']['search_items'] = __("Search", "gd-taxonomies-tools").' '.$cpt['labels']['name'];
        $cpt['labels']['not_found'] = __("No", "gd-taxonomies-tools").' '.$cpt['labels']['name'].' '.__("Found", "gd-taxonomies-tools");
        $cpt['labels']['not_found_in_trash'] = __("No", "gd-taxonomies-tools").' '.$cpt['labels']['name'].' '.__("Found In Trash", "gd-taxonomies-tools");
        $cpt['labels']['parent_item_colon'] = __("Parent", "gd-taxonomies-tools").' '.$cpt['labels']['name'].':';
        $cpt['labels']['all_items'] = __("All", "gd-taxonomies-tools").' '.$cpt['labels']['name'];
        $cpt['labels']['menu_name'] = $cpt['labels']['name'];

        return $cpt;
    }

    function generate_tax_labels($tax) {
        $tax['labels']['parent_item'] = __("Parent", "gd-taxonomies-tools").' '.$tax['labels']['singular_name'];
        $tax['labels']['search_items'] = __("Search", "gd-taxonomies-tools").' '.$tax['labels']['name'];
        $tax['labels']['popular_items'] = __("Popular", "gd-taxonomies-tools").' '.$tax['labels']['name'];
        $tax['labels']['all_items'] = __("All", "gd-taxonomies-tools").' '.$tax['labels']['name'];
        $tax['labels']['edit_item'] = __("Edit", "gd-taxonomies-tools").' '.$tax['labels']['singular_name'];
        $tax['labels']['view_item'] = __("View", "gd-taxonomies-tools").' '.$tax['labels']['singular_name'];
        $tax['labels']['update_item'] = __("Update", "gd-taxonomies-tools").' '.$tax['labels']['singular_name'];
        $tax['labels']['add_new_item'] = __("Add New", "gd-taxonomies-tools").' '.$tax['labels']['singular_name'];
        $tax['labels']['add_or_remove_items'] = __("Add or remove", "gd-taxonomies-tools").' '.$tax['labels']['name'];
        $tax['labels']['choose_from_most_used'] = __("Choose from the most used", "gd-taxonomies-tools").' '.$tax['labels']['name'];
        $tax['labels']['parent_item_colon'] = __("Parent", "gd-taxonomies-tools").' '.$tax['labels']['name'].':';
        $tax['labels']['new_item_name'] = __("New", "gd-taxonomies-tools").' '.$tax['labels']['name'].' '.__("Name", "gd-taxonomies-tools");
        $tax['labels']['separate_items_with_commas'] = __("Separate", "gd-taxonomies-tools").' '.$tax['labels']['name'].' '.__("with commas", "gd-taxonomies-tools");
        $tax['labels']['not_found'] = __("No", "gd-taxonomies-tools").' '.$tax['labels']['name'].' '.__("found", "gd-taxonomies-tools").'.';

        $tax['labels']['menu_name'] = $tax['labels']['name'];

        return $tax;
    }

    function save_settings_cpt_simple($cpt) {
        global $gdtt;

        $cpt['supports'] = isset($cpt['supports']) ? array_keys($cpt['supports']) : array();
	$cpt['taxonomies'] = isset($cpt['taxonomies']) ? array_keys($cpt['taxonomies']) : array();
	$cpt['special'] = isset($cpt['enhanced']) ? array_keys($cpt['enhanced']) : array();

        if (isset($cpt['enhanced'])) {
            unset($cpt['enhanced']);
        }

        $post_type = get_post_type_object($cpt['name']);

        if (isset($gdtt->nn_p['full'][$cpt['name']])) {
            $cpt_full = $gdtt->nn_p['full'][$cpt['name']];
        } else {
            $cpt_full = gdtt_get_override_post_type($cpt['name'], $post_type);
        }

        $cpt['intersections_partial'] = isset($cpt['intersections_partial']) ? 'yes' : 'no';
        if (isset($cpt['intersections_structure'])) {
            $cpt['intersections_structure'] = trim(strip_tags($cpt['intersections_structure']));
            $cpt['intersections_structure'] = str_replace(' ', '-', $cpt['intersections_structure']);

            if ($cpt['intersections_structure'] == '') {
                if ($cpt['intersections'] == 'max') $cpt['intersections'] = 'yes';
                if ($cpt['intersections'] == 'adv') $cpt['intersections'] = 'no';
            }
        } else {
            $cpt['intersections'] = 'no';
            $cpt['intersections_structure'] = '';
        }

        $cpt['intersections_baseless'] = isset($cpt['intersections_baseless']) ? $cpt['intersections_baseless'] : '';

        $cpt['date_archives'] = isset($cpt['date_archives']) ? 'yes' : 'no';
        $cpt['permalinks_active'] = isset($cpt['permalinks_active']) ? 'yes' : 'no';
        if (isset($cpt['permalinks_structure'])) {
            $cpt['permalinks_structure'] = trim(strip_tags($cpt['permalinks_structure']));
            $cpt['permalinks_structure'] = str_replace(' ', '-', $cpt['permalinks_structure']);

            if ($cpt['permalinks_structure'] == '') {
                $cpt['permalinks_active'] = 'no';
            }
        } else {
            $cpt['permalinks_structure'] = '';
        }

        $cpt_full['active'] = $cpt['active'];
        $cpt_full['supports'] = $cpt['supports'];
	$cpt_full['taxonomies'] = $cpt['taxonomies'];
	$cpt_full['special'] = $cpt['special'];
        $cpt_full['intersections_partial'] = $cpt['intersections_partial'];
        $cpt_full['intersections_structure'] = $cpt['intersections_structure'];
	$cpt_full['intersections'] = $cpt['intersections'];
	$cpt_full['date_archives'] = $cpt['date_archives'];
	$cpt_full['permalinks_active'] = $cpt['permalinks_active'];
	$cpt_full['permalinks_structure'] = $cpt['permalinks_structure'];

        $cpt = apply_filters('gdcpt_post_type_save_simple', $cpt);
        $cpt_full = apply_filters('gdcpt_post_type_save', $cpt_full);

        $gdtt->nn_p['full'][$cpt['name']] = $cpt_full;
	$gdtt->nn_p['status'][$cpt['name']] = $cpt['active'];
	$gdtt->nn_p['simple'][$cpt['name']] = $cpt;

	update_option('gd-taxonomy-tools-nn-cpt', $gdtt->nn_p);

        $gdtt->unset_cache('cpt', $cpt['name']);
        return new gdrClass(array('status' => 'ok'));
    }

    function save_settings_tax_simple($tax) {
        global $gdtt;

	$post_types = isset($tax['post_types']) ? array_keys($tax['post_types']) : array();
        $tax['domain'] = join(',', $post_types);
        $tax['metabox'] = trim(strip_tags($tax['metabox']));
        $tax['supports'] = isset($tax['supports']) ? array_keys($tax['supports']) : array();
	$tax['special'] = isset($tax['enhanced']) ? array_keys($tax['enhanced']) : array();
        $tax['cloud'] = isset($tax['cloud']) ? 'yes' : 'no';
        $tax['nav_menus'] = isset($tax['nav_menus']) ? 'yes' : 'no';
        $tax['show_admin_column'] = isset($tax['show_admin_column']) ? 'yes' : 'no';

        if (isset($tax['enhanced'])) {
            unset($tax['enhanced']);
        }

        $tax_type = get_taxonomy($tax['name']);
        if (isset($gdtt->nn_t['full'][$tax['name']])) {
            $tax_full = $gdtt->nn_t['full'][$tax['name']];
        } else {
            $tax_full = gdtt_get_override_taxonomy($tax['name'], $tax_type);
        }

        $tax_full['active'] = $tax['active'];
        $tax_full['supports'] = $tax['supports'];
        $tax_full['metabox'] = $tax['metabox'];
        $tax_full['domain'] = $tax['domain'];
        $tax_full['special'] = $tax['special'];
        $tax_full['cloud'] = $tax['cloud'];
        $tax_full['nav_menus'] = $tax['nav_menus'];
        $tax_full['show_admin_column'] = $tax['show_admin_column'];

        $tax = apply_filters('gdcpt_taxonomy_save_simple', $tax);
        $tax_full = apply_filters('gdcpt_taxonomy_save', $tax_full);

        $gdtt->nn_t['full'][$tax['name']] = $tax_full;
        $gdtt->nn_t['status'][$tax['name']] = $tax['active'];
        $gdtt->nn_t['simple'][$tax['name']] = $tax;

        update_option('gd-taxonomy-tools-nn-tax', $gdtt->nn_t);

        $gdtt->unset_cache('tax', $tax['name']);
        return new gdrClass(array('status' => 'ok'));
    }

    function save_settings_cpt_quick($ajax) {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');

        global $gdtt;

        $cpt = gdCPTAdmin_Panels::get_default_cpt();

        $errors = $blocks = array();
        $result = array('status' => 'ok');

        $cpt['hierarchy'] = isset($ajax['hierarchy']) ? 'yes' : 'no';
	$cpt['taxonomies'] = isset($ajax['taxonomies']) ? array_keys($ajax['taxonomies']) : array();
        $cpt['name'] = trim(strtolower(sanitize_user($ajax['name'], true)));

        if (empty($cpt['name'])) {
            $errors[] = array('cpt_name', __("Name is required.", "gd-taxonomies-tools"));
        } else if (!$this->is_term_valid($cpt["name"])) {
            $errors[] = array('cpt_name', __("Name you used is not valid.", "gd-taxonomies-tools"));
        } else if (in_array($cpt["name"], $gdtt->reserved_names)) {
            $errors[] = array('cpt_name', __("Name you used is reserved.", "gd-taxonomies-tools"));
        }

        if (!empty($errors)) {
            $blocks[] = array('.gdr2-panel-basics .gdr2-group-name');
        }

        if ($ajax['labels']['name'] === '') {
            $errors[] = array('cpt_labels_name', __("Label value is required.", "gd-taxonomies-tools"));
            $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels');
        } else {
            $cpt['labels']['name'] = $ajax['labels']['name'];
        }

        if ($ajax['labels']['singular_name'] === '') {
            $errors[] = array('cpt_labels_singular_name', __("Label value is required.", "gd-taxonomies-tools"));
            $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels');
        } else {
            $cpt['labels']['singular_name'] = $ajax['labels']['singular_name'];
        }

        if (empty($errors)) {
            $cpt = $this->generate_cpt_labels($cpt);

            $cpt = apply_filters('gdcpt_post_type_quick_save', $cpt);

            gdtt_insert_post_type($cpt);
        } else {
            $result['status'] = 'error';
            $result['errors'] = $errors;
            $result['groups'] = $blocks;
        }

        return new gdrClass($result);
    }

    function save_settings_tax_quick($ajax) {
        include(GDTAXTOOLS_PATH.'code/internal/panels.php');

        global $gdtt;

        $tax = gdCPTAdmin_Panels::get_default_tax();

        $errors = $blocks = array();
        $result = array('status' => 'ok');

        $post_types = isset($ajax['post_types']) ? array_keys($ajax['post_types']) : array();
        $tax['hierarchy'] = isset($ajax['hierarchy']) ? 'yes' : 'no';
        $tax['domain'] = join(',', $post_types);
        $tax['name'] = trim(strtolower(sanitize_user($ajax['name'], true)));

        $tax['index_normal'] = isset($tax['index_normal']) ? 'yes' : 'no';
        $tax['index_intersect'] = isset($tax['show_admin_column']) ? 'yes' : 'no';

        if (empty($tax['name'])) {
            $errors[] = array('tax_name', __("Name is required.", "gd-taxonomies-tools"));
        } else if (!$this->is_term_valid($tax["name"])) {
            $errors[] = array('tax_name', __("Name you used is not valid.", "gd-taxonomies-tools"));
        } else if (in_array($tax["name"], $gdtt->reserved_names)) {
            $errors[] = array('tax_name', __("Name you used is reserved.", "gd-taxonomies-tools"));
        }

        if (!empty($errors)) {
            $blocks[] = array('.gdr2-panel-basics .gdr2-group-name');
        }

        if ($ajax['labels']['name'] === '') {
            $errors[] = array('cpt_labels_name', __("Label value is required.", "gd-taxonomies-tools"));
            $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels');
        } else {
            $tax['labels']['name'] = $ajax['labels']['name'];
        }

        if ($ajax['labels']['singular_name'] === '') {
            $errors[] = array('cpt_labels_singular_name', __("Label value is required.", "gd-taxonomies-tools"));
            $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels');
        } else {
            $tax['labels']['singular_name'] = $ajax['labels']['singular_name'];
        }

        if (empty($errors)) {
            $tax = $this->generate_tax_labels($tax);

            $tax = apply_filters('gdcpt_taxonomy_quick_save', $tax);

            gdtt_insert_taxonomy($tax);
        } else {
            $result['status'] = 'error';
            $result['errors'] = $errors;
            $result['groups'] = $blocks;
        }

        return new gdrClass($result);
    }

    function save_settings_cpt_full($cpt, $editable = true) {
        global $gdtt;

        $errors = $blocks = array();
        $result = array('status' => 'ok');

        $cpt['id'] = intval($cpt['id']);
        if ($cpt['id'] > -1) {
            $cpt['active'] = isset($cpt['active']) ? 1 : 0;
        }

        $cpt['hierarchy'] = isset($cpt['hierarchy']) ? 'yes' : 'no';
        $cpt['publicly_queryable'] = isset($cpt['publicly_queryable']) ? 'yes' : 'no';
        $cpt['rewrite_feeds'] = isset($cpt['rewrite_feeds']) ? 'yes' : 'no';
        $cpt['rewrite_pages'] = isset($cpt['rewrite_pages']) ? 'yes' : 'no';
        $cpt['rewrite_front'] = isset($cpt['rewrite_front']) ? 'yes' : 'no';
        $cpt['public'] = isset($cpt['public']) ? 'yes' : 'no';
        $cpt['ui'] = isset($cpt['ui']) ? 'yes' : 'no';
        $cpt['exclude_from_search'] = isset($cpt['exclude_from_search']) ? 'yes' : 'no';
        $cpt['nav_menus'] = isset($cpt['nav_menus']) ? 'yes' : 'no';
        $cpt['show_in_menu'] = isset($cpt['show_in_menu']) ? 'yes' : 'no';
        $cpt['show_in_admin_bar'] = isset($cpt['show_in_admin_bar']) ? 'yes' : 'no';
        $cpt['can_export'] = isset($cpt['can_export']) ? 'yes' : 'no';

        $cpt['yourls_active_link'] = isset($cpt['yourls_active_link']) ? 'yes' : 'no';

        $cpt['archive_slug'] = trim(str_replace(' ', '', strip_tags($cpt['archive_slug'])));
        $cpt['rewrite_slug'] = trim(str_replace(' ', '', strip_tags($cpt['rewrite_slug'])));
        $cpt['query_slug'] = trim(strtolower(gdr2_sanitize_custom($cpt['query_slug'], array('replacement' => '-'))));

        $cpt['description'] = trim(strip_tags($cpt['description']));
        $cpt['caps_type'] = trim(strip_tags($cpt['caps_type']));
        $cpt['edit_link'] = trim(strip_tags($cpt['edit_link']));

        if ($cpt['edit_link'] == '') {
            $cpt['edit_link'] = 'post.php?post=%d';
        }

        if (!isset($cpt['icon'])) {
            $cpt['icon'] = '';
        }

        $cpt['intersections_partial'] = isset($cpt['intersections_partial']) ? 'yes' : 'no';
        if (isset($cpt['intersections_structure'])) {
            $cpt['intersections_structure'] = trim(strip_tags($cpt['intersections_structure']));
            $cpt['intersections_structure'] = str_replace(' ', '-', $cpt['intersections_structure']);

            if ($cpt['intersections_structure'] == '') {
                if ($cpt['intersections'] == 'max') $cpt['intersections'] = 'yes';
                if ($cpt['intersections'] == 'adv') $cpt['intersections'] = 'no';
            }
        } else {
            $cpt['intersections_structure'] = '';
        }

        $cpt['date_archives'] = isset($cpt['date_archives']) ? 'yes' : 'no';
        $cpt['permalinks_active'] = isset($cpt['permalinks_active']) ? 'yes' : 'no';
        if (isset($cpt['permalinks_structure'])) {
            $cpt['permalinks_structure'] = trim(strip_tags($cpt['permalinks_structure']));
            $cpt['permalinks_structure'] = str_replace(' ', '-', $cpt['permalinks_structure']);

            if ($cpt['permalinks_structure'] == '') {
                $cpt['permalinks_active'] = 'no';
            }
        } else {
            $cpt['permalinks_structure'] = '';
        }

        $this->add_cpt_caps($cpt['caps_type']);

        $cpt['caps'] = isset($cpt['caps']) ? $cpt['caps'] : $gdtt->post_type_caps;
        $cpt['supports'] = isset($cpt['supports']) ? array_keys($cpt['supports']) : array();
	$cpt['taxonomies'] = isset($cpt['taxonomies']) ? array_keys($cpt['taxonomies']) : array();
	$cpt['special'] = isset($cpt['enhanced']) ? array_keys($cpt['enhanced']) : array();

        if (isset($cpt['enhanced'])) {
            unset($cpt['enhanced']);
        }

        if ($editable) {
            $cpt['name'] = trim(strtolower(sanitize_user($cpt['name'], true)));

            if (empty($cpt['name'])) {
                $errors[] = array('cpt_name', __("Name is required.", "gd-taxonomies-tools"));
            } else if (!$this->is_term_valid($cpt["name"])) {
                $errors[] = array('cpt_name', __("Name you used is not valid.", "gd-taxonomies-tools"));
            } else if (in_array($cpt["name"], $gdtt->reserved_names)) {
                $errors[] = array('cpt_name', __("Name you used is reserved.", "gd-taxonomies-tools"));
            }

            if (!empty($errors)) {
                $blocks[] = array('.gdr2-panel-basics .gdr2-group-name');
            }
        }

        foreach ($cpt['labels'] as $key => $label) {
            $cpt['labels'][$key] = trim($label);

            if ($cpt['labels'][$key] === "") {
                $error = array('cpt_labels_'.$key, __("Label value is required.", "gd-taxonomies-tools"));

                if ($key != 'name' && $key != 'singular_name') {
                    $error[1].= " ".__("You can use Auto Fill button to get this value.", "gd-taxonomies-tools");
                    $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels_expanded');
                } else {
                    $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels_basic');
                }

                $errors[] = $error;
            }
        }

        if (empty($errors)) {
            $this->save_yourls_options($cpt['name'], $cpt['yourls_active_link'] == 'yes');

            $cpt = apply_filters('gdcpt_post_type_save', $cpt);

            $new_id = gdtt_insert_post_type($cpt);

            if (!is_null($new_id)) {
                $result['id'] = $new_id;
            }
        } else {
            $result['status'] = 'error';
            $result['errors'] = $errors;
            $result['groups'] = $blocks;
        }

        $gdtt->unset_cache('cpt', $cpt['name']);
        return new gdrClass($result);
    }

    function save_settings_tax_full($tax, $editable = true) {
        global $gdtt;

        $errors = $blocks = array();
        $result = array('status' => 'ok');

        $tax['id'] = intval($tax['id']);
        if ($tax['id'] > -1) {
            $tax['active'] = isset($tax['active']) ? 1 : 0;
        }

        $post_types = isset($tax['post_types']) ? array_keys($tax['post_types']) : array();
        $tax['domain'] = join(',', $post_types);
        $tax['supports'] = isset($tax['supports']) ? array_keys($tax['supports']) : array();
	$tax['special'] = isset($tax['enhanced']) ? array_keys($tax['enhanced']) : array();

        if (isset($tax['enhanced'])) {
            unset($tax['enhanced']);
        }

        $tax['description'] = trim(strip_tags($tax['description']));

        $tax['caps_type'] = trim(strip_tags($tax['caps_type']));
        $tax['metabox'] = trim(strip_tags($tax['metabox']));
        $tax['query'] = trim(strip_tags($tax['query']));
        $tax['rewrite'] = trim(strip_tags($tax['rewrite']));

        $tax['rewrite_custom'] = trim(str_replace(' ', '', strip_tags($tax['rewrite_custom'])));
        $tax['query_custom'] = trim(strtolower(gdr2_sanitize_custom($tax['query_custom'], array('replacement' => '-'))));

        $this->add_tax_caps($tax['caps_type']);

        $tax['hierarchy'] = isset($tax['hierarchy']) ? 'yes' : 'no';
        $tax['rewrite_hierarchy'] = isset($tax['rewrite_hierarchy']) ? 'yes' : 'no';
        $tax['rewrite_front'] = isset($tax['rewrite_front']) ? 'yes' : 'no';
        $tax['public'] = isset($tax['public']) ? 'yes' : 'no';
        $tax['ui'] = isset($tax['ui']) ? 'yes' : 'no';
        $tax['cloud'] = isset($tax['cloud']) ? 'yes' : 'no';
        $tax['sort'] = isset($tax['sort']) ? 'yes' : 'no';
        $tax['nav_menus'] = isset($tax['nav_menus']) ? 'yes' : 'no';
        $tax['show_admin_column'] = isset($tax['show_admin_column']) ? 'yes' : 'no';

        $tax['index_normal'] = isset($tax['index_normal']) ? 'yes' : 'no';
        $tax['index_intersect'] = isset($tax['show_admin_column']) ? 'yes' : 'no';

        if ($editable) {
            $tax['name'] = trim(strtolower(sanitize_user($tax['name'], true)));

            if (empty($tax['name'])) {
                $errors[] = array('tax_name', __("Name is required.", "gd-taxonomies-tools"));
            } else if (!$this->is_term_valid($tax["name"])) {
                $errors[] = array('tax_name', __("Name you used is not valid.", "gd-taxonomies-tools"));
            } else if (in_array($tax["name"], $gdtt->reserved_names)) {
                $errors[] = array('tax_name', __("Name you used is reserved.", "gd-taxonomies-tools"));
            }

            if (!empty($errors)) {
                $blocks[] = array('.gdr2-panel-basics .gdr2-group-name');
            }
        }

        foreach ($tax['labels'] as $key => $label) {
            $tax['labels'][$key] = trim($label);

            if ($tax['labels'][$key] === "") {
                $error = array('tax_labels_'.$key, __("Label value is required.", "gd-taxonomies-tools"));

                if ($key != 'name' && $key != 'singular_name') {
                    $error[1].= " ".__("You can use Auto Fill button to get this value.", "gd-taxonomies-tools");
                    $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels_expanded');
                } else {
                    $blocks[] = array('.gdr2-panel-basics .gdr2-group-labels_basic');
                }

                $errors[] = $error;
            }
        }

        if (empty($errors)) {
            $tax = apply_filters('gdcpt_taxonomy_save', $tax);

            $new_id = gdtt_insert_taxonomy($tax);

            if (!is_null($new_id)) {
                $result['id'] = $new_id;
            }
        } else {
            $result['status'] = 'error';
            $result['errors'] = $errors;
            $result['groups'] = $blocks;
        }

        $gdtt->unset_cache('tax', $tax['name']);
        return new gdrClass($result);
    }

    function ajax_save_settings() {
        check_ajax_referer('gd-cpt-tools');

        $data = $_POST;
        $resp = null;
        switch ($data['gdr2_action']) {
            case 'cpt-simple':
                $resp = $this->save_settings_cpt_simple($data['cpt']);
                break;
            case 'tax-simple':
                $resp = $this->save_settings_tax_simple($data['tax']);
                break;
            case 'cpt-quick':
                $resp = $this->save_settings_cpt_quick($data['cpt']);
                break;
            case 'tax-quick':
                $resp = $this->save_settings_tax_quick($data['tax']);
                break;
            case 'cpt-full':
                $resp = $this->save_settings_cpt_full($data['cpt'], $data['gdr2_editable'] == 'yes');
                break;
            case 'tax-full':
                $resp = $this->save_settings_tax_full($data['tax'], $data['gdr2_editable'] == 'yes');
                break;
            default:
                break;
        }

        die(json_encode($resp));
    }
}

class gdCPT_CustomField extends gdrBase {
    public $code = '';
    public $name = '';
    public $description = '';
    public $fnc_name = '';
    public $type = 'text';
    public $values = array();
    public $assoc_values = array();
    public $required = false;
    public $default = '';
    public $selection = 'select';
    public $selmethod = 'normal';
    public $limit = 0;
    public $format = '';
    public $datesave = 'dashed';
    public $rewrite = '__none__';
    public $regex = '__none__';
    public $regex_custom = '';
    public $mask_custom = '';
    public $unit = '';

    public $user_access = 'none';
    public $user_roles = '';
    public $user_caps = '';

    function __construct($field = array()) {
        if ($field['required'] == 'false') {
            $field['required'] = false;
        }

        if ($field['required'] == 'true') {
            $field['required'] = true;
        }

        $this->code = $field['code'];
        $this->name = $field['name'];
        $this->description = $field['description'];
        $this->type = $field['type'];
        $this->fnc_name = $field['fnc_name'];
        $this->selection = $field['selection'];
        $this->selmethod = $field['selmethod'];
        $this->limit = $field['limit'];
        $this->format = $field['format'];
        $this->datesave = $field['datesave'];
        $this->rewrite = $field['rewrite'];
        $this->unit = $field['unit'];
        $this->regex = $field['regex'];
        $this->regex_custom = $field['regex_custom'];
        $this->mask_custom = $field['mask_custom'];
        $this->user_access = $field['user_access'];
        $this->values = $field['values'];

        $field['user_roles'] = trim($field['user_roles']);
        if (strlen($field['user_roles']) > 0) {
            $tmp = explode(',', $field['user_roles']);
            $this->user_roles = join(',', array_map('trim', $tmp));
        }

        $field['user_caps'] = trim($field['user_caps']);
        if (strlen($field['user_caps']) > 0) {
            $tmp = explode(',', $field['user_caps']);
            $this->user_caps = join(',', array_map('trim', $tmp));
        }

        if ($this->type == 'listing' || $this->type == 'select') {
            $this->values = gdr2_split_textarea($field['values']);
        }

        $values = gdr2_split_textarea($field['assoc_values']);
        $this->assoc_values = array();

        foreach ($values as $val) {
            if (!empty($val)) {
                $val = explode('|', $val, 2);

                if (count($val) == 1) {
                    $this->assoc_values[$val[0]] = $val[0];
                } else {
                    $this->assoc_values[$val[0]] = $val[1];
                }
            }
        }

        $this->required = $field['required'];

        do_action_ref_array('gdcpt_clean_custom_field_'.$this->type, array(&$this));
    }

    public function get_data() {
        return (array)$this;
    }
}

class gdCPT_MetaBox extends gdrBase {
    public $code = '';
    public $name = '';
    public $description = '';

    public $fields = array();

    public $location = 'advanced';
    public $repeater = 'no';

    public $user_access = 'none';
    public $user_roles = '';
    public $user_caps = '';

    function __construct($box) {
        $this->code = $box['code'];
        $this->name = $box['name'];
        $this->location = $box['location'];
        $this->repeater = $box['repeater'];
        $this->description = $box['description'];
        $this->fields = (array)$box['fields'];
        $this->user_access = $box['user_access'];

        $box['user_roles'] = trim($box['user_roles']);
        if (strlen($box['user_roles']) > 0) {
            $tmp = explode(',', $box['user_roles']);
            $this->user_roles = join(',', array_map('trim', $tmp));
        }

        $box['user_caps'] = trim($box['user_caps']);
        if (strlen($box['user_caps']) > 0) {
            $tmp = explode(',', $box['user_caps']);
            $this->user_caps = join(',', array_map('trim', $tmp));
        }

        do_action_ref_array('gdcpt_clean_meta_box', array(&$this));
    }

    public function get_data() {
        return (array)$this;
    }
}

class gdCPT_MetaBoxGroup extends gdrBase {
    public $code = '';
    public $name = '';

    public $boxes = array();

    public $location = 'advanced';

    public $user_access = 'none';
    public $user_roles = '';
    public $user_caps = '';

    function __construct($box) {
        $this->code = $box['code'];
        $this->name = $box['name'];
        $this->location = $box['location'];
        $this->boxes = (array)$box['boxes'];
        $this->user_access = $box['user_access'];

        $box['user_roles'] = trim($box['user_roles']);
        if (strlen($box['user_roles']) > 0) {
            $tmp = explode(',', $box['user_roles']);
            $this->user_roles = join(',', array_map('trim', $tmp));
        }

        $box['user_caps'] = trim($box['user_caps']);
        if (strlen($box['user_caps']) > 0) {
            $tmp = explode(',', $box['user_caps']);
            $this->user_caps = join(',', array_map('trim', $tmp));
        }

        do_action_ref_array('gdcpt_clean_meta_box_group', array(&$this));
    }

    public function get_data() {
        return (array)$this;
    }
}

?>