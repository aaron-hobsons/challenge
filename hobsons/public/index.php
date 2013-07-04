<?php
date_default_timezone_set('America/New_York'); 
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));


/** Zend_Application */
require_once 'Zend/Application.php';

			
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$Bootstrap=$application->getBootstrap();

//Zend_Registry::set('Db',$Bootstrap->getPluginResource('db')->getDbAdapter());
// Zend_Registry::set('Logger',$Bootstrap->getPluginResource('log')->getLog());
//
$Autoloader = Zend_Loader_Autoloader::getInstance();
$Autoloader->registerNameSpace('H_');
$Autoloader->setFallbackAutoloader(true);

Zend_Session::start();

$application->bootstrap()
            ->run();
