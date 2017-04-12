<?php

/*
Name:    gdr2_Units: Length
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    http://en.wikipedia.org/wiki/Unit_conversion
*/

$gdr2_unit_loading = array(
    'name' => __("Memory", "gdr2"),
    'base' => 'B',
    'list' => array(
        'bit' => __("Bit", "gdr2"),
        'B' => __("Byte", "gdr2"),
        'KB' => __("Kilobyte", "gdr2"),
        'MB' => __("Megabyte", "gdr2"),
        'GB' => __("Gigabyte", "gdr2"),
        'TB' => __("Terabyte", "gdr2"),
        'PB' => __("Petabyte", "gdr2"),
        'CD74' => __("1 CD 74min", "gdr2"),
        'CD80' => __("1 CD 80min", "gdr2"),
        'DVD' => __("1 DVD", "gdr2"),
        'DVDDL' => __("1 DVD Dual Layer", "gdr2"),
        'BD' => __("1 BD", "gdr2"),
        'BDDL' => __("1 BD Dual Layer", "gdr2")
    ),
    'convert' => array(
        'bit' => 0.125,
        'B' => 1,
        'KB' => 1024,
        'MB' => 1048576,
        'GB' => 1073741824,
        'TB' => 1099511627800,
        'PB' => 1125899906800000,
        'CD74' => 681058304,
        'CD80' => 736279247,
        'DVD' => 5046586572.8,
        'DVDDL' => 9126805504,
        'BD' => 26843545600,
        'BDDL' => 53687091200
    )
);

?>