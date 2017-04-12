<?php

require_once(GDTAXTOOLS_PATH.'gdr2/plugin/gdr2.settings.admin.php');
require_once(GDTAXTOOLS_PATH.'code/internal/render.php');
require_once(GDTAXTOOLS_PATH.'code/modules/admin.php');

$r = new gdCPTRender();
$r->base = 'gdr2_module_';

$s = new gdCPTModules_Admin();
$p = $s->panels();

?>

<script type='text/javascript'>
    gdCPTTools.cookie_name = "wp-gdcpt-modules";

    jQuery(document).ready(function() {
        gdCPTAdmin.panel.modules.init();
    });
</script>

<div class="gdcpt-settings">
    <form action="" id="gdcpt-settings-form" method="post">
        <input name="gdr2_action" type="hidden" value="gdr2_modules" />
        <input name="gdr2_scope" type="hidden" value="site" />
        <input name="gdr2_response" type="hidden" value="json" />
        <input name="gdr2_type" type="hidden" value="modules" />
        <input name="gdr2_base" type="hidden" value="gdr2_module_" />

        <div id="tabs">
            <ul><?php $i = 1;
                foreach ($p as $panel) {
                    echo sprintf('<li><a href="#tabs-%s">%s</a><div>%s</div></li>', $i, $panel->title, $panel->subtitle);
                    $i++;
                }
            ?></ul>
            <?php
                $i = 1;
                foreach ($p as $panel) {
                    $r->base = 'gdr2_module_'.$panel->name.'_';

                    $elements = $s->settings($panel->name);
                    echo sprintf('<div id="tabs-%s"><div class="gdr2-panel gdr2-panel-%s">%s', $i, $panel->name, GDR2_EOL);

                    foreach ($panel->groups as $group) {
                        $group->base_url = GDTAXTOOLS_URL;
                        $group->render($r, $panel->name, $elements, 'modules');
                    }

                    echo '</div></div>'.GDR2_EOL;
                    $i++;
                }
            ?>
        </div>
        <input style="margin-top: 10px;" type="submit" value="<?php _e("Save Settings", "gd-taxonomies-tools"); ?>" name="gdcpt-save" class="pressbutton" />
    </form>
</div>