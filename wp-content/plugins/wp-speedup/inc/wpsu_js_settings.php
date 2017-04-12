<h3 class="wpsu_toggle js">Back</h3>
<div class="selection_div settings sub js">
<strong style="color:red">Not recommended unless you understand the javascript minification.</strong>
<?php 

	
	if ( isset( $_POST['selection_js'] )) {

			update_option( 'selection_js', (boolean)$_POST['selection_js']);
	}
	$selection_js = get_option('selection_js');	
?>	
<?php if ( isset( $_POST['selection_js'] )): ?>    
<div class="updated settings-error" id="setting-error-settings_updated"><p><strong><?php echo __( 'Settings saved.', 'wpsu' ); ?></strong></p></div>
<?php endif; ?>
    
    
<form method="post">



	<div title="Click here for enable/disable" class="selection_js <?php echo ($selection_js==true)?'':'disabled'; ?>">SpeedUp JS</div>
    <input type="hidden" name="selection_js" value="<?php echo $selection_js; ?>" />

<div style="clear:both; margin:20%"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'wp-su' ); ?>" /></div>
</form>
</div>