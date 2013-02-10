<?php
include('../lib/amica/amica.php');

$a = new AmicaParser();
$foods = $a->getData('se');
echo json_encode($foods);
//echo json_encode($foods[date("Y-n-j")] );
//echo "<pre>";
//print_r( $foods[date("Y-n-j")] );
//echo "</pre>";