<?php namespace Model;

/**
 * TODO: Create a endTransaction() method that based on result will commit or rollback
 *
 * Class Player
 * @package Model
 */
class Player extends Model implements Interfaces\Player
{
    /**
     * Player's id
     *
     * @var int $id
     */
    private $id;

    /**
     * Player's Name
     *
     * @var string $name
     */
    private $name;

    /**
     * Player's Position
     *
     * @var int $position
     */
    private $position;

    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        //-- call parent in order to have the db connection
        parent::__construct();

        //-- set player id if not null
        if ($id) $this->setId($id);
    }

    /**
     * Set Player id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        //-- set player id
        $this->id = $id;

        //-- return self instance for caning
        return $this;
    }

    /**
     * Get Player ID
     *
     * @return int
     */
    public function getId(){ return (int) $this->id; }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        //-- set player Name
        $this->name = $name;

        //-- return self instance for caning
        return $this;
    }

    /**
     * Get Player Name
     *
     * @return string
     */
    public function getName(){ return (string) $this->name; }

    /**
     * Set Player's Position
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        //-- set player position
        $this->position = $position;

        //-- return self instance for caning
        return $this;
    }

    /**
     * Get Player's position
     *
     * @return int
     */
    public function getPosition(){ return (int) $this->position; }

    /**
     * get Player
     *
     * @return null|array
     * @throws \Exception
     */
    public function get()
    {
        try
        {
            //-- database query
            $str_sql = "SELECT id, name, position, created FROM players WHERE id = ?";

            //-- execute query
            $player = $this->db->fetchAll($str_sql, array($this->getId()), array(\PDO::PARAM_INT));

            return (isset($player[0]) ? $player[0] : null);
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }

    /**
     * Save Player in database
     *
     * @return int|mixed
     * @throws \Exception
     */
    public function save()
    {
        try
        {
            //-- begin database transaction
            $this->db->beginTransaction();

            //-- database query
            $str_sql = "INSERT INTO players (name, position, created) VALUES (?, ?, NOW())";

            //-- sql params
            $params = array($this->getName(), $this->getPosition());

            //-- sql param types
            $types = array(\PDO::PARAM_STR, \PDO::PARAM_INT);

            //-- execute query
            $this->db->executeUpdate($str_sql, $params, $types);

            //-- get player id
            $player_id = $this->db->lastInsertId();

            //-- commit
            $this->db->commit();

            //-- everything is ok just return true
            return $player_id;
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }
}