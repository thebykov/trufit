<?php global $gdtt; $status = $gdtt->o["status"] != 'stable' ? ucfirst($gdtt->o["status"]).' #'.$gdtt->o["revision"] : ucfirst($gdtt->o["status"]); ?>
<div class="wrap gdsr">
    <div class="heading">
        <div class="onerule">
            <?php _e("Enhancing WordPress content management...", "gd-taxonomies-tools"); ?>
        </div>
        <img alt="GD Custom Posts And Taxonomies Tools" src="<?php echo GDTAXTOOLS_URL; ?>gfx/logo_full.png" />
        <div class="info">
            <?php

            _e("Release Date: ", "gd-taxonomies-tools");
            echo '<strong>'.$gdtt->o["date"]."</strong> | ";
            _e("Version: ", "gd-taxonomies-tools");
            echo '<strong>'.$gdtt->o["version"]."</strong> | ";
            _e("Status: ", "gd-taxonomies-tools");
            echo '<strong>'.$status."</strong> | ";
            _e("Build: ", "gd-taxonomies-tools");
            echo '<strong>'.$gdtt->o["build"]."</strong>";

            ?>
        </div>
    </div>
