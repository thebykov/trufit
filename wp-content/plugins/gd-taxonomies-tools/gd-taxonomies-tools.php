<?php

/*
Plugin Name: GD Custom Posts And Taxonomies Tools
Plugin URI: http://www.gdcpttools.com/
Description: GD Custom Posts And Taxonomies Tools is plugin for management and tools collection for working with custom posts and taxonomies.
Version: 4.3.9
Author: Milan Petrovic
Author URI: http://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2015 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$gdtt_dirname_basic = dirname(__FILE__);

// Loading of development version of JavaScript.
if (!defined('GDTAXTOOLS_JS_DEV')) define('GDTAXTOOLS_JS_DEV', false);
// Full path to a text file used to save debug info. File must be writeable.
if (!defined('GDTAXTOOLS_LOG_PATH')) define('GDTAXTOOLS_LOG_PATH', $gdtt_dirname_basic.'/debug.txt');

require_once($gdtt_dirname_basic.'/code/defaults.php');
require_once($gdtt_dirname_basic.'/gdr2/gdr2.core.php');
require_once($gdtt_dirname_basic.'/gdr2/gdr2.widget.php');
require_once($gdtt_dirname_basic.'/gdr2/gdr2.units.php');
require_once($gdtt_dirname_basic.'/gdr2/plugin/gdr2.settings.module.php');

require_once($gdtt_dirname_basic.'/code/modules/load.php');

require_once($gdtt_dirname_basic.'/code/internal/functions.php');
require_once($gdtt_dirname_basic.'/code/internal/classes.php');

require_once($gdtt_dirname_basic.'/code/meta/core.php');
require_once($gdtt_dirname_basic.'/code/meta/fields.php');
require_once($gdtt_dirname_basic.'/code/meta/data.php');

define('GDTAXTOOLS_WP_ADMIN', defined('WP_ADMIN') && WP_ADMIN);
define('GDTAXTOOLS_WP_AJAX', defined('DOING_AJAX') && DOING_AJAX);
define('GDTAXTOOLS_WP_CRON', defined('DOING_CRON') && DOING_CRON);
define('GDTAXTOOLS_POSTBACK', isset($_POST['gdcpt_form_postback']));

require_once($gdtt_dirname_basic.'/code/class.php');
require_once($gdtt_dirname_basic.'/code/core.php');

global $gdtt, $gdtt_fields, $gdtt_core, $gdtt_admin, $gdtt_icons, $gdpt_ajax_admin;

$gdtt = new gdCPT_Tools($gdtt_dirname_basic, __FILE__);
$gdtt_fields = new gdCPT_Fields();

require_once($gdtt_dirname_basic.'/gdr2/plugin/gdr2.plugin.shortcodes.php');
require_once($gdtt_dirname_basic.'/code/internal/shortcodes.php');

include(GDTAXTOOLS_PATH.'code/public/general.php');
include(GDTAXTOOLS_PATH.'code/public/filters.php');
include(GDTAXTOOLS_PATH.'code/public/display.php');
include(GDTAXTOOLS_PATH.'code/public/template.php');
include(GDTAXTOOLS_PATH.'code/public/meta.php');

include(GDTAXTOOLS_PATH.'code/public/legacy.php');

$gdtt_core = new gdCPT_Core();

if (GDTAXTOOLS_WP_ADMIN) {
    require_once($gdtt_dirname_basic.'/gdr2/gdr2.icons.php');
    require_once($gdtt_dirname_basic.'/code/internal/admin.php');

    $gdtt_admin = new gdCPTAdmin($gdtt->o, $gdtt->p, $gdtt->t, $gdtt->sf);
    $gdtt_icons = new gdCPTIcons();

    if (GDTAXTOOLS_WP_AJAX) {
        require_once($gdtt_dirname_basic.'/code/internal/ajax.php');

        $gdpt_ajax_admin = new gdCPTAdmin_AJAX();
    }
}

?>