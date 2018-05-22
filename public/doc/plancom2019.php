<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}
require '../view/_head.php';
require '../view/_navbar.php';

?>

<div class="container">
<h1 class="blue-text text-darken-4">Plan de communication 2019</h1>

<br>
 <embed src="<?=SITE_ADDRESS ."/public/doc/PLAN DE COMMUNICATION BTLEC EST 2019.pdf" ?>" type='application/pdf' width=100% height=900px/>
</div>

<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>