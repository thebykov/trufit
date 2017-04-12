</div>
<?php do_action('gdcpt_panel_footer_'.$_panel_name); ?>
<div class="gdr2-dialog-blocks">
    <div id="gdr2dialog_custom" title="Title">
        <div class="gdr2-dialog-content">
            <p>Content.</p>
        </div>
    </div>
    <div id="gdr2dialog_confirm" title="<?php _e("Confirmation", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-dialog-content">
            <?php _e("Are you sure? Operation is not reversible.", "gd-taxonomies-tools"); ?>
        </div>
    </div>
    <div id="gdr2dialogsave" title="<?php _e("Saving Settings...", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-please-wait">
            <?php _e("Working. Please wait...", "gd-taxonomies-tools"); ?>
        </div>
    </div>
    <div id="gdr2dialog_error" title="<?php _e("Error", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-dialog-content">
            <p><?php _e("Please fix the errors and try again. Fields with errors are displayed in red.", "gd-taxonomies-tools"); ?></p>
        </div>
    </div>
    <div id="gdr2dialog_tax_simple" title="<?php _e("Taxonomy Simple Edit", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-dialog-content">
            <p><?php _e("Data is saved. What do you want to do now?", "gd-taxonomies-tools"); ?></p>
            <a class="pressbutton edit-again" href="admin.php?page=gdtaxtools_taxs&cpt=0&action=simple&tname="><?php _e("Edit Again", "gd-taxonomies-tools"); ?></a>
            <a class="pressbutton" href="admin.php?page=gdtaxtools_taxs"><?php _e("Taxonomies List", "gd-taxonomies-tools"); ?></a>
        </div>
    </div>
    <div id="gdr2dialog_tax_full" title="<?php _e("Taxonomy Edit", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-dialog-content">
            <p><?php _e("Data is saved. What do you want to do now?", "gd-taxonomies-tools"); ?></p>
            <a class="pressbutton edit-again" href="admin.php?page=gdtaxtools_taxs&action=edit&cpt="><?php _e("Edit Again", "gd-taxonomies-tools"); ?></a>
            <a class="pressbutton add-new" href="admin.php?page=gdtaxtools_taxs&action=addnew"><?php _e("New Taxonomy", "gd-taxonomies-tools"); ?></a>
            <a class="pressbutton" href="admin.php?page=gdtaxtools_taxs"><?php _e("Taxonomies List", "gd-taxonomies-tools"); ?></a>
        </div>
    </div>
    <div id="gdr2dialog_cpt_simple" title="<?php _e("Post Type Simple Edit", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-dialog-content">
            <p><?php _e("Data is saved. What do you want to do now?", "gd-taxonomies-tools"); ?></p>
            <a class="pressbutton edit-again" href="admin.php?page=gdtaxtools_postypes&cpt=0&action=simple&pname="><?php _e("Edit Again", "gd-taxonomies-tools"); ?></a>
            <a class="pressbutton" href="admin.php?page=gdtaxtools_postypes"><?php _e("Post Types List", "gd-taxonomies-tools"); ?></a>
        </div>
    </div>
    <div id="gdr2dialog_cpt_full" title="<?php _e("Post Type Edit", "gd-taxonomies-tools"); ?>">
        <div class="gdr2-dialog-content">
            <p><?php _e("Data is saved. What do you want to do now?", "gd-taxonomies-tools"); ?></p>
            <a class="pressbutton edit-again" href="admin.php?page=gdtaxtools_postypes&action=edit&cpt="><?php _e("Edit Again", "gd-taxonomies-tools"); ?></a>
            <a class="pressbutton add-new" href="admin.php?page=gdtaxtools_postypes&action=addnew"><?php _e("New Post Type", "gd-taxonomies-tools"); ?></a>
            <a class="pressbutton" href="admin.php?page=gdtaxtools_postypes"><?php _e("Post Types List", "gd-taxonomies-tools"); ?></a>
        </div>
    </div>
    <?php do_action('gdcpt_panel_dialogs_'.$_panel_name); ?>
</div>