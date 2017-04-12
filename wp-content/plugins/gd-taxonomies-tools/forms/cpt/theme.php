<?php

echo '<h3 class="title">'.__("Post Type", "gd-taxonomies-tools").':</h3>';
echo '<strong>'.__("Label", "gd-taxonomies-tools")."</strong>: ".$cpt['labels']['name'].'<br/>';
echo '<strong>'.__("Name", "gd-taxonomies-tools")."</strong>: ".$cpt['name'];

if ($cpt['public'] == 'yes') {
    echo '<h3 class="title">'.__("Template", "gd-taxonomies-tools").' - '.__("Single Post", "gd-taxonomies-tools").':</h3>';
    echo '<strong>'.__("File Name", "gd-taxonomies-tools")."</strong>: single-".$cpt['name'].'.php<br/>';
    echo '<em>'.sprintf(__("This file should be based on standard %s template.", "gd-taxonomies-tools"), '<strong>single.php</strong>').'</em>';

    echo '<h3 class="title">'.__("Template", "gd-taxonomies-tools").' - '.__("Posts Archive", "gd-taxonomies-tools").':</h3>';
    if ($cpt['archive'] !== 'no') {
        echo '<strong>'.__("File Name", "gd-taxonomies-tools")."</strong>: archive-".$cpt['name'].'.php<br/>';
        echo '<em>'.sprintf(__("This file should be based on standard %s template.", "gd-taxonomies-tools"), '<strong>archive.php</strong>').'</em>';
    } else {
        echo '<strong>'.__("Archives for this post type are disabled.", "gd-taxonomies-tools").'</strong>';
    }
} else {
    echo '<h3 class="title">'.__("Notice", "gd-taxonomies-tools").':</h3>';
    echo '<strong>'.__("Post type is not public.", "gd-taxonomies-tools").'</strong>';
}

?>

<br/><a class="pressbutton" style="margin-top: 10px;" href="admin.php?page=gdtaxtools_postypes&amp;cpt=1&amp;pid=<?php echo $_GET["pid"]; ?>&amp;action=edit"><?php _e("Edit Post Type", "gd-taxonomies-tools"); ?></a>
