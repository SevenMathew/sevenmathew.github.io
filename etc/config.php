<?php

// call the url rewritting function; the $_modules variable will be set inside this function also;
rewrite();

// set the name used for the application session; use only alphanumeric characters
session_name('');// ---- TO FILL IN ----- the name of session 
session_start();

// include template engine
require_once SITE_LIB . 'template/IT.php';

// create a new instance for the template engine class
$tpl = new HTML_Template_IT(SITE_ROOT . 'templates/');

$a_message = array();
$b_isError = false;

$questions = Questions::get_set_of_questions();



require_once SITE_LIB . 'facebook.php';
$fbconfig = array();
$fbconfig['baseUrl'] = WWW_ROOT;
$fbconfig['appBaseUrl'] = FACEBOOK_APP_URL;

$facebook = new Facebook(array(
        'appId' => 130372163967607,
        'secret' => ea66de539af3668cd1e6d32691dc26c5,
        'cookie' => true,
        'fileUpload' => true
));
