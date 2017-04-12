<?php require_once(GDTAXTOOLS_PATH."gdr2/gdr2.ui.php"); ?>
<script type="text/javascript">
    var cpt_rules = <?php echo json_encode($rcaps); ?>;
    jQuery(document).ready(function() { gdCPTAdmin.caps.init(); });
</script>
<div class="gdcpt-settings">
    <div id="tabs">
        <ul>
            <li style="width: 240px;"><a href="#tabs-cpt"><?php _e("Post Types", "gd-taxonomies-tools"); ?></a><div><?php _e("roles with custom capabilities", "gd-taxonomies-tools"); ?></div></li>
            <li style="width: 240px;"><a href="#tabs-tax"><?php _e("Taxonomies", "gd-taxonomies-tools"); ?></a><div><?php _e("roles with custom capabilities", "gd-taxonomies-tools"); ?></div></li>
        </ul>
        <div id="tabs-cpt">
            <?php include GDTAXTOOLS_PATH."forms/caps/cpt.php"; ?>
        </div>
        <div id="tabs-tax">
            <?php include GDTAXTOOLS_PATH."forms/caps/tax.php"; ?>
        </div>
    </div>
</div>