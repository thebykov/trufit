<?php

/*
Name:    gdr2_Math
Version: 2.8.8
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.dev4press.com/libs/gdr2/
Info:    Collection of functions

== Copyright ==
Copyright 2008 - 2015 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!function_exists('gdr2_factorial')) {
    function gdr2_factorial($int){
        if ($int < 2) {
            return 1;
        }

        for ($f = 2; $int - 1 > 1; $f*=$int--);

        return $f;
    }
}

if (!function_exists('gdr2_combinations')) {
    function gdr2_combinations($n, $r, $order = false, $repeat = false) {
        if ($order && $repeat) {
            return pow($n, $r);
        }

        if ($order && !$repeat) {
            return gdr2_factorial($n) / gdr2_factorial($n - $r);
        }

        if (!order && $repeat) {
            return gdr2_factorial($n + $r - 1) / (gdr2_factorial($r) * gdr2_factorial($n - 1));
        }

        if (!$order && !$repeat) {
            return gdr2_factorial($n) / (gdr2_factorial($n - $r) * gdr2_factorial($r));
        }
    }
}

?>