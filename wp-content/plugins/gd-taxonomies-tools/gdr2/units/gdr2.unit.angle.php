<?php

/*
Name:    gdr2_Units: Angle
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    http://en.wikipedia.org/wiki/Unit_conversion
*/

$gdr2_unit_loading = array(
    'name' => __("Angle", "gdr2"),
    'base' => 'radian',
    'list' => array(
        'radian' => __("Radian", "gdr2"),
        'grad' => __("Grad", "gdr2"),
        'degree' => __("Degree", "gdr2"),
        'minute' => __("Minute", "gdr2"),
        'second' => __("Second", "gdr2"),
        'revolution' => __("Revolution", "gdr2"),
    ),
    'convert' => array(
        'radian' => 1,
        'grad' => 0.015707963268,
        'degree' => 0.01745329252,
        'minute' => 0.00029088820867,
        'second' => 0.0000048481368111,
        'revolution' => 6.283185307,
    )
);

?>