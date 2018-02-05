<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';
require "../../functions/stats.fn.php";
$descr="historique côté mag";
$page=basename(__file__);
$action="tableau général";
addRecord($pdoStat,$page,$action, $descr);



//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('histo-mag.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');