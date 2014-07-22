<?php

/**
 * @param $message
 * @param $error
 */
function print_error($message, $error)
{
    global $config;
    if ($config['app']['environment'] === DEVELOPMENT)
    {
        echo "<div style='padding: 10px;background: darkorange; color: black'>$message</div><br/>";
        dd($error);
    }
    else echo "Hoops, something went wrong :(";
}

/**
 * Dump and die on command :)
 *
 * @param $variable
 * @param bool $die
 */
function dd($variable, $die = false)
{
    echo "<pre>";
    print_r($variable);
    echo "</pre>";
    if ($die) die;
}

/**
 * Set Session Flush
 *
 * @param string $message
 */
function flush_message($message = null)
{
    $_SESSION['flush'] = $message;
}

/**
 * Show Message
 *
 * @return null
 */
function show_message()
{
    //-- get message
    $message = (isset($_SESSION['flush']) ? $_SESSION['flush'] : null);

    //-- unset
    unset($_SESSION['flush']);

    //-- return message
    return $message;
}