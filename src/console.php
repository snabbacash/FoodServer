<?php

// change the following paths if necessary
$yii = dirname(__FILE__).'/../lib/yii/framework/yii.php';
//$commonConfig = dirname(__FILE__).'/protected/config/main.common.php';
$commonConfig = dirname(__FILE__).'/protected/config/cron.common.php';
$localConfig = dirname(__FILE__).'/protected/config/main.local.php';


// include Yii
require_once($yii);
defined('YII_DEBUG') or define('YII_DEBUG',true);


$common = require($commonConfig);

// join local config
if (file_exists($localConfig))
{
	// Merge the configs
	$local = require($localConfig);
	$config = CMap::mergeArray($common, $local);
}
else
	$config = $common;

 
 
// creating and running console application
Yii::createConsoleApplication($config)->run();
