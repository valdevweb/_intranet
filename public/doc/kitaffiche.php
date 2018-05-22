<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";
require '../view/_head.php';
require '../view/_navbar.php';



?>

<div class="container">
<h1 class="blue-text text-darken-4">Kit Affiche</h1>
<br>
	<h4 class="blue-text text-darken-4" id="odr-title"><i class="fa fa-hand-o-right" aria-hidden="true"></i>SB fête des mères du 15 au 26 mai 2018</h4>
	<a  class= "blue-link" href="KIT AFFICHES SB FETE DES MÈRES.xls">télécharger le kit affiche</a>
</div>

<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>