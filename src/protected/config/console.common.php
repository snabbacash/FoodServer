<?php
return array(
    // This path may be different. You can probably get it from `config/main.php`.
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'FoodServer cron',
 
    'preload'=>array('log'),
 
    'import'=>array(
        'application.components.*',
        'application.models.*',
    ),
    // We'll log cron messages to the separate files
    'components'=>array(
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
                    'levels'=>'error, warning',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron_trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
 
        // Your DB connection, should be defined in protected/config/main.local.php
        'db'=>array(
        	'connectionString'=>'mysql:host=localhost;port=3306;dbname=cash',
		),
    ),
);