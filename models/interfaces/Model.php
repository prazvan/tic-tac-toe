<?php namespace Model\Interfaces;

use \Doctrine\DBAL as DBAL;

/**
 * Interface Model
 * @package Model\Interfaces
 */
interface Model
{
    /**
     * Model Constructor
     */
    public function __construct();

    /**
     * Set Database Object
     *
     * @param DBAL\Connection $db
     */
    public function setDb(DBAL\Connection $db);
} 