<script type="text/javascript" src="./vendor/yiisoft/jquery/jquery.min.js"></script>
<script type="text/javascript" src="./vendor/twitter/bootstrap/dist/js/bootstrap.min.js"></script>
<?php if (isset($_REQUEST['action']) and ($_REQUEST['action'] == 'game' or $_REQUEST['action'] == 'start_new_game')) { ?>
    <script type="text/javascript" src="./assets/js/helpers.js"></script>
    <script type="text/javascript" src="./assets/js/game.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            //-- init game
            var game = new Game();

            var options = <?php echo json_encode($game); ?>;

            //-- run the game
            game.run(options);
        });
    </script>

<?php } ?>
</body>
</html>