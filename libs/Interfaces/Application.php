<?php namespace Lib\Interfaces;

/**
 * Interface Application
 * @package Lib\Interfacesß
 */
interface Application
{
    /**
     * @param array $options
     * @return self
     */
    public static function register($options = array());

    /**
     * Redirect to Page
     *
     * @param string $page
     */
    public static function redirect($page);

    /**
     * Render action
     *
     * @param null $view
     */
    public function route($view = null);
}