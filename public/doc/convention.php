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

// recupération du dernier kit affiche

$req=$pdoBt->query("SELECT * FROM documents WHERE code=10");
$kitData=$req->fetch(PDO::FETCH_ASSOC);



?>

<div class="container">
<h1 class="blue-text text-darken-4">Présentation de la convention 2018</h1>
<br>
	<h4 class="blue-text text-darken-4" ><i class="fa fa-hand-o-right" aria-hidden="true"></i><?= $kitData['name'] ?></h4>
	<p><a  class= "blue-link" href="<?=$kitData['file']?>">télécharger la présentation</a></p>
	<br>
	<br>
 <embed src="<?=$kitData['file']?>" type='application/pdf' width=100% height=900px/>

</div>

<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>