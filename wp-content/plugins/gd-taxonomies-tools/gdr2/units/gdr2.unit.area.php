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
    'name' => __("Area", "gdr2"),
    'base' => 'm2',
    'display' => array(
        'm2' => 'm&sup2;',
        'km2' => 'km&sup2;',
        'cm2' => 'cm&sup2;',
        'mm2' => 'mm&sup2;',
        'um2' => '&micro;m&sup2;',
        'in2' => 'in&sup2;',
        'mi2' => 'mi&sup2;',
        'dt2' => 'ft&sup2;',
        'yd2' => 'yd&sup2;'
    ),
    'list' => array(
        'm2' => __("Square Meter", "gdr2"),
        'km2' => __("Square Kilometer", "gdr2"),
        'cm2' => __("Square Centimeter", "gdr2"),
        'mm2' => __("Square Millimeter", "gdr2"),
        'um2' => __("Square Micrometer", "gdr2"),
        'in2' => __("Square Inch", "gdr2"),
        'mi2' => __("Square Mile", "gdr2"),
        'ft2' => __("Square Foot", "gdr2"),
        'yd2' => __("Square Yard", "gdr2"),
        'a' => __("Are", "gdr2"),
        'ha' => __("Hectare", "gdr2"),
        'acre' => __("Acre", "gdr2")
    ),
    'convert' => array(
        'm2' => 1,
        'km2' => 1000000,
        'cm2' => 0.0001,
        'mm2' => 0.000001,
        'um2' => 0.000000000001,
        'in2' => 0.00064516,
        'mi2' => 2589988.110336,
        'ft2' => 0.09290304,
        'yd2' => 0.83612736,
        'a' => 100,
        'ha' => 10000,
        'acre' => 4046.8564224
    ),
    'system' => array(
        'metric' => array('m2', 'km2', 'cm2', 'mm2', 'um2', 'a', 'ha'),
        'imperial' => array('in2', 'mi2', 'ft2', 'yd2', 'acre'),
        'us' => array('in2', 'mi2', 'ft2', 'yd2', 'acre')
    )
);

?>