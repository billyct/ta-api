<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';
define('PUBLICPATH', __DIR__);
define('BASEURL', 'http://api.localhost');

define( "WB_AKEY" , '1652999032' );
define( "WB_SKEY" , '901c3aa0164b252722f6a196fc7464d9' );
define( "WB_CALLBACK_URL" , 'http://api.localhost/api/sina/callback' );
// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
