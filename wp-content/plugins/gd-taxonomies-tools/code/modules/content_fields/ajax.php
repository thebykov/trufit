<?php

if (!defined('ABSPATH')) exit;

class gdCPTCore_Content_Fields_Ajax {
    function __construct() {
        add_action('wp_ajax_gdcpt_mod_cf_get_posts', array(&$this, 'get_posts'));
        add_action('wp_ajax_gdcpt_mod_cf_get_terms', array(&$this, 'get_terms'));
        add_action('wp_ajax_gdcpt_mod_cf_get_users', array(&$this, 'get_users'));
    }

    public function get_posts() {
        check_ajax_referer('gdcptools');

        $posts = get_posts(array('post_type' => $_POST['p'], 'numberposts' => 64, 's' => $_POST['q']));
        if (empty($posts)) {
            die('<p class="gdtt-warning">'.__("No results found.", "gd-taxonomies-tools").'</p>');
        } else {
            echo '<ul>';

            foreach ($posts as $post) {
                echo '<li gdtt-id="'.$post->ID.'"><span>'.$post->post_title.'</span><em>'.$post->post_date.'</em></li>';
            }

            echo '</ul>';
        }

        exit;
    }

    public function get_terms() {
        check_ajax_referer('gdcptools');

        $terms = get_terms($_POST['p'], array('number' => 64, 'hide_empty' => false, 'name__like' => $_POST['q']));
        if (empty($terms)) {
            die('<p class="gdtt-warning">'.__("No results found.", "gd-taxonomies-tools").'</p>');
        } else {
            echo '<ul>';

            foreach ($terms as $term) {
                echo '<li gdtt-id="'.$term->term_id.'">'.$term->name.'</li>';
            }

            echo '</ul>';
        }

        exit;
    }

    public function get_users() {
        check_ajax_referer('gdcptools');

        $cls = explode('_', $_POST['p'], 2);
        
        $users = array();
        if ($cls[0] == 'misc') {
            if ($cls[1] == 'all') {
                $users = get_users(array('number' => 64, 'search' => $_POST['q'].'*'));
            } else if ($cls[1] == 'authors') {
                
            }
        } else if ($cls[0] == 'role') {
            $users = get_users(array('role' => $cls[1], 'number' => 64, 'search' => $_POST['q']));
        }

        if (empty($users)) {
            die('<p class="gdtt-warning">'.__("No results found.", "gd-taxonomies-tools").'</p>');
        } else {
            echo '<ul>';

            foreach ($users as $user) {
                echo '<li gdtt-id="'.$user->ID.'"><span>'.$user->user_nicename.'</span><em>'.$user->user_registered.' | '.$user->display_name.'</em></li>';
            }

            echo '</ul>';
        }

        exit;
    }
}

global $gdtt_content_fields_ajax;
$gdtt_content_fields_ajax = new gdCPTCore_Content_Fields_Ajax();

?>