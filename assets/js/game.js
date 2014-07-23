/**
 * Game App
 * @type {Function}
 */
var Game = (function()
{
    /**
     * Game Options
     *
     * @type {{}}
     * @private
     */
    var _options = {};

    //-- player's index
    var _player1 = 0, _player2 = 1;

    /**
     * Set Current Player
     *
     * @private
     */
    function _outputPlayer(index)
    {
        //-- set current player index
        _options.player_turn = index;

        //-- set current player
        $('#player_turn').html($.ucfirst(_options.players[_options.player_turn].name) + "'s turn");
    }

    /**
     * Based on Current Player add X or O
     *
     * @param cell
     * @private
     */
    function _add_cell_element(cell)
    {
        //-- default output
        var output = 'x';

        //-- if player's 2 turn then switch to o
        if (_options.player_turn == _player2) output = 'o';

        //-- add to html
        $(cell).html(output);
    }

    /**
     * Switch Player :)
     *
     * @private
     */
    function _switch_player()
    {
        //-- if current player is player 1 switch to 2
        switch (_options.player_turn)
        {
            case _player1:
                _options.player_turn = _player2;
            break;

            case _player2:
                _options.player_turn = _player1;
            break;
        }

        //-- set current player
        _outputPlayer(_options.player_turn);
    }

    /**
     *
     * @private
     */
    function _enable_board()
    {
        $('#game-board .row div').click(function(event)
        {
            //-- prevent default behavior
            event.preventDefault();

            //-- empty cell
            if ($(this).html() == '' && _options.allow_click)
            {
                //-- based on current player add cell element
                _add_cell_element(this);

                //-- switch player
                _switch_player();

                //-- increment turn
                _options.turn++;

                var cells = [];
                $('#game-board .row div').each(function(index, element)
                {
                    //-- add elements to array
                    var html = 'none';

                    //-- add x or o
                    if ($(element).html() != '') html = $(element).html();

                    //-- push to cells
                    cells.push(html);
                });

                $.ajax({
                    url: 'index.php?action=game_status',
                    type: 'POST',
                    DataType: 'JSON',
                    cache: false,
                    data:
                    {
                        current_game: _options,
                        cells: cells
                    },

                    success: function(result)
                    {
                        if (result.status)
                        {
                            _options.allow_click = result.allow_click;

                            if (!result.playing && result.won)
                            {
                                //-- hide player turn the game is over
                                $('#dashboard').hide();

                                //-- show game result
                                $('#game_id').html(_options.game_id);
                                $('#player_holder').html(_options.players[result.won_by].name);
                                $('#game-result').show();
                            }
                            else if (!result.playing && result.draw)
                            {
                                //-- hide player turn the game is over
                                $('#dashboard').hide();

                                //-- show game result
                                $('#game_id').html(_options.game_id);
                                $('#player_holder_contianer').html("It's a Draw :)");

                                $('#game-result').show();
                            }
                        }
                        else
                        {
                            //-- we have some errors
                            if (result.error) alert(result.error);
                        }
                    },
                    error: function(data, status)
                    {
                        //-- error send logic
                        alert('hoops something went wrong :(');
                    }
                });

                //-- a turn is still available
                if (_options.turn < 9)
                {

                }
            }
        });
    }

    /**
     * Run game
     * @private
     */
    function _run(options)
    {
        //-- merge options
        _options = options;

        //-- set player turn
        _outputPlayer(_player1);

        //-- enable the board
        _enable_board();
    }

    return {
        /**
         * Run the Game
         * @param options
         */
        run: function(options){ _run(options); }
    }

});