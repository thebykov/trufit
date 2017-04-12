<?php

global $post;
$p_types = gdtt_get_public_post_types(true);
$post_types = array();
foreach ($p_types as $p) {
    $post_types[$p->name] = isset($p->labels) && isset($p->labels->singular_name) ? $p->labels->singular_name : $p->label;
}
gdr2_UI::draw_select($post_types, $post->post_type, "cpt_post_type", "", "", "width: 100%;");

?><input type="hidden" name="cpt_postype_noonce" id="cpt_postype_noonce" value="<?php echo wp_create_nonce("gdcpttools"); ?>" />
<p><?php _e("Be careful with changing the post type!", "gd-taxonomies-tools"); ?></p>