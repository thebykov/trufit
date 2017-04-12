<style type="text/css">
	.selection_div.main, 
	.wpsu_modes{
		display:none !important;
	}
</style>
  <div class="head_area">
  <?php if(!$su_pro): ?><a href="<?php echo $su_premium_link; ?>" target="_blank">Go Premium</a><?php endif; ?>
  
	
    <div class="wpsu_actions">
    <?php if(!isset($_GET['wpsu_ct'])): ?>
    <a class="wpsu_ct button button-primary" title="It will create a temp directory and copy compressed versions there instead of replacing original images.">Compress All Images <span></span>(It's Safe)</a>&nbsp;&nbsp;<a title="Warning! Make sure that you didn't switch the original directories as temp directories because this action will remove temp directories permanantly." class="wpsu_ci button button-secondary">Delete All Temp Directories</a>
    <?php else: ?>
    <a class="wpsu_ctr button button-primary" title="A temp directory is created and compressed versions are copied there instead of replacing original images.">All files are successfully compressed. Click here to refresh.</a>
    <?php endif; ?>
	</div>
	</div>    

	


    <div class="images_compression_report">
    
	<?php wpsu_load_img_module(); ?>
    
	</div>
	<script type="text/javascript" language="javascript">
	jQuery(document).ready(function($){
		 setTimeout(function(){
			 if($('.wpsu_temp_text').length>0){
				$('a.wpsu_ct span').html('Again ');
			 }
		 }, 500);
		 
		 
	});
	</script>
