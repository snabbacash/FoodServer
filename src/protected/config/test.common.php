<?php

// Define the test url on a machine-per-machine basis by creating a
// protected/config/test.php file.
//
// define('TEST_BASE_URL', 'http://test.local/index.php/');

if (file_exists(__DIR__.'/test.local.php'))
	require_once(__DIR__.'/test.local.php');
