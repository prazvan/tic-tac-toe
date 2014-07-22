<?php

/**
 * Require Composer autoload
 */
$autoload = require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

/**
 * Include Helpers
 */
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'helpers.php';

/**
 * Include Dependencies
 */
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'interfaces'.DIRECTORY_SEPARATOR.'View.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'View.php';

/**
 * Include Application
 */
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'interfaces'.DIRECTORY_SEPARATOR.'Application.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'Application.php';

/**
 * Include Models
 */
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'interfaces'.DIRECTORY_SEPARATOR.'Model.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'interfaces'.DIRECTORY_SEPARATOR.'Player.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'interfaces'.DIRECTORY_SEPARATOR.'History.php';

include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'Model.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'Player.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'Game.php';
include_once dirname(__DIR__).DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'History.php';