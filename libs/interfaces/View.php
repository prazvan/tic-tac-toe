<?php namespace Lib\Interfaces;

/**
 * Interface view
 * @package Lib\Interfaces
 */
interface View
{
    /**
     * @param string $view
     * @param array $arguments
     * @return mixed
     */
    public function make($view, $arguments = array());
}