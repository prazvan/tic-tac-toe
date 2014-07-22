<?php namespace Model;

use \Doctrine\DBAL as DBAL;

/**
 * Class Model
 * @package Model
 */
abstract class Model implements Interfaces\Model
{
    /**
     * Database Object
     *
     * @var DBAL\Connection
     */
    protected $db;

    /**
     * basic Model
     */
    public function __construct()
    {
        //-- use global config
        global $config;

        //-- set database object
        $this->setDb(DBAL\DriverManager::getConnection($config['db'], new DBAL\Configuration()));
    }

    /**
     * Set Database Object
     *
     * @param DBAL\Connection $db
     */
    public function setDb(DBAL\Connection $db){ $this->db = $db; }

    /**
     * Save Method for all modules
     *
     * @return mixed
     */
    public abstract function save();
}