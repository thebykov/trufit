<?php

if (!defined('ABSPATH')) exit;

class gdCPTShortcodes extends gdr2_Shortcodes {
    public $access_filter = 'gdcpt_access_shortcode_';

    public function init() {
        $this->shortcodes = array(
            'cpt_field' => array(
                'name' => __("Custom Field Value", "gd-taxonomies-tools"),
                'alias' => array('cpt_gmap')
            ),
            'cpt_termlink' => array(
                'name' => __("Link to taxonomy term", "gd-taxonomies-tools"),
                'atts' => array('tax' => 'post_tag', 'term' => '', 'target' => '', 'rel' => ''),
                'alias' => array('termlink')
            ),
            'cpt_posttypes' => array(
                'name' => __("List of Post Types", "gd-taxonomies-tools"),
                'atts' => array('list' => '', 'counts' => true, 'current' => true)
            ),
            'cpt_termscloud' => array(
                'name' => __("Terms Cloud", "gd-taxonomies-tools"),
                'atts' => array('post_types' => '', 'taxonomy' => 'post_tag', 'number' => 45, 'unit' => 'pt', 'smallest' => 8, 'largest' => 22, 'orderby' => 'name', 'order' => 'asc', 'hide_empty' => 1, 'current' => 1, 'exclude' => '')
            ),
            'cpt_termslist' => array(
                'name' => __("Terms List", "gd-taxonomies-tools"),
                'atts' => array('post_types' => '', 'taxonomy' => 'post_tag', 'number' => 10, 'orderby' => 'name', 'order' => 'asc', 'hide_empty' => 1, 'current' => 1, 'exclude' => '', 'display' => 'list', 'hierarchy' => 1, 'show_option_none' => 'Select Term', 'count' => 1)
            )
        );
    }

    public function shortcode_cpt_field($atts) {
        global $gdtt, $post;

        $field_name = $atts['code'];
        $post_id = !isset($atts['post']) || $atts['post'] == 0 ? $post->ID : $atts['post'];

        $field = isset($gdtt->m['fields'][$field_name]) ? $gdtt->m['fields'][$field_name] : array('type' => 'text');
        $values = get_post_meta($post_id, $field_name);

        return $this->show($gdtt->prepare_cpt_field($field, $values, $atts), 'cpt_field', $atts);
    }

    public function shortcode_cpt_termlink($atts, $content = '') {
        $atts = $this->atts('cpt_termlink', $atts);

        $term_link = get_term_link($atts['term'], $atts['tax']);
        if (is_string($term_link)) {
            if ($content == '') {
                $term = &get_term($atts['term'], $atts['tax']);
                $content = $term->name;
            }

            return sprintf('<a href="%s"%s%s>%s</a>',
                    get_term_link($atts['term'], $atts['tax']),
                    $atts['target'] != '' ? ' target="'.$atts['target'].'"' : '',
                    $atts['rel'] != '' ? ' rel="'.$atts['rel'].'"' : '',
                    $content);
        }

        return $this->show($content, 'cpt_termlink', $atts);
    }

    public function shortcode_cpt_posttypes($atts, $content = '') {
        $atts = $this->atts('cpt_posttypes', $atts);

        if ($atts['list'] == '') {
            $atts['list'] = gdtt_get_public_post_types(true);
            $atts['list'] = array_keys($atts['list']);
        } else {
            $atts['list'] = explode(',', $atts['list']);
        }

        $args = array(
            'list' => $atts['list'],
            'counts' => $atts['counts'],
            'mark_current' => $atts['current'],
            'display_css' => $atts['class']
        );

        return $this->show(gdtt_widget_posttypes_list($args), 'cpt_posttypes', $atts);
    }

    public function shortcode_cpt_termscloud($atts, $content = '') {
        $atts = $this->atts('cpt_termscloud', $atts);

        $atts['mark_current'] = $atts['current'];
        $atts['display_css'] = $atts['class'];
        unset($atts['current']);
        unset($atts['class']);

        return $this->show(gdtt_widget_terms_cloud($atts), 'cpt_termscloud', $atts);
    }

    public function shortcode_cpt_termslist($atts, $content = '') {
        $atts = $this->atts('cpt_termslist', $atts);

        $atts['mark_current'] = $atts['current'];
        $atts['display_css'] = $atts['class'];
        $atts['display_render'] = $atts['display'];
        $atts['display_count'] = $atts['count'];
        $atts['display_hierarchy'] = $atts['hierarchy'];
        unset($atts['current']);
        unset($atts['class']);
        unset($atts['display']);
        unset($atts['count']);
        unset($atts['hierarchy']);

        return $this->show(gdtt_widget_terms_list($atts), 'cpt_termslist', $atts);
    }
}

global $gdtt_shortcodes;
$gdtt_shortcodes = new gdCPTShortcodes();

?>