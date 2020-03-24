<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
    echo "pas de variable session";
    header('Location:'. ROOT_PATH.'/index.php');
}
if (isset($_GET['chart_x']) && isset($_GET['chart_y'])) {
    $_SESSION['chart_x'] = $_GET['chart_x'];
    $_SESSION['chart_y'] = $_GET['chart_y'];
}else{
        $_SESSION['chart_x'] = 0;
    $_SESSION['chart_y'] = 0;
}

include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
    <h1 class="text-main-blue py-5 ">Main title</h1>




        <form name="postPosition" id="postPosition" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input name="chart_x" id="chart_x" type="hidden" value="" />
            <input name="chart_y" id="chart_y" type="hidden" value="" />
        </form>
        <div class="row">
            <div class="col-4"></div>
            <div class="col">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="col-4"></div>

        </div>

        <div class="row">
            <div class="col-4"></div>
            <div class="col">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="col-4"></div>

        </div>


        <div class="row">
            <div class="col-4"></div>
            <div class="col">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="col-4"></div>

        </div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="col-4"></div>

        </div>
<form>

    <button class="btn btn-primary" name="valid">test 1</button>

                <div class="row">
            <div class="col-4"></div>
            <div class="col">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="col-4"></div>

        </div>
                <div class="row">
            <div class="col-4"></div>
            <div class="col">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
            <div class="col-4"></div>

        </div>
    <button class="btn btn-primary" name="valid">test 2</button>

</form>


</div>      <!-- Display Stuff Here -->

      <script type="text/javascript">

function getScroll() {
                    var x = 0, y = 0;
                    var position = new Object();
                    position.x = document.body.scrollLeft;
                    position.y = document.body.scrollTop;
                    return position;
                };

                function saveScroll() {
                    var position = getScroll();
                    document.getElementById("chart_x").value = position.x;
                    document.getElementById("chart_y").value = position.y;
                    document.forms["postPosition"].submit();
                }

                function setScroll() {
                    var x = <?php echo json_encode($_SESSION['chart_x']); ?>;
                    var y = <?php echo json_encode($_SESSION['chart_y']); ?>;
                    if (x && y)
                        window.scrollTo(x, y);
                }
            $( document ).ready(function() {
                // $( window ).load(setScroll());

                setScroll();

                $('button').on('click',function(){
                saveScroll();


                });
                // $( window ).unload(saveScroll());

            });


        </script>
        <?php


require '../view/_footer-bt.php';
?>