<?php

/**
 * 
 * NOTE: Override these values on a machine-per-machine basis by creating 
 * procected/config/main.php which should contain a subset of what is defined 
 * here. Example:
 * 
 * // use proper database credentials
 * return array(
	'components'=>array(
		'db'=>array(
			'username'=>'root',
			'password'=>'password',
		)
	)
);
 * 
 */
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Foodserver',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.interfaces.*',
	),

	'modules'=>array(
	
	),

	// application components
	'components'=>array(
		'authProvider'=>array(
			'class'=>'LDAPAuthenticationProvider',
			// define the rest in main.local.php
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// use tunneling
		'db'=>array(
			'connectionString'=>'mysql:host=localhost;port=9987;dbname=cash',
			'emulatePrepare'=>true,
			'username'=>'',
			'password'=>'',
			'charset'=>'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);