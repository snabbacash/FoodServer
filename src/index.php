<?php

// change the following paths if necessary
define('LIB_PATH', dirname(__FILE__).'/../lib');
$yii = LIB_PATH.'/yii/framework/yii.php';
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

Yii::setPathOfAlias('JsonSchema', LIB_PATH.'/json-schema/src/JsonSchema');

// run the application
Yii::createWebApplication($config)->run();
