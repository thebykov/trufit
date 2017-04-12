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
    'name' => __("Weight / Mass", "gdr2"),
    'base' => 'mg',
    'list' => array(
        'mg' => __("Milligram", "gdr2"),
        'g' => __("Gram", "gdr2"),
        'kg' => __("Kilogram", "gdr2"),
        't' => __("Tonne", "gdr2"),
        'oz' => __("Ounce", "gdr2"),
        'lb' => __("Pound", "gdr2"),
        'st' => __("Stone", "gdr2"),
        'qtr' => __("Quarter", "gdr2"),
        'carat' => __("Carat", "gdr2")
    ),
    'convert' => array(
        'mg' => 1,
        'g' => 1000,
        'kg' => 1000000,
        't' => 1000000000,
        'oz' => 28349.5231,
        'lb' => 453592.37,
        'st' => 6350293.18,
        'qtr' => 12700586.36,
        'carat' => 205.196548333
    ),
    'system' => array(
        'metric' => array('mg', 'g', 'kg', 't'),
        'imperial' => array('oz', 'lb', 'carat', 'st', 'qtr'),
        'us' => array('oz', 'lb', 'carat', 'st', 'qtr')
    )
);

?>