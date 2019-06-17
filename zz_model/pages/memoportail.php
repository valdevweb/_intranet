
<?php
// btn retour



require_once  '../../Class/Helpers.php';
	<?= Helpers::returnBtn('mag-litige-listing.php');?>


<!-- lien direct -->
<!-- dans le mail  -->
	$link='<a href="'.SITE_ADDRESS.'/index.php?litiges/ctrl-stock.php?id='.$litige[0]['id_main'].'"> cliquant ici</a>';

<!-- sur la pge ou est redirigé l'utilisateur -->
unset($_SESSION['goto']);
