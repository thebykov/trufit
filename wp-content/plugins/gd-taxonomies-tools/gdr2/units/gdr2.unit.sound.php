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
    'name' => __("Sound", "gdr2"),
    'base' => 'B',
    'list' => array(
        'B' => __("Bel", "gdr2"),
        'dB' => __("Decibel", "gdr2"),
        'neper' => __("Neper", "gdr2")
    ),
    'convert' => array(
        'B' => 1,
        'dB' => 10,
        'neper' => 1.1512779
    )
);

?>