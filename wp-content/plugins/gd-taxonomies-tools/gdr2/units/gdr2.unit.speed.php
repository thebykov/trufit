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
    'name' => __("Speed", "gdr2"),
    'base' => 'kp/h',
    'list' => array(
        'mp/s' => __("Meters per second", "gdr2"),
        'kp/h' => __("Kilometers per hour", "gdr2"),
        'mp/h' => __("Miles per hour", "gdr2"),
        'kn' => __("Knots", "gdr2")
    ),
    'convert' => array(
        'mp/s' => 3.6,
        'kp/h' => 1,
        'mp/h' => 1.609344,
        'kn' => 1.852
    ),
    'system' => array(
        'metric' => array('mp/s', 'kp/h'),
        'imperial' => array('mp/h'),
        'us' => array('mp/h')
    )
);

?>