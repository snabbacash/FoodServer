<?php

// Define the test url on a machine-per-machine basis by creating a
// protected/config/test.php file.
//
// define('TEST_BASE_URL', 'http://test.local/index.php/');

if (file_exists(__DIR__.'/test.local.php'))
	require_once(__DIR__.'/test.local.php');

// @TODO if we want to test Yii models etc, this is required:
//
// return CMap::mergeArray(
// 	require(dirname(__FILE__).'/main.common.php'),
// 	array(
// 		'components'=>array(
// 			'fixture'=>array(
// 				'class'=>'system.test.CDbFixtureManager',
// 			),
// 			'db'=>array(
// 				'connectionString'=>'mysql:host=localhost;port=9987;dbname=cash_test',
// 				'emulatePrepare'=>true,
// 				'username'=>'',
// 				'password'=>'',
// 				'charset'=>'utf8',
// 			),
// 		),
// 	)
// );
