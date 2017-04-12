<?php

global $gdtt, $post_type;

$taxonomies = gdtt_get_taxonomies_for_post_types($post_type, "object");
$tax_h = array();

foreach ($taxonomies as $tax) {
    if ($tax->show_ui) {
        $tax_h[] = "'".$tax->name."': ".($tax->hierarchical ? 1 : 0);
    }
}

?>
<div id="gdcpt-container-term" style="display:none;">
    <div id="gdcpt-tinymce-selector">
        <table>
            <tr>
                <td class="gdcpt-tinymce-td-left"><?php _e("Taxonomy", "gd-taxonomies-tools"); ?>:</td>
                <td class="gdcpt-tinymce-td-right">
                    <select id="txTaxonomy">
                        <?php

                            foreach ($taxonomies as $tax) {
                                if ($tax->show_ui) {
                                    echo "\t<option value='".$tax->name."'>".$tax->label."</option>\r\n";
                                }
                            }

                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="gdcpt-tinymce-td-left"><?php _e("Term Text", "gd-taxonomies-tools"); ?>:</td>
                <td class="gdcpt-tinymce-td-right">
                    <input type="text" id="txOriginalText" value="" />
                </td>
            </tr>
        </table>
    </div>
</div>