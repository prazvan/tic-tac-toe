<?php namespace Model;

/**
 * TODO: Create a endTransaction() method that based on result will commit or rollback
 *
 * Class Player
 * @package Model
 */
class History extends Model implements Interfaces\History
{
    /**
     * History id
     *
     * @var int $id
     */
    private $id;

    /**
     * History Player id
     *
     * @var int $player_id
     */
    private $player_id;

    /**
     * History game id
     *
     * @var int $game_id
     */
    private $game_id;

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
     * Get ID
     *
     * @return int
     */
    public function getId(){ return (int) $this->id; }

    /**
     * Set Player id
     *
     * @param int $player_id
     * @return $this
     */
    public function setPlayerId($player_id)
    {
        //-- set game id
        $this->player_id = $player_id;

        //-- return self instance for caning
        return $this;
    }

    /**
     * Get Game Id
     *
     * @return int
     */
    public function getPlayerId() { return (int) $this->player_id; }

    /**
     * Set Game id
     *
     * @param int $game_id
     * @return $this
     */
    public function setGameId($game_id)
    {
        //-- set game id
        $this->game_id = $game_id;

        //-- return self instance for caning
        return $this;
    }

    /**
     * Get Game Id
     *
     * @return int
     */
    public function getGameId() { return (int) $this->game_id; }

    /**
     * Get History
     *
     * @return null
     * @throws \Exception
     */
    public function get()
    {
        try
        {
            //-- database query
            $str_sql = "SELECT id, player_id, game_id FROM history WHERE id = ?";

            //-- execute query
            $history = $this->db->fetchAll($str_sql, array($this->getId()), array(\PDO::PARAM_INT));

            return (isset($history[0]) ? $history[0] : null);
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }

    /**
     * Get History By Game Id
     *
     * @param $game_id
     * @return array
     * @throws \Exception
     */
    public function getByGameId($game_id)
    {
        try
        {
            //-- database query
            $str_sql = "SELECT id, player_id, game_id FROM history WHERE game_id = ?";

            //-- execute query and return result
            return $this->db->fetchAll($str_sql, array($game_id), array(\PDO::PARAM_INT));
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }

    /**
     * Get Statistics
     *
     * @return array
     * @throws \Exception
     */
    public function getStatistics()
    {
        try
        {
            //-- default result
            $statistics = array();

            //-- database query
            $str_sql = "SELECT
                          g.id AS game_id,
                          g.number AS game_number,
                          g.turns AS game_turns,
                          g.won_by AS winner,
                          p.id as player_id,
                          p.name as player_name,
                          p.created as player_join
                        FROM game AS g
                        INNER JOIN history AS h on (h.game_id = g.id )
                        INNER JOIN players AS p on (p.id = h.player_id)
                        ORDER BY g.id DESC
                        LIMIT 10";

            //-- execute query and return result
            $a_results = $this->db->fetchAll($str_sql);

            //-- format data
            foreach ($a_results as $result)
            {
                //-- group by game id
                $statistics[$result['game_id']][$result['player_id']] = array
                (
                    'game_number'   => $result['game_number'],
                    'game_turns'    => $result['game_turns'],
                    'player_id'     => $result['player_id'],
                    'player_name'   => $result['player_name'],
                    'player_join'   => $result['player_join'],
                    'winner'        => ($result['winner'] == $result['player_id']) ? true : false,
                );
            }

            //-- return array with data
            return (array) $statistics;
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            //$this->db->rollBack();

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
            $str_sql = "INSERT INTO history (player_id, game_id) VALUES (?, ?)";

            //-- sql params
            $params = array($this->getPlayerId(), $this->getGameId());

            //-- sql param types
            $types = array(\PDO::PARAM_INT, \PDO::PARAM_INT);

            //-- execute query
            $this->db->executeUpdate($str_sql, $params, $types);

            //-- get history id
            $history_id = $this->db->lastInsertId();

            //-- commit
            $this->db->commit();

            //-- everything is ok just return true
            return $history_id;
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }
}