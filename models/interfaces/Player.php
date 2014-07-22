<?php namespace Model\Interfaces;

/**
 * Interface Player
 * @package Model\Interfaces
 */
interface Player
{
    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get Player Name
     *
     * @return string
     */
    public function getName();

    /**
     * Set Player's Position
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position);

    /**
     * Get Player's position
     *
     * @return int
     */
    public function getPosition();

    /**
     * Save Player in database
     */
    public function save();
} 