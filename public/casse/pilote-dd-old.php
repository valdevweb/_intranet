<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);

// require_once '../../vendor/autoload.php';

require('casse-getters.fn.php');

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------





if(isset($_GET['id']))
{
	$expInfo=getExpAndPalette($pdoCasse,$_GET['id']);
	// echo "<pre>";
	// print_r($expInfo);
	// echo '</pre>';

}
else{
	$loc='Location:bt-casse-dashboard.php?error=1';
	header($loc);

}

$textareaCt="Bonjour,\nNous allons faire partir le       , ".count($expInfo) ." palettes pour le magasin ".$expInfo[0]['btlec'].". Vous trouverez ci joint le tableau de correspondance des contremarques.\n";
$textareaCt.="Pourriez vous faire déstocker ces palettes et les vérifier avant leur envoi ? Une fois ok , les mettre en RAQ et faire un retour sur le portail ?"



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

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
	<h1 class="text-main-blue py-5 ">Demande de destockage palettes casses</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col">
			<form action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >

				<div class="form-group">
					<label for="action">Message :</label>
					<textarea type="text" class="form-control" name="msg" id="msg" style="height:180px"><?=$textareaCt?></textarea>
				</div>

			</form>
		</div>
	</div>



	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>