<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(isset($_GET['idmain'])){
	$req=$pdoBt->prepare("DELETE FROM opp_files_main WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['idmain']
	]);
	header("Location:opp-edit.php?id=".$_GET['id']."#delete-main");
}

if(isset($_GET['idaddons'])){
	$req=$pdoBt->prepare("DELETE FROM opp_files_addons WHERE id= :id");
	$req->execute([
		':id'		=>$_GET['idaddons']
	]);
	header("Location:opp-edit.php?id=".$_GET['id']."#delete-addons");
}






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