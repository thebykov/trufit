<?php if (isset($_GET['message']) && $_GET['message'] == "saved") { ?>
<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e("Settings saved.", "gd-taxonomies-tools"); ?></strong></p></div>
<?php } ?>

<form method="post" action="">
<div id="tabs" class="gdtttabs gdtt-middle-tabs" style="width: 100%;">
    <ul>
        <li><a href="#integration"><?php _e("Integration", "gd-taxonomies-tools"); ?></a><div><?php _e("into WordPress panels", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#enhanced"><?php _e("Enhanced", "gd-taxonomies-tools"); ?></a><div><?php _e("plugin added features", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#metabox"><?php _e("Meta Boxes", "gd-taxonomies-tools"); ?></a><div><?php _e("integration and control", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#tagger"><?php _e("Tagger", "gd-taxonomies-tools"); ?></a><div><?php _e("API's access and settings", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#accessibility"><?php _e("Accessibility", "gd-taxonomies-tools"); ?></a><div><?php _e("and visual enhancements", "gd-taxonomies-tools"); ?></div></li>
    </ul>
    <div style="clear: both"></div>
    <div id="integration">
        <?php include GDTAXTOOLS_PATH."forms/settings/integration.php"; ?>
    </div>
    <div id="enhanced">
        <?php include GDTAXTOOLS_PATH."forms/settings/enhanced.php"; ?>
    </div>
    <div id="metabox">
        <?php include GDTAXTOOLS_PATH."forms/settings/metabox.php"; ?>
    </div>
    <div id="tagger">
        <?php include GDTAXTOOLS_PATH."forms/settings/tagger.php"; ?>
    </div>
    <div id="accessibility">
        <?php include GDTAXTOOLS_PATH."forms/settings/accessibility.php"; ?>
    </div>
</div>

<input type="submit" class="pressbutton" value="<?php _e("Save Settings", "gd-taxonomies-tools"); ?>" name="gdtt_saving" style="margin-top: 10px;" />
</form>
