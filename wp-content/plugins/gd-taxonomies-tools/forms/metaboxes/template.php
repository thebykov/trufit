<?php

global $post;

$templates = gdtt_custom_post_templates();
$template = get_post_meta($post->ID, '_wp_post_template', true);

gdr2_UI::draw_select($templates, $template, 'cpt_post_templates', '', '', 'width: 100%;');

?>
<input type="hidden" name="cpt_post_noonce" id="cpt_post_noonce" value="<?php echo wp_create_nonce('gdcpttools'); ?>" />
