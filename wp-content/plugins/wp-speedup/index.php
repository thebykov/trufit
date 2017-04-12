<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: WP SpeedUp

Plugin URI: http://www.websitedesignwebsitedevelopment.com/wordpress/plugins/wp-speedup

Description: WP SpeedUp is a great plugin to speedup your WordPress website with a simple installation.

Version: 1.4.0
Author: Fahad Mahmood 
Author URI: http://www.androidbubbles.com
License: GPL3
*/ 

	


	define( 'WPSU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
	
	
	
	
	global $su_premium_link, $wpsu_dir, $su_pro, $su_data, $wpsu_css, $dir_size,  $wpsu_compress_images, $su_name, $wpsu_total_bytes, $wpsu_live;
	$wpsu_live = ($_SERVER['REMOTE_ADDR']!='127.0.0.1');
	
	$wpsu_dir = plugin_dir_path( __FILE__ );
	$rendered = FALSE;
	$su_pro = file_exists($wpsu_dir.'pro/wpsu_extended.php');
	$su_data = get_plugin_data(__FILE__);
	$su_premium_link = 'http://shop.androidbubbles.com/product/wp-speedup-pro';
	$su_name = 'WP SpeedUp'.(' ('.$su_data['Version'].($su_pro?') Pro':')'));
	$wpsu_compress_images = (get_option('wpsu_compress_images') || isset($_GET['wpsu_ct']));
	$wpsu_compress_images = ($wpsu_compress_images?true:false);
	$wpsu_total_bytes = get_option('wpsu_total_bytes');

	
	$ap_data = get_plugin_data(__FILE__);
	
	
	function wpsu_backup_pro($src='pro', $dst='') { 

		$plugin_dir = plugin_dir_path( __FILE__ );
		$uploads = wp_upload_dir();
		$dst = ($dst!=''?$dst:$uploads['basedir']);
		$src = ($src=='pro'?$plugin_dir.$src:$src);
		
		$pro_check = basename($plugin_dir);

		$pro_check = $dst.'/'.$pro_check.'.dat';

		if(file_exists($pro_check)){
			if(!is_dir($plugin_dir.'pro')){
				mkdir($plugin_dir.'pro');
			}
			$files = file_get_contents($pro_check);
			$files = explode('\n', $files);
			if(!empty($files)){
				foreach($files as $file){
					
					if($file!=''){
						
						$file_src = $uploads['basedir'].'/'.$file;
						//echo $file_src.' > '.$plugin_dir.'pro/'.$file.'<br />';
						//copy($file_src, $plugin_dir.'pro/'.$file);

						$trg = $plugin_dir.'pro/'.$file;
						if(!file_exists($trg))
						copy($file_src, $trg);
						
					}
				}//exit;
			}
		}
		
		if(is_dir($src)){
			if(!file_exists($pro_check)){
				$f = fopen($pro_check, 'w');
				fwrite($f, '');
				fclose($f);
			}	
			$dir = opendir($src); 
			@mkdir($dst); 
			while(false !== ( $file = readdir($dir)) ) { 
				if (( $file != '.' ) && ( $file != '..' )) { 
					if ( is_dir($src . '/' . $file) ) { 
						wpsu_backup_pro($src . '/' . $file, $dst . '/' . $file); 
					} 
					else { 
						$dst_file = $dst . '/' . $file;
						
						if(!file_exists($dst_file)){
							
							copy($src . '/' . $file,$dst_file); 
							$f = fopen($pro_check, 'a+');
							fwrite($f, $file.'\n');
							fclose($f);
						}
					} 
				} 
			} 
			closedir($dir); 
			
		}	
	}	
	
	include('inc/functions.php');
	
	if($su_pro){
		wpsu_backup_pro();
		include('pro/wpsu_extended.php');
	}
	
	
        
	

	add_action( 'admin_enqueue_scripts', 'register_su_scripts' );
	add_action( 'wp_enqueue_scripts', 'register_su_scripts' );
	add_action('admin_footer', 'wpsu_footer_scripts');
	add_action('admin_init', 'wpsu_actions');
	
	function wpsu_actions(){
		
		global $wpdb;
		//pree($_POST);exit;
		if(isset($_POST['itom_conversion_innodb'])){
			$MyISAM = $wpdb->get_results("SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE engine = 'MyISAM' AND TABLE_SCHEMA = '".DB_NAME."'");
			if(!empty($MyISAM)){
				foreach($MyISAM as $tbl){
					//pree($table_name);exit;
					$sql = "ALTER TABLE ".DB_NAME.".$tbl->table_name ENGINE = InnoDB";
					//echo $sql.'<br />';
					$wpdb->query($sql);
				}
			}
		}
		
		if(isset($_POST['itom_conversion_myisam'])){
			$InnoDB = $wpdb->get_results("SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE engine = 'InnoDB' AND TABLE_SCHEMA = '".DB_NAME."'");
			if(!empty($InnoDB)){
				foreach($InnoDB as $tbl){
					//pree($table_name);exit;
					$sql = "ALTER TABLE ".DB_NAME.".$tbl->table_name ENGINE = MyISAM";
					//echo $sql.'<br />';
					$wpdb->query($sql);
				}
			}			
		}		
	}
	
	if(is_admin()){
		
		
		
		add_action( 'admin_menu', 'wpsu_menu' );		
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'wpsu_plugin_links' );	
		
		if((isset($_GET['page']) && $_GET['page']=='wp_su') || $wpsu_compress_images){
			add_action('admin_init', 'wpsu_compression_check');
		}
		
	}elseif(!defined( 'XMLRPC_REQUEST' ) && !defined( 'DOING_CRON' )){
		
	
		if(get_option('selection_js')){
			
			add_filter( 'print_scripts_array', 'wpsu_save_do_not_defer_deps' );
			add_filter( 'script_loader_src', 'wpsu_save_dscripts', PHP_INT_MAX, 2 );
			add_action( 'wp_footer', 'wpsu_render_scripts', PHP_INT_MAX );
		}
		
		
			add_action( 'wp_footer', 'wp_speedup' );									
		
	}	