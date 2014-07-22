<?php

/**
 * App Config
 */
$config = array();

/**
 * Errors?
 */
$error_reporting = 0;
$display_errors = 0;

/**
 * Include app config
 */
$config['app'] = require dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'app.php';

/**
 * If application environment is development show errors
 */
if ($config['app']['environment'] === DEVELOPMENT)
{
    $error_reporting = E_ALL;
    $display_errors = 1;
}

error_reporting($error_reporting);
ini_set('display_errors', $display_errors);

/**
 * Include database config
 */
$config['db'] = require dirname(__DIR__).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'database.php';

/**
 * Register Autoload
 */
require 'autoload.php';