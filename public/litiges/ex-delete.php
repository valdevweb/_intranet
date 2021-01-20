<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
$table=$_GET['table'];
$id=$_GET['id'];
$errors=[];
function deleteData($pdoLitige, $table, $id)
{
	$req=$pdoLitige->prepare("delete FROM $table WHERE id=$id");
	$req->execute();
	return $req->rowCount();
	// return $req->errorInfo();

}

$done=deleteData($pdoLitige, $table, $id);
if($done>0)
{
	header('Location:'. $_SERVER['HTTP_REFERER']);

}
else
{
$errors[]="impossible de traiter la demande";


}


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>



<div class="container">
<?php
include('../view/_errors.php');


 ?>

</div>




<?php

require '../view/_footer-bt.php';

?>