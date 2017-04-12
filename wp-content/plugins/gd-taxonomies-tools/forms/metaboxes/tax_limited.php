<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
    <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
        <?php
            $name = ($taxonomy == 'category') ? 'post_category' : 'tax_input['.$taxonomy.']';
            echo "<input type='hidden' name='{$name}[]' value='0' />";
        ?>
        <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> categorychecklist form-no-clear">
            <?php

                $walker = new gdttWalker_CategoryChecklist();
                $walker->selection = $selection;
                $walker->hierarchy = is_taxonomy_hierarchical($taxonomy);
                wp_terms_checklist($post->ID, array('taxonomy' => $taxonomy, 'walker' => $walker));

            ?>
        </ul>
        <?php if (!is_taxonomy_hierarchical($taxonomy)) { ?>
            <input name="tax_input[<?php echo $taxonomy ?>]" id="gdtt_tax_input_<?php echo $taxonomy ?>" type="hidden" value="<?php echo get_terms_to_edit($post->ID, $taxonomy); ?>" />
        <?php } ?>
    </div>
</div>
