<?php

echo '<h3 class="title">'.__("Taxonomy", "gd-taxonomies-tools").':</h3>';
echo '<strong>'.__("Label", "gd-taxonomies-tools")."</strong>: ".$tax['labels']['name'].'<br/>';
echo '<strong>'.__("Name", "gd-taxonomies-tools")."</strong>: ".$tax['name'];

if ($tax['public'] == 'yes') {
    echo '<h3 class="title">'.__("Template", "gd-taxonomies-tools").' - '.__("Taxonomy Term Posts Archive", "gd-taxonomies-tools").':</h3>';
    echo '<strong>'.__("File Name", "gd-taxonomies-tools").'</strong>: taxonomy-'.$tax['name'].'.php<br/>';
    echo '<em>'.sprintf(__("This file should be based on standard %s template.", "gd-taxonomies-tools"), '<strong>archive.php</strong>').'</em>';

    echo '<h3 class="title">'.__("Template", "gd-taxonomies-tools").' - '.__("For Any Taxonomy", "gd-taxonomies-tools").':</h3>';
    echo '<strong>'.__("File Name", "gd-taxonomies-tools").'</strong>: taxonomy.php<br/>';
    echo '<em>'.__("All taxonomies can use this template if they don't have own named based file.", "gd-taxonomies-tools").' '.sprintf(__("This file should be based on standard %s template.", "gd-taxonomies-tools"), '<strong>archive.php</strong>').'</em>';
} else {
    echo '<h3 class="title">'.__("Notice", "gd-taxonomies-tools").':</h3>';
    echo '<strong>'.__("Taxonomy is not public.", "gd-taxonomies-tools").'</strong>';
}

?>

<br/><a class="pressbutton" style="margin-top: 10px;" href="admin.php?page=gdtaxtools_taxs&amp;cpt=1&amp;tid=<?php echo $_GET['tid']; ?>&amp;action=edit"><?php _e("Edit Taxonomy", "gd-taxonomies-tools"); ?></a>