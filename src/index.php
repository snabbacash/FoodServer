<?php

// change the following paths if necessary
$yii = dirname(__FILE__).'/../lib/yii/framework/yii.php';
$commonConfig = dirname(__FILE__).'/protected/config/main.common.php';
$localConfig = dirname(__FILE__).'/protected/config/main.local.php';

require_once($yii);

// Join common and local config (if it exists)
$common = require($commonConfig);

if (file_exists($localConfig))
{
	// Merge the configs
	$local = require($localConfig);
	$config = CMap::mergeArray($common, $local);
}
else
	$config = $common;

// run the application
Yii::createWebApplication($config)->run();