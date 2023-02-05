<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

use Phalcon\Config ;


return new Config([
    'database'=> [
        'adapter' => 'Mysql',
        'host' => 'database',
        'username' => 'music',
        'password' => 'music',
        'dbname' => 'music',
        'charset' => 'utf8'
    ],
    'application' => [
        'appDir' => APP_PATH . '/' ,
        'controllersDir' => APP_PATH . '/controllers/' ,
        'modelsDir' => APP_PATH . '/models/' ,
        'migrationsDir' => APP_PATH . '/migrations/' ,
        'ApiUri' => '/api'
    ]
]) ;
