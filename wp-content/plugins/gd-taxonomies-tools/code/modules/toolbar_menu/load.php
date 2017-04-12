<?php

if (!defined('ABSPATH')) exit;

class gdCPTToolbarMenu {
    public $version;

    function __construct() {
        if (gdtt_mod('toolbar_menu', 'active')) {
            add_action('admin_bar_menu', array(&$this, 'admin_bar_menu'), 100);

            if (gdtt_mod('toolbar_menu', 'icon')) {
                add_action('admin_head', array(&$this, 'admin_bar_icon'));
                add_action('wp_head', array(&$this, 'admin_bar_icon'));
            }
        }
    }

    public function admin_bar_icon() { ?>
        <style type="text/css">
            #wpadminbar .ab-top-menu > li.menupop.icon-gdcpt-toolbar > .ab-item {
                background-image: url('<?php echo plugins_url('gd-taxonomies-tools/gfx/menu/icon_16.png'); ?>');
                background-repeat: no-repeat;
                background-position: 0.85em 50%;
                padding-left: 32px;
            }
        </style>
        <?php if (GDTAXTOOLS_WPV > 37) { ?>
        <style type="text/css">
            @media screen and ( max-width: 782px ) {
                #wpadminbar li#wp-admin-bar-gdcpt-toolbar {
                    display: block;
                }

                #wpadminbar li#wp-admin-bar-gdcpt-toolbar > .ab-item {
                    background-image: url('<?php echo plugins_url('gd-taxonomies-tools/gfx/menu/icon_32.png'); ?>');
                    padding-left: 48px;
                    text-indent: -1000px;
                    outline: none;
                }
            }
        </style>
        <?php } ?>
    <?php }

    public function admin_bar_menu($wp_admin_bar) {
        if (current_user_can('gdcpttools_basic')) {
            global $gdtt, $wp_post_types, $wp_taxonomies;
            $action_url = esc_url_raw(add_query_arg("_gdcpt_nonce", wp_create_nonce("gd-cpt-tools")));

            $wp_admin_bar->add_menu(array(
                'id'     => 'gdcpt-toolbar',
                'title'  => __("CPT Tools", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_front'),
                'meta'   => array('class' => 'icon-gdcpt-toolbar')
            ));

            $wp_admin_bar->add_group(array(
                'parent' => 'gdcpt-toolbar',
                'id'     => 'gdcpt-toolbar-data'
            ));

            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-data',
                'id'     => 'gdcpt-toolbar-cpt',
                'title'  => __("Post Types", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_postypes')
            ));

            foreach ($wp_post_types as $name => $pt) {
                $href_list = $pt->show_ui ? admin_url('edit.php?post_type='.$name) : 
                    ($name == 'attachment' ? admin_url('upload.php') : '');
                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-cpt',
                    'id'     => 'gdcpt-toolbar-cpt-'.$name,
                    'title'  => $pt->label,
                    'href'   => $href_list
                ));
                if (isset($gdtt->loaded['cpt'][$name])) {
                    $wp_admin_bar->add_menu(array(
                        'parent' => 'gdcpt-toolbar-cpt-'.$name,
                        'id'     => 'gdcpt-toolbar-cpt-'.$name.'-edit',
                        'title'  => __("Edit Post Type", "gd-taxonomies-tools"),
                        'href'   => admin_url('admin.php?page=gdtaxtools_postypes&action=edit&cpt=1&pid='.$gdtt->loaded['cpt'][$name])
                    ));
                }
                if ($pt->show_ui || $pt->has_archive) {
                    $wp_admin_bar->add_group(array(
                        'parent' => 'gdcpt-toolbar-cpt-'.$name,
                        'id'     => 'gdcpt-toolbar-cpt-'.$name.'-group',
                    ));
                    if ($pt->show_ui) {
                        $wp_admin_bar->add_menu(array(
                            'parent' => 'gdcpt-toolbar-cpt-'.$name.'-group',
                            'id'     => 'gdcpt-toolbar-cpt-'.$name.'-new',
                            'title'  => $pt->labels->add_new_item,
                            'href'   => admin_url('post-new.php?post_type='.$name)
                        ));
                    }
                    if ($pt->has_archive) {
                        $wp_admin_bar->add_menu(array(
                            'parent' => 'gdcpt-toolbar-cpt-'.$name.'-group',
                            'id'     => 'gdcpt-toolbar-cpt-'.$name.'-archive',
                            'title'  => $pt->label.' '.__("Archives", "gd-taxonomies-tools"),
                            'href'   => get_post_type_archive_link($name)
                        ));
                    }
                }
            }

            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-data',
                'id'     => 'gdcpt-toolbar-tax',
                'title'  => __("Taxonomies", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_taxs')
            ));

            foreach ($wp_taxonomies as $name => $tax) {
                $href_list = isset($gdtt->loaded['tax'][$name]) ? admin_url('admin.php?page=gdtaxtools_taxs&action=edit&cpt=1&tid='.$gdtt->loaded['tax'][$name]) : '';
                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-tax',
                    'id'     => 'gdcpt-toolbar-tax-'.$name,
                    'title'  => $tax->label,
                    'href'   => $href_list
                ));
            }

            if (gdtt_mod('toolbar_menu', 'create_new')) {
                $wp_admin_bar->add_group(array(
                    'parent' => 'gdcpt-toolbar',
                    'id'     => 'gdcpt-toolbar-create'
                ));

                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-create',
                    'id'     => 'gdcpt-toolbar-create-cpt',
                    'title'  => __("New Post Type", "gd-taxonomies-tools")
                ));

                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-create-cpt',
                    'id'     => 'gdcpt-toolbar-create-cpt-quick',
                    'title'  => __("Add Quick", "gd-taxonomies-tools"),
                    'href'   => admin_url('admin.php?page=gdtaxtools_postypes&action=addquick'),
                ));

                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-create-cpt',
                    'id'     => 'gdcpt-toolbar-create-cpt-new',
                    'title'  => __("Add Full", "gd-taxonomies-tools"),
                    'href'   => admin_url('admin.php?page=gdtaxtools_postypes&action=addnew'),
                ));

                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-create',
                    'id'     => 'gdcpt-toolbar-create-tax',
                    'title'  => __("New Taxonomy", "gd-taxonomies-tools")
                ));

                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-create-tax',
                    'id'     => 'gdcpt-toolbar-create-tax-quick',
                    'title'  => __("Add Quick", "gd-taxonomies-tools"),
                    'href'   => admin_url('admin.php?page=gdtaxtools_taxs&action=addquick'),
                ));

                $wp_admin_bar->add_menu(array(
                    'parent' => 'gdcpt-toolbar-create-tax',
                    'id'     => 'gdcpt-toolbar-create-tax-new',
                    'title'  => __("Add Full", "gd-taxonomies-tools"),
                    'href'   => admin_url('admin.php?page=gdtaxtools_taxs&action=addnew'),
                ));
            }

            $wp_admin_bar->add_group(array(
                'parent' => 'gdcpt-toolbar',
                'id'     => 'gdcpt-toolbar-plugin'
            ));

            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-plugin',
                'id'     => 'gdcpt-toolbar-plugin-metas',
                'title'  => __("Meta Boxes", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_metas'),
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-plugin',
                'id'     => 'gdcpt-toolbar-plugin-modules',
                'title'  => __("Modules", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_modules'),
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-plugin',
                'id'     => 'gdcpt-toolbar-plugin-settings',
                'title'  => __("Settings", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_settings'),
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-plugin',
                'id'     => 'gdcpt-toolbar-plugin-tools',
                'title'  => __("Tools", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_tools'),
            ));

            $wp_admin_bar->add_group(array(
                'parent' => 'gdcpt-toolbar',
                'id'     => 'gdcpt-toolbar-info',
                'meta'   => array('class' => 'ab-sub-secondary')
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-info',
                'id'     => 'gdcpt-toolbar-info-links',
                'title'  => __("Information", "gd-taxonomies-tools")
            ));
            $wp_admin_bar->add_group(array(
                'parent' => 'gdcpt-toolbar-info-links',
                'id'     => 'gdcpt-toolbar-info-links-local',
                'meta'   => array('class' => 'ab-sub-secondary')
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-info-links-local',
                'id'     => 'gdcpt-toolbar-about',
                'title'  => __("About", "gd-taxonomies-tools"),
                'href'   => admin_url('admin.php?page=gdtaxtools_about')
            ));
            $wp_admin_bar->add_group(array(
                'parent' => 'gdcpt-toolbar-info-links',
                'id'     => 'gdcpt-toolbar-info-links-online',
                'meta'   => array('class' => 'ab-sub-secondary')
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-info-links-online',
                'id'     => 'gdcpt-toolbar-d4p-home',
                'title'  => __("Homepage", "gd-taxonomies-tools"),
                'href'   => 'http://www.dev4press.com/plugins/gd-taxonomies-tools/',
                'meta'   => array('target' => '_blank')
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-info-links-online',
                'id'     => 'gdcpt-toolbar-d4p-forum',
                'title'  => __("Support", "gd-taxonomies-tools"),
                'href'   => 'http://www.dev4press.com/plugins/gd-taxonomies-tools/support/',
                'meta'   => array('target' => '_blank')
            ));
            $wp_admin_bar->add_menu(array(
                'parent' => 'gdcpt-toolbar-info-links-online',
                'id'     => 'gdcpt-toolbar-d4p-website',
                'title'  => __("Official Website", "gd-taxonomies-tools"),
                'href'   => 'http://www.gdcpttools.com/',
                'meta'   => array('target' => '_blank')
            ));
        }
    }
}

global $gdcpt_toolbarmenu;
$gdcpt_toolbarmenu = new gdCPTToolbarMenu();

?>