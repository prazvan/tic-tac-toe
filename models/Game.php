<?php namespace Model;

/**
 * Class Game
 * @package Model
 */
class Game extends Model
{
    /**
     * Tic Tac Toe Constants
     */
    const X         = 'x';
    const O         = 'o';
    const PLAYER1   = 1;
    const PLAYER2   = 2;

    /**
     * Game Info
     *
     * @var array
     */
    private $gameInfo = array();

    /**
     * game Constructor
     */
    public function __construct()
    {
        //-- call parent in order to have the db connection
        parent::__construct();
    }

    /**
     * Save game in DB and return id
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
            $str_sql = "INSERT INTO game (number)
                        (
                            SELECT
                                COALESCE(MAX(number) +1 , 1)
                            FROM game
                            ORDER BY number DESC
                            LIMIT 1
                        )";

            //-- execute query
            $this->db->executeUpdate($str_sql);

            //-- get player id
            $game_id = $this->db->lastInsertId();

            //-- commit
            $this->db->commit();

            //-- return result
            return (int) $game_id;
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }

    /**
     * Update Game in DB
     *
     * @return void
     * @throws \Exception
     */
    public function update()
    {
        try
        {
            //-- begin database transaction
            $this->db->beginTransaction();

            //-- get current game
            $current_game = $this->getGameInfo();

            //-- database query
            $str_sql = "UPDATE game SET turns = ?, won_by = ? WHERE id = ?";

            //-- array with sql data
            $sql_params = array
            (
                $current_game['turn'],
                $current_game['won_by'],
                $current_game['game_id']
            );

            //-- array with data types
            $sql_types = array(\PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT);

            //-- execute query
            $this->db->executeUpdate($str_sql, $sql_params, $sql_types);

            //-- commit
            $this->db->commit();
        }
        catch(\Exception $ex)
        {
            //-- something went wrong rollback
            $this->db->rollBack();

            throw $ex;
        }
    }

    /**
     * Update History
     *
     * @throws \Exception
     */
    public function updateHistory()
    {
        try
        {
            //-- get current game
            $current_game = $this->getGameInfo();

            $history = new History();

            //-- search history by game id
            $history_search = $history->getByGameId($current_game['game_id']);

            //-- no history found
            if (empty($history_search))
            {
                //-- for each player update history
                foreach ($current_game['players'] as $player)
                {
                    //-- new instance of history
                    $history->setId($current_game['history_id']);

                    if (!$history->get())
                    {
                        //-- set values and save
                        $history->setPlayerId($player['id'])->setGameId($current_game['game_id'])->save();
                    }
                }
            }
        }
        catch(\Exception $ex)
        {
            throw $ex;
        }
    }

    /**
     * Create Game
     *
     * @param array $players
     * @return array
     * @throws \Exception
     */
    public function create($players = array())
    {
        try
        {
            if (empty($players)) throw new \Exception('Add two players in order to play!');

            //-- save game in db
            $game_id = $this->save();

            //-- add game # to session
            $this->gameInfo['game_id'] = $game_id;

            //-- add players to game
            $this->addPlayers($players);

            //-- start Game
            $this->start();

            //-- return game info
            return (array) $this->getGameInfo();
        }
        catch (\Exception $ex)
        {
            throw $ex;
        }
    }

    /**
     * Update Game info
     *
     * @param array $game_info
     * @return void
     */
    public function updateGameInfo($game_info = array())
    {
        //-- update game info
        $_SESSION['game'] = $game_info;
    }

    /**
     * Get Game info
     *
     * @return mixed
     */
    public function getGameInfo()
    {
        //-- return param or full array
        return isset($_SESSION['game']) ? $_SESSION['game'] : array();
    }

    /**
     * Check Game Status
     *
     * @param array $game
     * @return array
     */
    public function checkStatus($game = array())
    {
        //-- default result
        $a_result = array
        (
            'status'        => true,
            'playing'       => true,
            'allow_click'   => true,
            'won'           => false,
            'won_by'        => 0,
            'draw'          => false,
        );

        try
        {
            //-- get current game from session
            $current_session_game = $this->getGameInfo();

            //-- validate cells
            $cells_result = $this->validateCells($game['cells']);

            //-- set cells history
            $game['current_game']['cells_history'] = $game['cells'];

            //-- determinate who made the move
            $current_player_index = ($game['current_game']['player_turn'] == static::PLAYER1) ? 0 : 1;
            $current_player = $game['current_game']['players'][$current_player_index];

            //-- some small validation
            if ($current_session_game['game_id'] == $game['current_game']['game_id'])
            {
                //-- game still on going
                if ($cells_result['playing'])
                {
                    $a_result['playing'] = true;
                    $a_result['allow_click'] = true;
                }
                elseif ($cells_result['won'])
                {
                    $game['current_game']['won_by'] = $current_player['id'];

                    $a_result['won'] = true;
                    $a_result['won_by']         = $current_player_index;
                    $a_result['playing']        = false;
                    $a_result['allow_click']    = false;
                }

                //-- it's a draw :)
                if ($game['current_game']['turn'] >= 9)
                {
                    $a_result['playing']        = false;
                    $a_result['allow_click']    = false;
                    $a_result['won']            = false;
                    $a_result['won_by']         = 0;
                    $a_result['draw']           = true;
                }

                //-- update history in current game
                $game['current_game']['history_id'] = $this->updateHistory();

                //-- update game info
                $this->updateGameInfo($game['current_game']);

                //-- update game in db
                $this->update();
            }
            else throw new \Exception("not the same game, don't cheat :)");
        }
        catch (\Exception $ex)
        {
            $a_result['status'] = false;
            $a_result['error'] = $ex->getMessage();
        }

        //-- return result
        return $a_result;
    }

