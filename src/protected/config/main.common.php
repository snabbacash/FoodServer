<?php

/**
 * 
 * NOTE: Override these values on a machine-per-machine basis by creating 
 * procected/config/main.php which should contain a subset of what is defined 
 * here. Example:
 * 
 <?php
 // use proper database credentials
 return array(
	'components'=>array(
		'db'=>array(
			//'connectionString'=>'mysql:host=localhost;port=3306;dbname=cash',
			'username'=>'root',
			'password'=>'password',
		),
		'authProvider'=>array(
			'ldapUrl' => 'ldaps://ldap.example.org',
			'ldapSearchBase' => 'ou=others,dc=example,dc=org',
		),
	)
); 
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
			'class'=>'ArcadaLdapAuthenticationProvider',
            'ldapUrl'=>'ldaps://ldap.arcada.fi',
            'ldapSearchBase'=>'ou=people,dc=arcada,dc=fi'
			// define the rest in main.local.php
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		// I hope You have a Big Ass Screen
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				array('auth/login', 	  'pattern'	=>'login', 							'verb'=>'POST'),

				array('menu/view', 		  'pattern'	=>'menu/today', 					'verb'=>'GET'),
				array('menu/view', 		  'pattern'	=>'menu/<date:\d{4}-\d{2}-\d{2}>', 	'verb'=>'GET'),
				array('menu/list', 		  'pattern'	=>'menu/<week:\d{1,2}>', 			'verb'=>'GET'),
				
				
				array('order/listDate', 	  'pattern'	=>'orders/<date:\d{4}-\d{2}-\d{2}>', 'verb'=>'GET'),
				array('order/listStatus', 'pattern'	=>'orders/<status:(new|refunded|paid|pending)>', 'verb'=>'GET'),
				
				
				array('order/create',  	  'pattern'	=>'orders', 						'verb'=>'POST'),
				array('order/list',   	  'pattern'	=>'orders', 						'verb'=>'GET'),

				array('order/view', 	  'pattern'	=>'orders/<id:\d+>', 				'verb'=>'GET'),
				array('order/update', 	  'pattern'	=>'orders/<id:\d+>', 				'verb'=>'PUT'),
				array('order/delete', 	  'pattern'	=>'orders/<id:\d+>', 				'verb'=>'DELETE'),
				// Keep this one last
				//array('order/listUser',	  'pattern'	=>'orders/<username:\w+>', 			'verb'=>'GET'),
				
				array('user/view', 		  'pattern'	=>'user/<username:\w+>', 			'verb'=>'GET'),
				array('user/update', 	  'pattern'	=>'user/<username:\w+>', 			'verb'=>'PUT'),
				
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		// use tunneling
		'db'=>array(
			'connectionString'=>'mysql:host=localhost;port=3306;dbname=cash',
			'emulatePrepare'=>true,
			'charset'=>'utf8',
			'username'=>'',
			'password'=>'',
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
		
		// username used for in combination with the API token
		'httpUsername'=>'api_token',
	),
);
