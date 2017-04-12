<?php

global $gdtt_bbpress_load;

$bbp_locations_topic = array_merge(array(
    '__parent__' => __("Use from parent", "gd-taxonomies-tools"),
    '__default__' => __("Default from settings", "gd-taxonomies-tools"),
    'bbp_theme_before_topic_form_title' => __("Before Title", "gd-taxonomies-tools"),
    'bbp_theme_after_topic_form_title' => __("After Title", "gd-taxonomies-tools"),
    'bbp_theme_after_topic_form_content' => __("After Content", "gd-taxonomies-tools"),
    'bbp_theme_after_topic_form_tags' => __("After Tags", "gd-taxonomies-tools"),
    'bbp_theme_before_topic_form_submit_wrapper' => __("Form End", "gd-taxonomies-tools")
), $gdtt_bbpress_load->embed_locations['topic']);

$bbp_locations_reply = array_merge(array(
    '__parent__' => __("Use from parent", "gd-taxonomies-tools"),
    '__default__' => __("Default from settings", "gd-taxonomies-tools"),
    'bbp_theme_before_reply_form_content' => __("Before Content", "gd-taxonomies-tools"),
    'bbp_theme_after_reply_form_content' => __("After Content", "gd-taxonomies-tools"),
    'bbp_theme_after_reply_form_tags' => __("After Tags", "gd-taxonomies-tools"),
    'bbp_theme_before_reply_form_submit_wrapper' => __("Form End", "gd-taxonomies-tools")
), $gdtt_bbpress_load->embed_locations['reply']);

$bbp_boxes = array(
    '__parent__' => __("Use from parent", "gd-taxonomies-tools"),
    '__default__' => __("Default from settings", "gd-taxonomies-tools"),
    '__none__' => __("Do not use any", "gd-taxonomies-tools"));

foreach ($meta as $name => $box) {
    $fields_count = count($box['fields']);
    $bbp_boxes[$name] = $box['name']." (".$fields_count." "._n("field", "fields", $fields_count, "gd-taxonomies-tools").")";
}

?>
<input type="hidden" name="gdtt_bbpress_meta[nonce]" value="<?php echo wp_create_nonce("gdcptbbpress"); ?>" />
<p>
    <strong class="label"><?php _e("Metabox to expand topic form", "gd-taxonomies-tools"); ?>:</strong>
    <label class="screen-reader-text" for="gdtt_bbpress_meta_topic"><?php _e("Metabox to expand topic form", "gd-taxonomies-tools"); ?>:</label>
    <select name="gdtt_bbpress_meta[topic]" id="gdtt_bbpress_meta_topic">
        <?php
            foreach ($bbp_boxes as $key => $f_val) {
                $sel = $key == $values["topic"] ? ' selected="selected"' : '';
                echo '<option value="'.__($key).'"'.$sel.'>'.__($f_val).'</option>';
            }
        ?>
    </select><br/>
    <strong class="label"><?php _e("Add after", "gd-taxonomies-tools"); ?>:</strong>
    <label class="screen-reader-text" for="gdtt_bbpress_meta_location_topic"><?php _e("Add after", "gd-taxonomies-tools"); ?>:</label>
    <select name="gdtt_bbpress_meta[location_topic]" id="gdtt_bbpress_meta_location_topic">
        <?php
            foreach ($bbp_locations_topic as $key => $f_val) {
                $sel = $key == $values["location_topic"] ? ' selected="selected"' : '';
                echo '<option value="'.__($key).'"'.$sel.'>'.__($f_val).'</option>';
            }
        ?>
    </select>
</p>
<hr/>
<p>
    <strong class="label"><?php _e("Metabox to expand reply form", "gd-taxonomies-tools"); ?>:</strong>
    <label class="screen-reader-text" for="gdtt_bbpress_meta_reply"><?php _e("Metabox to expand reply form", "gd-taxonomies-tools"); ?>:</label>
    <select name="gdtt_bbpress_meta[reply]" id="gdtt_bbpress_meta_reply">
        <?php
            foreach ($bbp_boxes as $key => $f_val) {
                $sel = $key == $values["reply"] ? ' selected="selected"' : '';
                echo '<option value="'.__($key).'"'.$sel.'>'.__($f_val).'</option>';
            }
        ?>
    </select><br/>
    <strong class="label"><?php _e("Add after", "gd-taxonomies-tools"); ?>:</strong>
    <label class="screen-reader-text" for="gdtt_bbpress_meta_location_reply"><?php _e("Add after", "gd-taxonomies-tools"); ?>:</label>
    <select name="gdtt_bbpress_meta[location_reply]" id="gdtt_bbpress_meta_location_reply">
        <?php
            foreach ($bbp_locations_reply as $key => $f_val) {
                $sel = $key == $values["location_reply"] ? ' selected="selected"' : '';
                echo '<option value="'.__($key).'"'.$sel.'>'.__($f_val).'</option>';
            }
        ?>
    </select>
</p>
