<?php

//-- Define Development constant
if (!defined('DEVELOPMENT')) define('DEVELOPMENT', 'development');

//-- Define Production constant
if (!defined('PRODUCTION'))  define('PRODUCTION', 'production');

/**
 * Application Config file
 */
return array
(
    /**
     * Application Environment
     */
    'environment' => DEVELOPMENT
);