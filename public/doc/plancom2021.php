<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

include('../view/_head-bt.php');
include('../view/_navbar.php');

?>

<div class="container">
<h1 class="text-main-blue py-5">Plan de communication 2021</h1>
<p class="text-center"><i class="fas fa-arrow-alt-circle-right pr-3"></i><a href="#plan20"> Plan de communication 2021</a> <span class="px-3">-</span> <a href="#arg20"> Argumentaire plan de communication 2021</a></p>

<br>
<h5 class="text-main-blue pb-5">Le plan de communication</h5>
 <embed src="<?=SITE_ADDRESS ."/public/doc/PLAN DE COMMUNICATION BTLEC EST 2021.pdf" ?>" type='application/pdf' width=100% height=900px/ id="plan20">
 <h5 class="text-main-blue py-5" id="arg20">L'argumentaire</h5>
 <embed src="<?=SITE_ADDRESS ."/public/doc/Argumentaire au Plan de communication 2021 - Magasins.pdf" ?>" type='application/pdf' width=100% height=900px/ >
</div>

<?php
require '../view/_footer-bt.php';
?>