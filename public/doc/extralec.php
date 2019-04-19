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
<h1 class="blue-text text-darken-4">L'application Extralec</h1>


<p>Pour toute information ou inscription, rendez-vous sur <a href="http://www.extralecbtlec.fr" class="blue-link"> http://www.extralecbtlec.fr</a></p>
 <embed src="<?=SITE_ADDRESS ."/public/doc/plaquetteCommercialeEXTRALEC-ZEPLV.pdf" ?>" type='application/pdf' width=100% height=900px/>
</div>

<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>