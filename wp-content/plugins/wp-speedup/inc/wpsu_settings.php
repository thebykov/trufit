<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
	if ( !current_user_can( 'install_plugins' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-su' ) );
	}
	$saved = false;
	
	if(!empty($_POST) && isset($_POST['wpsu_options'])){
		update_option('wpsu_options', $_POST['wpsu_options']);
		$saved = true;
	}
	global $wpsu_css, $su_data, $su_pro, $su_premium_link;
	$wpsu_options_db = get_option('wpsu_options', array());
	//pree($wpsu_options_db);
	$wpsu_options = array('disable_hotlinking'=>'Don\'t use it unless you really need it.', 'expires_header'=>'', 'cache_control'=>'', 'deflate_compression'=>'Ask your hosting company if mod_deflate installed or not?', 'gzip_compression'=>'', 'ob_gzhandler'=>'', 'unset_etag'=>'Developers Only!');
?>	
<div class="wrap wpsu">
	
  <div class="head_area">
	<h2><?php _e( '<span class="dashicons dashicons-welcome-widgets-menus"></span>'.$su_name, 'wp-su' ); ?> - Settings</h2>
  </div>
  
  
  <a data-mode="booster" class="wpsu_modes button-secondary button-large">DB Booster</a>
  <a data-mode="advanced" class="wpsu_modes button-secondary button-large">Caching Mode</a>  
  <a data-mode="classic" class="wpsu_modes button-secondary button-large">Image Optimization</a>
  <br />
<br />

  <img height="200px;" src="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>/images/banner-1544x500.jpg" />
  
  <div class="wpsu_booster_area hide">
  <?php 
  	global $wpdb;
  	$InnoDB = $wpdb->get_results("SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE engine = 'InnoDB' AND TABLE_SCHEMA = '".DB_NAME."'");
	$MyISAM = $wpdb->get_results("SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE engine != 'InnoDB' AND TABLE_SCHEMA = '".DB_NAME."'");
	
  ?>
	<h2>
	<?php if(!empty($InnoDB)){ ?>
  	There are <?php echo count($InnoDB); ?> tables with InnoDB engine type.<br />
    <?php } ?>
    <?php if(!empty($InnoDB) && !empty($MyISAM)){ ?>
    &
    <br />
    <?php  } ?>
    <?php if(!empty($MyISAM)){ ?>
    There are <?php echo count($MyISAM); ?> tables with MyISAM engine type.<br />
    <?php } ?>
    </h2>
    <br />


     <form action="" method="post">

		
    
	<div class="wpsu_booster_wrapper">
    
    	<table>
        	<tr>
            	<td><h2>What is InnoDB?</h2>
        <p>
        	InnoDB is a storage engine for MySQL. MySQL 5.5 and later use it by default. InnoDB provides the standard ACID-compliant transaction features, along with foreign key support (Declarative Referential Integrity)
        </p> <ul>
        	<li>
            	<div>
                	<h5>InnoDB Advantages</h5>
                	<ul>
                    	<li>ACID transactions</li>
                    	<li>row-level locking</li>
                        <li>foreign key constraints</li>
                        <li>automatic crash recovery</li>
                        <li>table compression (read/write)</li>
                        <li>spatial data types (no spatial indexes)</li>
                    </ul>
                </div>
            </li>
            </ul><br />
<br />
<input type="submit" value="Convert all MyISAM to InnoDB" class="button-secondary button-large" name="itom_conversion_innodb" /></td>
                <td><h2>What is MyISAM?</h2>
        <p>
        	MyISAM is the default storage engine for the MySQL relational database management system versions prior to 5.5 1. It is based on the older ISAM code but has many useful extensions. The major deficiency of MyISAM is the absence of transactions support.
        </p>
        <ul>
        <li>
            	<div>
                	<h5>MyISAM Advantages</h5>
                	<ul>
                    	<li>fast COUNT(*)s (when WHERE, GROUP BY, or JOIN is not used)</li>
                    	<li>full text indexing (update: supported in InnoDB from MySQL 5.6)</li>
                        <li>smaller disk footprint</li>
                        <li>very high table compression (read only)</li>
                        <li>spatial data types and indexes (R-tree) (update: supported in InnoDB from MySQL 5.7)</li>
                    </ul>
                </div>
            </li>
        </ul><br />
<br />
<input type="submit" value="Convert all InnoDB to MyISAM" class="button-secondary button-large" name="itom_conversion_myisam" />
        </td>
            </tr>
        </table>
    	
        
       
        
        	
    </div>
    
     </form>
    
        
  </div>
  
  <div class="wpsu_todo_area hide">
  
  <p>Please test your website speed on these platforms before optimization.</p>
  <a href="https://tools.pingdom.com/" target="_blank" class="button-secondary button-large">Test on pingdom.com</a>
  &nbsp;
  <a href="https://developers.google.com/speed/pagespeed/insights/?url=<?php echo home_url(); ?>" target="_blank" class="button-secondary button-large">Test on google.com pagespeed</a>
  
  <h4>Speed Up Todo list:</h4>
  <form action="" method="post">
  <input type="hidden" name="wpsu_options[]" />
  <ol>
  	<li><strong>Turn off pingbacks and trackbacks:</strong> <a href="options-discussion.php" target="_blank"><small>Uncheck</small> "Allow link notifications from other blogs (pingbacks and trackbacks) on new articles"</a></li>
    <li><a href="upload.php" target="_blank"><strong>Images:</strong></a>
    <ul>
    <li>JPEG - use for photos</li>
    <li>PNG - use for graphics (or not detailed images)</li>
   	<li>GIF - use for simple small graphics or images</li>
    <li>BMP/TIFF - do not use them</li>
    </ul>
    </li>
    <li><strong>Optimization:</strong>
    <ul>
    <?php foreach($wpsu_options as $options=>$tooltip){ ?>
    	<li><input <?php checked(array_key_exists($options, $wpsu_options_db) && $wpsu_options_db[$options]); ?> id="<?php echo $options; ?>" type="checkbox" value="1" name="wpsu_options[<?php echo $options; ?>]" /><label for="<?php echo $options; ?>"><?php echo ucwords(str_replace('_', ' ', $options)); ?></label> <?php echo $tooltip!=''?' - <strong style="color:#8ac007">('.$tooltip.')</strong>':''; ?></li>
	<?php } ?>        
    </ul>
    </li>
	
    <li><strong>Permalink Settings:</strong> <a style="<?php echo ($saved?'color:red':''); ?>" href="options-permalink.php" target="_blank">Update permalinks every time you made changes here</a></li>
  </ol>

  <input type="submit" class="button-primary button-large" value="Save Changes" />
  <br /><br />

  <strong style="color:red">Before making any changes, its recommended that you connect FTP and backup your .htaccess file on root.</strong>

  </form>
  
  <br />
<br />
<br />
<div class="wpsu_blog_posts">
<a href="https://profiles.wordpress.org/fahadmahmood/#content-plugins" target="_blank"><img height="160" src="<?php echo plugin_dir_url( dirname(__FILE__) ); ?>/images/mechanic_with_board.png" align="right" /></a>
	
  <strong>A few blog posts related to .htaccess handling:</strong>
  <ul>
  	<li><a href="http://www.websitedesignwebsitedevelopment.com/category/website-development/htaccess/" target="_blank">.htaccess at a Glance</a></li>
    <li><a href="http://www.websitedesignwebsitedevelopment.com/website-development/htaccess/caching-with-htaccess/" target="_blank">Caching with .htaccess</a></li>
    <li><a href="http://www.websitedesignwebsitedevelopment.com/website-development/htaccess/codeigniter-htaccess-issue-on-php-cgi-webhero-hosting/" target="_blank">htaccess issue on php cgi</a></li>
    <li><a href="http://www.websitedesignwebsitedevelopment.com/website-development/htaccess/error-reporting-on-in-htaccess/" target="_blank">Error Reporting</a></li>
    <li><a href="https://www.google.com/search?q=websitedesignwebsitedevelopment.com+htaccess&ie=utf-8&oe=utf-8" target="_blank">More Articles on .htaccess usage</a></li>
    
  </ul>
  
</div>  
  </div>

<div class="selection_div main hide">
	<div class="selection_css hide" title="Click here for settings">CSS</div>
	<div class="selection_js hide" title="Click here for settings">JS</div>
        <div class="images_compression_report">
            <?php wpsu_load_img_module(); ?>
        </div>
</div>

<?php
				

		$file = 'wpsu_css_settings.php';
		if(is_object($wpsu_css) && file_exists($wpsu_css->get_plugin_path())){
			include($file);
		}
		$file = 'wpsu_js_settings.php';
		if(is_object($wpsu_css) && file_exists($wpsu_css->get_plugin_path())){
			include($file);
		}
		if(isset($_GET['type']) && $_GET['type']=='img'){
			$file = 'wpsu_img_settings.php';
			if(file_exists($wpsu_css->get_plugin_path())){
				include($file);
			}
		}

?>


<style type="text/css">
#message, #wpfooter{ display:none; }
</style>
</div>