    /**
     * Add Players to the Game
     *
     * @param array $players
     */
    private function addPlayers($players = array())
    {
        foreach ($players as $position => $player)
        {
            if (!is_array($player))
            {
                //-- set name
                $name = $player;

                //-- new player
                $new_player = new Player();

                //-- set player position 1 or 2
                $new_player->setPosition($position);

                //-- set player name
                $new_player->setName($name);

                //-- save player in db
                $player_id = $new_player->save();

                //-- set id
                $new_player->setId($player_id);

                $player = $new_player->get();
            }

            $this->gameInfo['players'][] =$player;
        }
    }

    /**
     * Start Game
     *
     * @return void
     */
    private function start()
    {
        //-- add game session id
        $this->gameInfo['session_id'] = session_id();

        //-- add history id
        $this->gameInfo['history_id'] = 0;

        //-- set turn to 0
        $this->gameInfo['turn'] = 0;

        //-- game in progress
        $this->gameInfo['playing'] = true;

        //-- allow clicking
        $this->gameInfo['allow_click'] = true;

        //-- add won
        $this->gameInfo['won'] = false;

        //-- add won by
        $this->gameInfo['won_by'] = 0;

        //-- add draw
        $this->gameInfo['won_by'] = false;

        //-- add game info to Session
        $_SESSION['game'] = $this->gameInfo;
    }

    /**
     * TODO: Can be why better then this. But this has to do for now. Maybe implement a difrent solving Algorithm
     *
     * Validate the cells to see if a player won the game
     * there are 8 possible outcomes to win a game the 9 is draw
     *
     * Simple "Algorithm"
     *
     * @param $cells
     * @return array
     */
    private function validateCells($cells)
    {
        //-- default result values
        $playing = true;
        $won     = false;

        //-- cells map
        $cells_maps = array
        (
            'row1' => array($cells[0], $cells[1], $cells[2]),
            'row2' => array($cells[3], $cells[4], $cells[5]),
            'row3' => array($cells[6], $cells[7], $cells[8]),

            'hor1' => array($cells[0], $cells[3], $cells[6]),
            'hor2' => array($cells[1], $cells[4], $cells[7]),
            'hor3' => array($cells[2], $cells[5], $cells[8]),

            'diag1' => array($cells[0], $cells[4], $cells[8]),
            'diag2' => array($cells[2], $cells[4], $cells[6])
        );

        //-- check row 1
        if ($cells_maps['row1'][0] !== 'none' and $cells_maps['row1'][1] !== 'none' and $cells_maps['row1'][2] !== 'none')
        {
            if ((($cells_maps['row1'][0] == $cells_maps['row1'][1]) and ($cells_maps['row1'][1] == $cells_maps['row1'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check row 2
        if ($cells_maps['row2'][0] !== 'none' and $cells_maps['row2'][1] !== 'row2' and $cells_maps['row2'][2] !== 'none')
        {
            if ((($cells_maps['row2'][0] == $cells_maps['row2'][1]) and ($cells_maps['row2'][1] == $cells_maps['row2'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check row 2
        if ($cells_maps['row3'][0] !== 'none' and $cells_maps['row3'][1] !== 'row3' and $cells_maps['row3'][2] !== 'none')
        {
            if ((($cells_maps['row3'][0] == $cells_maps['row3'][1]) and ($cells_maps['row3'][1] == $cells_maps['row3'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check hor 1
        if ($cells_maps['hor1'][0] !== 'none' and $cells_maps['hor1'][1] !== 'none' and $cells_maps['hor1'][2] !== 'none')
        {
            if ((($cells_maps['hor1'][0] == $cells_maps['hor1'][1]) and ($cells_maps['hor1'][1] == $cells_maps['hor1'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check hor 2
        if ($cells_maps['hor2'][0] !== 'none' and $cells_maps['hor2'][1] !== 'none' and $cells_maps['hor2'][2] !== 'none')
        {
            if ((($cells_maps['hor2'][0] == $cells_maps['hor2'][1]) and ($cells_maps['hor2'][1] == $cells_maps['hor2'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check hor 3
        if ($cells_maps['hor3'][0] !== 'none' and $cells_maps['hor3'][1] !== 'none' and $cells_maps['hor3'][2] !== 'none')
        {
            if ((($cells_maps['hor3'][0] == $cells_maps['hor3'][1]) and ($cells_maps['hor3'][1] == $cells_maps['hor3'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check diag 1
        if ($cells_maps['diag1'][0] !== 'none' and $cells_maps['diag1'][1] !== 'none' and $cells_maps['diag1'][2] !== 'none')
        {
            if ((($cells_maps['diag1'][0] == $cells_maps['diag1'][1]) and ($cells_maps['diag1'][1] == $cells_maps['diag1'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- check diag 2
        if ($cells_maps['diag2'][0] !== 'none' and $cells_maps['diag2'][1] !== 'none' and $cells_maps['diag2'][2] !== 'none')
        {
            if ((($cells_maps['diag2'][0] == $cells_maps['diag2'][1]) and ($cells_maps['diag2'][1] == $cells_maps['diag2'][2])))
            {
                $playing = false;
                $won     = true;
            }
        }

        //-- return status
        return array('playing' => $playing, 'won' => $won);
    }
} 