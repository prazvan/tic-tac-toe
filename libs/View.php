<?php namespace Lib;

/**
 * Class View
 * @package Lib
 */
class View implements Interfaces\View
{
    /**
     *  make view
     *
     * @param string $view
     * @param array $arguments
     * @return mixed|void
     * @throws \ErrorException
     */
    public function make($view, $arguments = array())
    {
        foreach ($arguments as $key => $argument)
        {
            //-- if key is not string continue
            if (is_int($key)) continue;

            //-- set view variable name
            $name = (string) $key;

            //-- assign value to variable
            ${$name} = $argument;
        }

        //-- path to view
        $view_path = 'views'.DIRECTORY_SEPARATOR.$view.'.php';

        //-- if view file is not found throw exception
        if (!file($view_path)) throw new \ErrorException($view.' view not found!');

        //-- include view
        include_once($view_path);
    }
} 