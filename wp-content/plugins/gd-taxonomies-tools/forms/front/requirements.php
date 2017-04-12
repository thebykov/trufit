<?php

require_once(GDTAXTOOLS_PATH."gdr2/gdr2.utils.php");
require_once(GDTAXTOOLS_PATH."gdr2/gdr2.ui.php");
$requirements = array(
    "php" => array("name" => "PHP", "version" => "5.0.0"),
    "mysql" => array("name" => "mySQL", "version" => "4.0.0"),
    "wordpress" => array("name" => "WordPress", "version" => "2.9.0"),
    "php_extensions" => array("name" => "PHP Extensions", "list" => array("curl"))
);
$g2_utils->system_requirements($requirements, true);

?>