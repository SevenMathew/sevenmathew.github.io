<?php

// set application timezone 
date_default_timezone_set('Europe/Bucharest');

// DATABASE CONNECTION SETTINGS
define('MYSQL_HOST',     ''); // ---- TO FILL IN ----- the sql host
define('MYSQL_USER',     ''); // ---- TO FILL IN ----- the sql user
define('MYSQL_PASSWORD', ''); // ---- TO FILL IN ----- the sql user password
define('MYSQL_DB',       ''); // ---- TO FILL IN ----- the sql database
define('TABLE_PREFIX',   ''); // ---- TO FILL IN ----- the sql database tables prefix

// Physical paths
define('SITE_ROOT', ""); // ---- TO FILL IN ----- path on server to root of the project
define('SITE_LIB', SITE_ROOT . 'lib/');


//*
define('FACEBOOK_APP_URL', '');// ---- TO FILL IN ----- the url of the application on facebook
define('FACEBOOK_APP_ID', '');// ---- TO FILL IN ----- the facebook application id
define('FACEBOOK_APP_SECRET', '');// ---- TO FILL IN ----- the facebook application secret key
//*/

// application url
define('WWW_ROOT', '');// ---- TO FILL IN ----- the url to the application on your domain

define('APP_NAME', '');// ---- TO FILL IN ----- the name of the application

/*** UTILS ***/
define('HTML_CHECKED', ' checked="checked"');
define('HTML_SELECTED', ' selected="selected"');
define('HTML_DISABLED', ' disabled="disabled"');

// reserved ip addresses for development.. 
$dev_ips = array();// ---- TO FILL IN ----- array of developer IP addresses

// set real client ip address
$remote_addr = '';
if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
{
	$remote_addr = $_SERVER['HTTP_CLIENT_IP'];
}
elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))//to check ip is pass from proxy
{
	$remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) // most of the time this will be the ip address
{
	$remote_addr = $_SERVER['REMOTE_ADDR'];
}

// assign the value to the most common variable used for remote ip address 
$_SERVER['REMOTE_ADDR'] = $remote_addr;

// check if the request is made from a development ip address
if (in_array($_SERVER['REMOTE_ADDR'], $dev_ips))
{
	ini_set('display_errors', 1); // display the errors to the developers
}
else 
{
	ini_set('display_errors', 0); // do not show the errors to regular users
}
ini_set('error_reporting', E_ALL); // enable error reporting
ini_set('error_log', SITE_ROOT . "php_error_log.log"); // save the errors to this error log


// connect to the database
try 
{
	$dbh = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD);

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // set error handling to throwing exceptions
	$dbh->setAttribute(PDO::ATTR_PERSISTENT, true); // use a persistent connection 
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, true); // use prepared statements 
	$dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); // use buffered queries
	$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //change the default fetch mode from array to assoc   
} 
catch (PDOException $e) 
{
	// check if the request is made by a developer
	if (in_array($_SERVER['REMOTE_ADDR'], $dev_ips))
	{
		print "Error!: " . $e->getMessage() . "<br/>"; // display connection problem
	}
	die; // stop application if no database connection can be established
}
// save the timestamp for this request
$request_timestamp = time();

define('PHP_DATE_FORMAT', 'd M');
define('PHP_DATETIME_FORMAT', 'M j, Y H:i');

// include global functions file 
require_once SITE_LIB . 'site_functions.php';

// set the autoloader for helper classes
spl_autoload_register ( 'load_helper' );
