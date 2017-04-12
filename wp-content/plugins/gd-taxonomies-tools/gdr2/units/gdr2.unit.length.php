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
    'name' => __("Lenght or Distance", "gdr2"),
    'base' => 'mm',
    'display' => array(
        'um' => '&micro;m',
        'uin' => '&micro;in'
    ),
    'list' => array(
        'nm' => __("Picometre", "gdr2"),
        'nm' => __("Nanometre", "gdr2"),
        'um' => __("Micrometre", "gdr2"),
        'mm' => __("Millimeter", "gdr2"),
        'cm' => __("Centimeter", "gdr2"),
        'dm' => __("Decimeter", "gdr2"),
        'm' => __("Meter", "gdr2"),
        'km' => __("Kilometer", "gdr2"),
        'pt' => __("Point", "gdr2"),
        'uin' => __("Micro Inch", "gdr2"),
        'in' => __("Inch", "gdr2"),
        'ft' => __("Feet", "gdr2"),
        'yd' => __("Yard", "gdr2"),
        'mi' => __("Mile", "gdr2"),
        'nmi' => __("Nautical Mile", "gdr2")
    ),
    'convert' => array(
        'pm' => 0.000000001,
        'nm' => 0.000001,
        'um' => 0.001,
        'mm' => 1,
        'cm' => 10,
        'dm' => 100,
        'm' => 1000,
        'km' => 1000000,
        'pt' => .3527778,
        'uin' => .0000254,
        'in' => 25.4,
        'ft' => 304.8,
        'yd' => 914.4,
        'mi' => 1609344,
        'nmi' => 1852000
    ),
    'system' => array(
        'metric' => array('pm', 'nm', 'um', 'mm', 'cm', 'dm', 'm', 'km'),
        'imperial' => array('pt', 'uin', 'in', 'ft', 'yd', 'mi', 'nmi'),
        'us' => array('pt', 'uin', 'in', 'ft', 'yd', 'mi', 'nmi')
    )
);

?>