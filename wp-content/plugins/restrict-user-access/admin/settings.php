<?php
/**
 * @package Restrict User Access
 * @author Joachim Jensen <jv@intox.dk>
 * @license GPLv3
 * @copyright 2017 by Joachim Jensen
 */

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit;
}

final class RUA_Settings_Page {

	/**
	 * Settings slug
	 * @var string
	 */
	private $slug = "rua-settings";

	/**
	 * Settings option group
	 * @var string
	 */
	private $option_group = 'rua-group-main';

	/**
	 * Settings prefix
	 * @var string
	 */
	private $prefix = "rua-";

	/**
	 * Settings
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action("admin_init",
			array($this, "init_settings"),99);
		add_action("admin_menu",
			array($this, "add_settings_menu"),99);
	}

	public function init_settings() {
		$this->settings = array(
			"general" => array(
				"name"     => "general",
				"title"    => __("General",RUA_App::DOMAIN),
				"callback" => "",
				"fields"   => array()
			)
		);

		$this->settings["general"]["fields"][] = array(
			"name"     => "toolbar-hide",
			"title"    => __("Hide Admin Toolbar for Users",RUA_App::DOMAIN),
			"callback" => array($this,"checkbox"),
			"args"     => array("label_for"=>$this->prefix."toolbar-hide")
		);

		$this->settings["general"]["fields"][] = array(
			"name"     => "registration-level",
			"title"    => __("New User Default Level",RUA_App::DOMAIN),
			"callback" => "wp_dropdown_pages",
			"args"     => array(
				"label_for"         => $this->prefix."registration-level",
				'selected'          => get_option($this->prefix."registration-level"),
				'name'              => $this->prefix."registration-level",
				'id'                => $this->prefix."registration-level",
				'show_option_none'  => __("-- None --"),
				'option_none_value' => 0,
				'post_type'         => RUA_App::TYPE_RESTRICT,
				'meta_key'          => RUA_App::META_PREFIX.'role',
				'meta_value'        => '-1',
			)
		);

		$default_role = get_option("default_role");
		$roles = get_editable_roles();
		$this->settings["general"]["fields"][] = array(
			"name"     => "registration-role",
			"title"    => __("New User Default Role"),
			"callback" => array($this,"setting_moved"),
			"args"     => array(
				"option" => $roles[$default_role]["name"],
				"title"  => __("General Settings"),
				"url"    =>"options-general.php"
			),
			"register" => false
		);

		$this->settings["general"]["fields"][] = array(
			"name"     => "registration",
			"title"    => __("Enable Registration",RUA_App::DOMAIN),
			"callback" => array($this,"setting_moved"),
			"args"     => array(
				"option" => get_option("users_can_register") ? __("Yes") : __("No"),
				"title"  => __("General Settings"),
				"url"    =>"options-general.php"
			),
			"register" => false
		);

		foreach($this->settings as $section) {
			add_settings_section(
				$this->prefix.$section["name"],
				$section["title"],
				$section["callback"],
				$this->slug
			);
			foreach($section["fields"] as $field) {
				add_settings_field(
					$this->prefix.$field["name"],
					$field["title"],
					$field["callback"],
					$this->slug,
					$this->prefix.$section["name"],
					$field["args"]
				);
				if(!isset($field["register"]) || $field["register"]) {
					register_setting($this->option_group, $this->prefix.$field["name"]);
				}
				
			}
		}
	}
	
	/**
	 * Add Settings submenu and page
	 *
	 * @since  0.10
	 * @return void
	 */
	public function add_settings_menu() {
		add_submenu_page(
			'rua',
			__('User Access Settings',RUA_App::DOMAIN),
			__('Settings'),
			RUA_App::CAPABILITY,
			$this->slug,
			array($this, 'settings_page')
		);
	}

	/**
	 * Render checkbox
	 *
	 * @since  0.10
	 * @param  array  $args
	 * @return void
	 */
	public function checkbox($args) {
		$option = get_option($args["label_for"]);
		echo '<input type="checkbox" name="'.$args["label_for"].'" value="1" '.checked($option,1,0).'/>';
	}

	/**
	 * Render moved setting
	 *
	 * @since  0.10
	 * @param  array  $args
	 * @return void
	 */
	public function setting_moved($args) {
		echo $args["option"];
		echo '<p class="description">'.sprintf(__("Setting can be changed in %s",RUA_App::DOMAIN),
			'<a href="'.admin_url($args["url"]).'">'.$args["title"].'</a>').'</p>';
	}
	
	/**
	 * Render settings page
	 *
	 * @since  0.10
	 * @return void
	 */
	public function settings_page() { ?>

		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<?php
			settings_errors();
?>
			<form method="post" action="options.php">
<?php
			settings_fields( $this->option_group );
			do_settings_sections($this->slug);
			submit_button();
?>
			</form>
		</div>
<?php

	}

}

//