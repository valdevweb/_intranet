<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">

</div>

<?php
require '../view/_footer-bt.php';
?>