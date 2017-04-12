<?php $post_types = get_post_types(array('_builtin' => false, 'public' => true, 'has_archive' => true), 'objects'); ?>

<div class="posttypediv" id="gdtt-cpt-archives-box">
    <div id="gdtt-cpt-archives" class="tabs-panel tabs-panel-active">
        <ul id="gdtt-cpt-archives-list">
            <?php foreach ($post_types as $pt) { ?>
                <li><label class="menu-item-title"><input type="checkbox" value="<?php echo esc_attr($pt->name); ?>" /> <?php echo esc_attr($pt->labels->name); ?></label></li>
            <?php } ?>
        </ul>
    </div>

    <p class="button-controls">
        <span class="list-controls">
            <a class="select-all" href="#gdtt-cpt-archives-box">Select All</a>
        </span>
        <span class="add-to-menu">
            <img alt="" src="<?php echo admin_url('images/wpspin_light.gif'); ?>" class="waiting" style="display: none;" />
            <input type="submit" id="gdtt-cpt-archives-box-submit" name="add-gdtt-cpt-archive-menu-item" value="Add to Menu" class="button-secondary submit-add-to-menu">
        </span>
    </p>
</div>