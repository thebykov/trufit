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
    'name' => __("Fuel Consumption", "gdr2"),
    'base' => 'L/km',
    'display' => array(
        'km/gallon/uk' => 'km/gallon',
        'km/gallon/us' => 'km/gallon',
        'mile/gallon/uk' => 'mile/gallon',
        'mile/gallon/us' => 'mile/gallon',
        'gallon/km/uk' => 'gallon/km',
        'gallon/km/us' => 'gallon/km',
        'gallon/mile/uk' => 'gallon/mile',
        'gallon/mile/us' => 'gallon/mile',
    ),
    'list' => array(
        'L/km' => __("Liter/100 Kilometer", "gdr2"),
        'L/mile' => __("Liter/100 Mile", "gdr2"),
        'km/L' => __("Kilometer/Liter", "gdr2"),
        'mile/L' => __("Mile/Liter", "gdr2"),
        'km/gallon/uk' => __("Kilometer/Gallon - UK", "gdr2"),
        'km/gallon/us' => __("Kilometer/Gallon - US", "gdr2"),
        'mile/gallon/uk' => __("Mile/Gallon - UK", "gdr2"),
        'mile/gallon/us' => __("Mile/Gallon - US", "gdr2"),
        'gallon/km/uk' => __("Gallon/100 Kilometer - UK", "gdr2"),
        'gallon/km/us' => __("Gallon/100 Kilometer - US", "gdr2"),
        'gallon/mile/uk' => __("Gallon/100 Mile - UK", "gdr2"),
        'gallon/mile/us' => __("Gallon/100 Mile - US", "gdr2")
    ),
    'convert' => array(
        'L/km' => 1,
        'L/mile' => 0.621371192237334,
        'km/L' => 100,
        'mile/L' => 62.1371192237334,
        'km/gallon/uk' => 454.609,
        'km/gallon/us' => 378.5411784,
        'mile/gallon/uk' => 282.480936331822,
        'mile/gallon/us' => 235.214583333333,
        'gallon/km/uk' => 4.54609,
        'gallon/km/us' => 3.785411784,
        'gallon/mile/uk' => 2.82480936331822,
        'gallon/mile/us' => 2.35214583333333
    ),
    'system' => array(
        'metric' => array('L/km', 'km/L'),
        'imperial' => array('L/mile', 'mile/L', 'mile/gallon/uk', 'gallon/mile/uk'),
        'us' => array('L/mile', 'mile/L', 'mile/gallon/us', 'gallon/mile/us')
    )
);

?>