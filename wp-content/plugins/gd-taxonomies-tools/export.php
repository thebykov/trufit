<?php

require_once(dirname(__FILE__).'/config.php');
$wpload = get_gdtt_wpload_path();
require($wpload);

include(GDTAXTOOLS_PATH.'code/internal/impexp.php');

@ini_set('memory_limit', '128M');
@set_time_limit(360);

$p = $_GET;
check_ajax_referer('gdcptools-export');

if (!gdr2_is_current_user_admin()) {
    wp_die(__("Only administrators can use export features.", "gd-taxonomies-tools"));
}

$export_date = date('Y-m-d');
if ($p['mod'] == 'terms') {
    gdtt_export_terms($p['tax'], $p['hir']);

    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename="gdcpt_'.$p["tax"].'_'.$export_date.'.txt"');

    global $gdtt_export_var;
    echo $gdtt_export_var;
} else if ($p['mod'] == 'settings') {
    $cpt_list = isset($p['cl']) ? $p['cl'] : array();
    $tax_list = isset($p['tl']) ? $p['tl'] : array();

    gdtt_export_settings($p['cpt'], $p['tax'], $p['ovr'], $p['met'], $p['set'], $p['cap'], $cpt_list, $tax_list);

    header('Content-type: application/force-download');
    header('Content-Disposition: attachment; filename="gdcpt_plugin_settings_'.$export_date.'.gdcpt"');

    global $gdtt_export_var;
    echo $gdtt_export_var;
} else {
    header('Location: '.site_url());
    exit;
}

?>