<table class="widefat"<?php if ($cpt_made) { ?> id="gdcpt-post-types"<?php } ?>>
    <thead>
        <tr>
            <?php if ($cpt_made) { ?><th scope="col" style="width: 16px;">&nbsp;</th><?php } ?>
            <th scope="col" style="white-space: nowrap;"><?php _e("Name", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 15%; text-align: left;"><?php _e("Rewrite & Query", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 15%; text-align: left;"><?php _e("Taxonomies", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 15%; text-align: left;"><?php _e("Meta", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 15%; text-align: left;"><?php _e("Settings", "gd-taxonomies-tools"); ?></th>
            <th scope="col" style="width: 5%; text-align: right;"><?php _e("Items", "gd-taxonomies-tools"); ?></th>
        </tr>
    </thead>
    <tbody>

<?php $tr_class = ""; ?>
