<div class="gdtt-mb-group">

    <?php do_action('gdcpt_metabox_group_'.$group['code'].'_header'); ?>
    
    <ul class="wp-tab-bar">
        <?php

        $first = true;
        foreach ($load_boxes as $_box => $meta) {
            echo '<li'.($first ? ' class="wp-tab-active"' : '').'><a href="#gdtt-tab-'.$_box.'">'.__($meta['name']).'</a></li>';
            $first = false;
        }

        ?>
        
    </ul>
    <?php

        $first = true;
        foreach ($load_boxes as $_box => $meta) {
            echo '<div id="gdtt-tab-'.$_box.'" class="wp-tab-panel"'.($first ? '' : ' style="display: none;"').'>';

            $values = $gdtt->meta_box_current_values($post->ID, $meta['code']);

            $_ID = 'gdtt_box_'.$meta['code'].'_';
            $_NAME = 'gdtt_box['.$meta['code'].'][';
            $_F = $gdtt->m['fields'];

            include(GDTAXTOOLS_PATH.'forms/metaboxes/custom_meta.php');

            echo '</div>';
            $first = false;
        }

    ?>

    <?php do_action('gdcpt_metabox_group_'.$group['code'].'_footer'); ?>
    
</div>