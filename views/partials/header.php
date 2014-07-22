<?php
$homepage = null;
$statistics = null;
if (isset($_REQUEST['action']))
{
    switch ($_REQUEST['action'])
    {
        case 'statistics':
            $statistics = 'class="active"';
        break;

        default:
        case 'homepage':
            $homepage = 'class="active"';
        break;
    }
}
else $homepage = 'class="active"';
?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php" title="Home Page">Tic Tac Toe</a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php echo $homepage;?>><a href="index.php">Home</a></li>
                <li <?php echo $statistics;?>><a href="index.php?action=statistics">Statistics</a></li>
            </ul>
        </div>

    </div>

</div>

