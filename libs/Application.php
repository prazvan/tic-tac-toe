<?php namespace Lib;

use Lib\Interfaces as i;
use Model;

/**
 * Class Application
 * @package Lib
 */
final class Application implements i\Application
{
    /**
     * @var static $App
     */
    private static $App;

    /**
     * The View Object
     *
     * @var i\View $view
     */
    private static $View;

    /**
     * Game Object
     *
     * @var \Model\Game $Game
     */
    private static $Game;

    /**
     * History Object
     *
     * @var \Model\History $Game
     */
    private static $History;

    /**
     * Application Options
     *
     * @var array $options
     */
    private static $options = array();

    /**
     * Application constructor is private in order to use singleton
     */
    private function __construct()
    {
        //-- set view object
        $this->setView(new View());

        //-- set game object
        $this->setGame(new Model\Game());

        $this->setHistory(new Model\History());
    }

    /**
     * Set View Object
     * @param i\View $View
     */
    private function setView(i\View $View) { static::$View = $View; }

    /**
     * Set New Game Object
     *
     * @param Model\Game $Game
     */
    private function setGame(Model\Game $Game) { static::$Game = $Game;}

    /**
     * Set New History Object
     *
     * @param Model\History $History
     */
    private function setHistory(Model\History $History) { static::$History = $History;}

    /**
     * Register New Instance of App
     *
     * @param array $options
     * @return Application|i\Application
     */
    public static function register($options = array())
    {
        //-- merge app options
        static::$options = array_merge(static::$options, $options);

        //-- register new instance
        if (is_null(static::$App)) static::$App = new static();

        //-- return instance
        return static::$App;
    }

    /**
     * Redirect to Page
     *
     * @param string $page
     */
    public static function redirect($page){ header('Location: index.php?action='.$page); }

    /**
     * Render action
     *
     * @param null $view
     */
    public function route($view = null)
    {
        switch ($view)
        {
            case 'game':
                $this->startGame();
            break;

            case 'game_status':
                $this->gameStatus();
            break;

            case 'start_new_game':
                $this->resetGame();
            break;

            case 'quit':
                $this->quit();
            break;

            case 'statistics':
                $this->statistics();
            break;

            case 'homepage':
            default:
                $this->homepage();
            break;
        }
    }

    /**
     * Render Homepage
     * @return void
     */
    private function homepage()
    {
        //-- render homepage
        static::$View->make('homepage');
    }

    /**
     * get Statistics
     *
     * @return void
     */
    private function statistics()
    {
        //-- render homepage
        static::$View->make('statistics', array('statistics_data' => static::$History->getStatistics()));
    }

    /**
     * Check Game Status
     * @return void
     */
    private function gameStatus()
    {
        $current_game = (!empty($_POST) ? $_POST : array());

        //-- check game status
        $game_status = static::$Game->checkStatus($current_game);

        //-- make view
        static::$View->make('game_status', array('game_status' => $game_status));
    }

    /**
     * Reset all game info and start a new one
     */
    private function resetGame()
    {
        //-- get current game info
        $current_game = static::$Game->getGameInfo();

        $players = $current_game['players'];

        //-- check game status
        $current_game = static::$Game->create($players);

        //-- make view
        static::$View->make('game', array('game' => $current_game));
    }

    /**
     * Quit Game
     */
    private function quit()
    {
        //-- destroy session and redirect
        session_destroy();
        static::redirect('homepage');
    }

    /**
     * Start or continue a game
     *
     * @return void
     */
    private function startGame()
    {
        try
        {
            //-- get players from post
            $players = (isset($_POST['players']) ? $_POST['players'] : array());

            //-- validate data
            $validator = $this->validatePlayers($players);

            if ($validator['success'])
            {
                //-- get current game
                $current_game = static::$Game->getGameInfo();

                //-- create new game in case we don't have one
                if (empty($current_game))
                {
                    $current_game = static::$Game->create($players);
                }

                //-- render view
                static::$View->make('game', array('game' => $current_game));
            }
            else
            {
                //-- throw error
                throw $validator['error'];
            }
        }
        catch (\Exception $ex)
        {
            //-- set flush message
            flush_message($ex->getMessage());

            //-- show homepage :)
            static::redirect('homepage');
        }
    }

    /**
     * Validate Players
     *
     * @param array $players
     * @return array
     */
    private function validatePlayers($players = array())
    {
        //-- default result
        $valid = array('success' => true, 'error' => null);

        foreach ($players as $player)
        {
            if (empty($player))
            {
                //-- result is false :)
                $valid['success'] = false;

                //-- player name is invalid
                $valid['error'] = new \Exception('Player Name is Invalid!');
            }
        }

        //-- return result
        return $valid;
    }
} 