<?php
//-- include head
include_once 'partials'.DIRECTORY_SEPARATOR.'head.php';
include_once 'partials'.DIRECTORY_SEPARATOR.'header.php';
?>

<div class="container">

    <div class="homepage-text">

        <?php
        $message = show_message();
        if ($message)
        {
            ?>

            <div class="alert alert-danger" role="alert"><?=$message;?></div>

        <?php
        }
        ?>

        <h1>Welcome to Tic Tac Toe</h1>
        <p class="lead">Please Enter your names</p>
        <form id="new_game_form" class="new-game-form" action="index.php?action=game" method="post">
            <div class="input-group input-group-lg">
                <span class="input-group-addon">Player 1</span>
                <input type="text" name="players[1]" class="form-control" placeholder="Nickname" value="Player 1" />
            </div>
            <br />
            <div class="input-group input-group-lg">
                <span class="input-group-addon">Player 2</span>
                <input type="text" name="players[2]" class="form-control" placeholder="Nickname" value="Player 2" />
            </div>
            <br />
            <button type="submit" class="btn btn-success">Start new Game</button>
        </form>

    </div>

</div><!-- /.container -->

<?php
//-- include footer
include_once 'partials'.DIRECTORY_SEPARATOR.'footer.php';
?>