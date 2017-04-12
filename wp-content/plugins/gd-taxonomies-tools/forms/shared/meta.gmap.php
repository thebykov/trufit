<div id="<?php echo $id; ?>" class="gdtt-field-gmap"></div>

<div class="gdtt-map-table">
    <?php _e("Search", "gd-taxonomies-tools") ?>:<br/>
    <input type="text" class="gdtt-field-text gdtt-map-search-field" id="<?php echo $id; ?>_search" name="<?php echo $name; ?>[search]" value="" />
    <input type="button" class="gdtt-field-button gdtt-map-search-button" value="Look" id="<?php echo $id; ?>_look" />
</div>

<div class="gdtt-map-table">
    <span class="gdtt-field-title-half"><?php _e("Latitude", "gd-taxonomies-tools"); ?>:</span>
    <input class="gdtt-field-text-half gdtt-field-number" type="text" id="<?php echo $id; ?>_latitude" name="<?php echo $name; ?>[latitude]" value="<?php echo $value->latitude; ?>" />
    <span class="gdtt-field-spacer"></span>
    <span class="gdtt-field-title-half"><?php _e("Longitude", "gd-taxonomies-tools"); ?>:</span>
    <input style="float: right" class="gdtt-field-text-half gdtt-field-number" type="text" id="<?php echo $id; ?>_longitude" name="<?php echo $name; ?>[longitude]" value="<?php echo $value->longitude; ?>" />
</div>

<div class="gdtt-map-table">
    <span class="gdtt-field-title-half"><?php _e("Width", "gd-taxonomies-tools"); ?>:</span>
    <input class="gdtt-field-text-half gdtt-field-int" type="text" id="<?php echo $id; ?>_width" name="<?php echo $name; ?>[width]" value="<?php echo $value->width; ?>" />
    <span class="gdtt-field-spacer"></span>
    <span class="gdtt-field-title-half"><?php _e("Height", "gd-taxonomies-tools"); ?>:</span>
    <input style="float: right" class="gdtt-field-text-half gdtt-field-int" type="text" id="<?php echo $id; ?>_height" name="<?php echo $name; ?>[height]" value="<?php echo $value->height; ?>" />
</div>

<div class="gdtt-map-table">
    <span class="gdtt-field-title-half"><?php _e("Type", "gd-taxonomies-tools"); ?>:</span>
    <select class="gdtt-field-select-half" id="<?php echo $id; ?>_maptype" name="<?php echo $name; ?>[maptype]">
        <option value="TERRAIN"<?php echo $value->maptype == "TERRAIN" ? ' selected="selected"' : ''; ?>><?php _e("Terrain", "gd-taxonomies-tools") ?></option>
        <option value="ROADMAP"<?php echo $value->maptype == "ROADMAP" ? ' selected="selected"' : ''; ?>><?php _e("Roadmap", "gd-taxonomies-tools") ?></option>
        <option value="SATELLITE"<?php echo $value->maptype == "SATELLITE" ? ' selected="selected"' : ''; ?>><?php _e("Satellite", "gd-taxonomies-tools") ?></option>
        <option value="HYBRID"<?php echo $value->maptype == "HYBRID" ? ' selected="selected"' : ''; ?>><?php _e("Hybrid", "gd-taxonomies-tools") ?></option>
    </select>
    <span class="gdtt-field-spacer"></span>
    <span class="gdtt-field-title-half"><?php _e("Zoom", "gd-taxonomies-tools"); ?>:</span>
    <select style="float: right" class="gdtt-field-select-half" id="<?php echo $id; ?>_zoom" name="<?php echo $name; ?>[zoom]">
        <?php for ($i = 1; $i < 23; $i++) { $selected = $value->zoom == $i ? ' selected="selected"' : '';
            echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
        } ?>
    </select>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        <?php echo $value->get_map_admin($id); ?>
    });
</script>