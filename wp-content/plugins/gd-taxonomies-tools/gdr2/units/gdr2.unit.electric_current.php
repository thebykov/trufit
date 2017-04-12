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
    'name' => __("Electric Current", "gdr2"),
    'base' => 'A',
    'list' => array(
        'A' => __("Ampere", "gdr2"),
        'mA' => __("Milliampere", "gdr2"),
        'abamp' => __("Abamper", "gdr2"),
        'MA' => __("Megampere", "gdr2"),
        'esu/s' => __("Statampere", "gdr2")
    ),
    'convert' => array(
        'A' => 1,
        'mA' => 0.001,
        'abamp' => 10,
        'MA' => 0.000333564095198,
        'esu/s' => 3.33564095198152e-010
    )
);

?>