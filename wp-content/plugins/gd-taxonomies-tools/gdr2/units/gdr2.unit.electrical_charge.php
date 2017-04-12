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
    'name' => __("Electrical Charge", "gdr2"),
    'base' => 'C',
    'list' => array(
        'C' => __("Coulomb", "gdr2"),
        'nC' => __("Nanocoulomb", "gdr2"),
        'uC' => __("Microcoulomb", "gdr2"),
        'mC' => __("Millicoulomb", "gdr2"),
        'kC' => __("Kilocoulomb", "gdr2"),
        'MC' => __("Megacoulomb", "gdr2"),
        'GC' => __("Gigacoulomb", "gdr2"),
        'abC' => __("Abcoulomb", "gdr2"),
        'emu' => __("Electromagnetic unit of charge", "gdr2"),
        'ecu' => __("Electrostatic unit of chargee", "gdr2"),
        'F' => __("Faraday", "gdr2"),
        'Fr' => __("Franklin", "gdr2"),
        'Ah' => __("Ampere Hour", "gdr2"),
        'Am' => __("Ampere Minute", "gdr2"),
        'As' => __("Ampere Second", "gdr2"),
        'mAh' => __("Milliampere Hour", "gdr2"),
        'mAm' => __("Milliampere Minute", "gdr2"),
        'mAs' => __("Milliampere Second", "gdr2")
    ),
    'convert' => array(
        'C' => 1,
        'nC' => 0.000000001,
        'uC' => 0.000001,
        'mC' => 0.001,
        'kC' => 1000,
        'MC' => 1000000,
        'GC' => 1000000000,
        'abC' => 10,
        'emu' => 10,
        'ecu' => 0.000000000334,
        'F' => 96485.338300000003,
        'Fr' => 0.000000000334,
        'Ah' => 3600,
        'Am' => 60,
        'As' => 1,
        'mAh' => 3.6,
        'mAm' => 0.06,
        'mAs' => 0.001
    )
);

?>