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
    'name' => __("Temperature", "gdr2"),
    'base' => 'C',
    'list' => array(
        'C' => __("Celsius", "gdr2"),
        'F' => __("Fahrenheit", "gdr2"),
        'K' => __("Kelvin", "gdr2"),
        'R' => __("Reaumur", "gdr2")
    ),
    'convert' => array(
        'C' => array('ratio' => 1, 'offset' => 0),
        'F' => array('ratio' => 1.8, 'offset' => 32),
        'K' => array('ratio' => 1, 'offset' => 273),
        'R' => array('ratio' => 0.8, 'offset' => 0)
    ),
    'system' => array(
        'metric' => array('C'),
        'imperial' => array('F'),
        'us' => array('F')
    )
);

?>