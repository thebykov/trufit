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
    'name' => __("Frequency", "gdr2"),
    'base' => 'Hz',
    'list' => array(
        'Hz' => __("Hertz", "gdr2"),
        'kHz' => __("Kilohertz", "gdr2"),
        'MHz' => __("Megahertz", "gdr2"),
        'GHz' => __("Gigahertz", "gdr2"),
        'THz' => __("Terahertz", "gdr2"),
        'mHz' => __("Millihertz", "gdr2"),
        'rad/hr' => __("Radian / Hour", "gdr2"),
        'rad/min' => __("Radian / Minute", "gdr2"),
        'rad/s' => __("Radian / Second", "gdr2"),
        'deg/hr' => __("Degree / Hour", "gdr2"),
        'deg/min' => __("Degree / Minute", "gdr2"),
        'deg/s' => __("Degree / Second", "gdr2"),
        'cps' => __("Cycle / Second", "gdr2")
    ),
    'convert' => array(
        'Hz' => 1,
        'kHz' => 1000,
        'MHz' => 1000000,
        'GHz' => 1000000000,
        'THz' => 1000000000000,
        'mHz' => 0.001,
        'rad/hr' => 0.000044209706414415,
        'rad/min' => 0.002652582384865,
        'rad/s' => 0.159154943091895,
        'deg/hr' => 0.000000771604938272,
        'deg/min' => 0.000046296296296296,
        'deg/s' => 0.002777777777778,
        'cps' => 1,
    )
);

?>