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
    'name' => __("Energy", "gdr2"),
    'base' => 'Wh',
    'list' => array(
        'Wh' => __("Watt Hour", "gdr2"),
        'Ws' => __("Watt Second", "gdr2"),
        'mWh' => __("Milliwatt Hour", "gdr2"),
        'kWh' => __("Kilowatt Hour", "gdr2"),
        'MWh' => __("Kilowatt Hour", "gdr2"),
        'GWh' => __("Gigawatt Hour", "gdr2"),
        'cal' => __("Calorie", "gdr2"),
        'kcal' => __("Kilocalorie", "gdr2"),
        'J' => __("Joule", "gdr2"),
        'kJ' => __("Kilojoule", "gdr2"),
        'MJ' => __("Megajoule", "gdr2"),
        'GJ' => __("Gigajoule", "gdr2"),
        'uJ' => __("Microjoule", "gdr2"),
        'mJ' => __("Millijoule", "gdr2")
    ),
    'convert' => array(
        'Wh' => 1,
        'Ws' => 0.000277777777778,
        'mWh' => 0.001,
        'kWh' => 1000,
        'MWh' => 1000000,
        'GWh' => 1000000000,
        'cal' => 0.001163,
        'kcal' => 1.163,
        'J' => 0.000277777777778,
        'kJ' => 0.277777777777778,
        'MJ' => 277.777777777778,
        'GJ' => 277777.777777778,
        'uJ' => 0.000000000277777777778,
        'mJ' => 0.000000277777777778
    )
);

?>