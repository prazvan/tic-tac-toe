<?php

//-- we need the session so let's start it :)
session_start();

/**
 * Require Dependencies in order to start the App
 */
require __DIR__.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'start.php';

try
{
    //-- get route
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

    //-- register a new instance and run the app
    Lib\Application::register()->route($action);
}
catch (\Exception $ex)
{
    print_error('Application Error!', array
    (
        'code'      => $ex->getCode(),
        'message'   => $ex->getMessage(),
        'trace'     => $ex->getTraceAsString()
    ));
}