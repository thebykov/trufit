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
    'name' => __("Brightness", "gdr2"),
    'base' => 'sb',
    'display' => array(
        'cd/cm2' => 'cd/cm&sup2;',
        'cd/m2' => 'cd/m&sup2;',
        'cd/in2' => 'cd/in&sup2;',
        'cd/ft2' => 'cd/ft&sup2;'
    ),
    'list' => array(
        'sb' => __("Stilb", "gdr2"),
        'cd/cm2' => __("Candela / square centimeter", "gdr2"),
        'cd/m2' => __("Candela / square meter", "gdr2"),
        'cd/in2' => __("Candela / square inch", "gdr2"),
        'cd/ft2' => __("Candela / square foot", "gdr2"),
        'La' => __("Lambert", "gdr2"),
        'fL' => __("FootLambert", "gdr2"),
        'mL' => __("MeterLambert", "gdr2"),
        'mLa' => __("MilliLambert", "gdr2")
    ),
    'convert' => array(
        'sb' => 1,
        'cd/cm2' => 1,
        'cd/m2' => 0.0001,
        'cd/in2' => 0.15500031000062,
        'cd/ft2' => 0.001076391041671,
        'La' => 0.318309886183791,
        'fL' => 0.000342625909964,
        'mL' => 0.0001,
        'mLa' => 0.000318309886184
    )
);

?>