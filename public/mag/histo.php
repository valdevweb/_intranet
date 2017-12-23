<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';

$allMagMsg=showAllMsg($pdoBt);




//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('histo.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');