<?php
// Get some Amica parsers
include('amica.php');
$a = new AmicaParser();
$menu = $a->getData('se');
echo "<pre>";
print_r($menu);
echo "</pre>";

