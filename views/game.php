<?php
//-- include head
include_once 'partials'.DIRECTORY_SEPARATOR.'head.php';
include_once 'partials'.DIRECTORY_SEPARATOR.'header.php';
?>
    <div class="container">
        <div class="homepage-text" id="game-result" style="display: none">
            <div class="page-header">
                <h2 class="game-title">Game #<span id="game_id"></span> is Over!</h2>
                <h5 class="small" id="player_holder_contianer"><span id="player_holder"></span> Won the game!</h5>
            </div>

            <div class="btn-group right">
                <a href="index.php?action=start_new_game" title="Quit game" class="btn">Start a new Game</a>
            </div>

        </div>

        <div class="homepage-text" id="dashboard">
            <div class="page-header">
                <h2 class="game-title"><?php echo $game['players'][0]['name']." VS ".$game['players'][1]['name']?></h2>
                <span class="small">Tic Tac Toe Game #<?php echo $game['game_id'];?></span>
            </div>
            <div id="player_turn" class="affix-header"><?php echo ucfirst($game['players'][0]['name'])."'s turn";?></div>


            <div class="btn-group right">
                <a href="index.php?action=quit" title="Quit game" class="btn btn-default">Quit Game</a>
            </div>
        </div>

        <div class="game-board" id="game-board">

            <div class="row">
                <div class="ttt cell-0" id="cell0"></div>
                <div class="ttt cell-1" id="cell1"></div>
                <div class="ttt cell-2" id="cell2"></div>
            </div>
            <div class="row">
                <div class="ttt cell-3" id="cell3"></div>
                <div class="ttt cell-4" id="cell4"></div>
                <div class="ttt cell-5" id="cell5"></div>
            </div>
            <div class="row">
                <div class="ttt cell-6" id="cell6"></div>
                <div class="ttt cell-7" id="cell7"></div>
                <div class="ttt cell-8" id="cell8"></div>
            </div
        </div>
    </div>
<?php
//-- include footer
include_once 'partials'.DIRECTORY_SEPARATOR.'footer.php';
?>