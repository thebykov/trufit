<?php

global $gdtt, $gdtt_fields, $wp_taxonomies;
$defaults = array('category', 'post_tag', 'link_category');

$tax_default = array_slice($wp_taxonomies, 0, $gdtt->get_defaults_count());
$tax_custom = array_slice($wp_taxonomies, $gdtt->get_defaults_count());

$post_types = get_post_types(array(), 'objects');
$post_count = gdCPTDB::get_post_types_counts();

?>

<table style="margin: 20px auto 0;"><tr><td valign="top">
    <div class="metabox-holder">
        <?php include(GDTAXTOOLS_PATH.'forms/front/posttypes.php'); ?>
    </div>
</td><td style="width: 20px"> </td><td valign="top">
    <div class="metabox-holder">
        <?php include(GDTAXTOOLS_PATH.'forms/front/taxonomies.php'); ?>
    </div>
</td></tr></table>

<?php

include(GDTAXTOOLS_PATH."forms/front/requirements.php");
include(GDTAXTOOLS_PATH."forms/front/copyright.php");

?>