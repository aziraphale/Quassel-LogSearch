<?php

// Uncomment the below line and edit the path as appropriate if you wish to move your config.ini file out of your web
//  document root (you should!)
# define('CONFIG_FILENAME', dirname(__FILE__) . '/../config.ini');

require dirname(__FILE__) . '/vendor/autoload.php';

QuasselLogSearch\Bootstrap::init();
