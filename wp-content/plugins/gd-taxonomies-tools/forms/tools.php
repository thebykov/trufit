<?php require_once(GDTAXTOOLS_PATH."gdr2/gdr2.ui.php"); ?>

<?php if (isset($_GET["message"]) && $_GET["message"] == "imported") { $terms = $_GET["terms"]; ?>
<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php echo sprintf(__("Import terms completed. Total of %s %s imported", "gd-taxonomies-tools"), $terms, _n("term", "terms", $terms, "gd-taxonomies-tools")); ?></strong></p></div>
<?php } if (isset($_GET["message"]) && $_GET["message"] == "transfered") { ?>
<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e("Import of settings and definitions file completed.", "gd-taxonomies-tools"); ?></strong></p></div>
<?php } if (isset($_GET["message"]) && $_GET["message"] == "failed") { ?>
<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><p><strong><?php _e("Import failed. File is not in expected format.", "gd-taxonomies-tools"); ?></strong></p></div>
<?php } ?>

<div id="tabs" class="gdtttabs">
    <ul>
        <li><a href="#terms"><?php _e("Terms", "gd-taxonomies-tools"); ?></a><div><?php _e("import and export", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#settings"><?php _e("Settings", "gd-taxonomies-tools"); ?></a><div><?php _e("import and export", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#reset"><?php _e("Reset", "gd-taxonomies-tools"); ?></a><div><?php _e("settings and rules", "gd-taxonomies-tools"); ?></div></li>
        <li><a href="#modules"><?php _e("Modules", "gd-taxonomies-tools"); ?></a><div><?php _e("specific tools", "gd-taxonomies-tools"); ?></div></li>
    </ul>
    <div style="clear: both"></div>
    <div id="terms">
        <?php include GDTAXTOOLS_PATH."forms/tools/terms.php"; ?>
    </div>
    <div id="settings">
        <?php include GDTAXTOOLS_PATH."forms/tools/settings.php"; ?>
    </div>
    <div id="reset">
        <?php include GDTAXTOOLS_PATH."forms/tools/reset.php"; ?>
    </div>
    <div id="modules">
        <?php include GDTAXTOOLS_PATH."forms/tools/modules.php"; ?>
    </div>
</div>
