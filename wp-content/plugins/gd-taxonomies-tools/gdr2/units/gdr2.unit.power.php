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
    'name' => __("Power", "gdr2"),
    'base' => 'W',
    'list' => array(
        'W' => __("Watt", "gdr2"),
        'kW' => __("Kilowatt", "gdr2"),
        'MB' => __("Megawatt", "gdr2"),
        'GB' => __("Gigawatt", "gdr2"),
        'hp' => __("Horsepower", "gdr2"),
        'hp-m' => __("Horsepower metric", "gdr2"),
        'mhp' => __("Millihorsepower", "gdr2"),
        'cal/hr' => __("Calorie / hour", "gdr2"),
        'cal/min' => __("Calorie / minute", "gdr2"),
        'cal/sec' => __("Calorie / second", "gdr2"),
        'joule/hr' => __("Joule / hour", "gdr2"),
        'joule/min' => __("Joule / minute", "gdr2"),
        'joule/sec' => __("Joule / second", "gdr2")
    ),
    'convert' => array(
        'W' => 1,
        'kB' => 1000,
        'MB' => 1000000,
        'GB' => 1000000000,
        'hp' => 745.69987158227,
        'hp-m' => 735.49875,
        'mhp' => 0.74569987158227,
        'cal/hr' => 0.001163,
        'cal/min' => 0.06978,
        'cal/sec' => 4.1868,
        'joule/hr' => 0.000277777777778,
        'joule/min' => 0.016666666666667,
        'joule/sec' => 1,
    )
);

?>