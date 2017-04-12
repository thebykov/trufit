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
    'name' => __("Time", "gdr2"),
    'base' => 'ns',
    'list' => array(
        'ns' => __("Nanosecond", "gdr2"),
        'us' => __("Microsecond", "gdr2"),
        'ms' => __("Millisecond", "gdr2"),
        's' => __("Second", "gdr2"),
        'min' => __("Minute", "gdr2"),
        'hour' => __("Hour", "gdr2"),
        'day' => __("Day", "gdr2"),
        'week' => __("Week", "gdr2"),
        'month' => __("Month", "gdr2"),
        'year' => __("Year", "gdr2"),
        'century' => __("Century", "gdr2"),
        'millennium' => __("Millennium", "gdr2")
    ),
    'convert' => array(
        'ns' => 1,
        'us' => 1000,
        'ms' => 1000000,
        's' => 1000000000,
        'min' => 60000000000,
        'hour' => 3600000000000,
        'day' => 86400000000000,
        'week' => 604800000000000,
        'month' => 2592000000000000,
        'year' => 31556926000000000,
        'century' => 3155692600000000000,
        'millennium' => 31556926000000000000
    )
);

?>