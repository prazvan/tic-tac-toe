<?php
//-- include head
include_once 'partials'.DIRECTORY_SEPARATOR.'head.php';
include_once 'partials'.DIRECTORY_SEPARATOR.'header.php';
?>

    <div class="container">

        <div class="panel panel-default margin-top-70">

        <?php if (!empty($statistics_data)) { ?>

            <!-- Default panel contents -->
            <div class="panel-heading"><strong>History of the games</strong></div>
            <div class="panel-body">does not show rematches</div>

            <!-- Table -->
            <table class="table">

                <thead>
                    <tr>
                        <td><b>Game ID</b></td>
                        <td><b>Players</b></td>
                        <td><b>Winner</b></td>
                    </tr>
                </thead>

                <?php  foreach ($statistics_data as $game_id => $data) {

                    $players = null;
                    $winner  = 'Draw';
                    foreach ($data as $value)
                    {
                        $players[] = ucfirst($value['player_name']);

                        if ($value['winner'])
                        {
                            $winner = ucfirst($value['player_name']);
                        }
                    }
                ?>

                <tr>
                    <td><?php echo $game_id;?></td>
                    <td><?php echo implode(' VS ', $players);?></td>
                    <td><?php echo $winner;?></td>
                </tr>

                <?php } ?>

            </table>

        <?php }
              else
              {
        ?>
                  <div class="panel-heading"><strong>No Games Found!</strong></div>
        <?php
              }
        ?>
        </div>

    </div>

<?php
//-- include footer
include_once 'partials'.DIRECTORY_SEPARATOR.'footer.php';
?>