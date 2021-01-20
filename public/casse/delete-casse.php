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
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function copyCasse($pdoCasse){
	$req=$pdoCasse->prepare("INSERT INTO casses_deleted (date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc, valo, pu, fournisseur, id_origine, id_type, id_palette, mt_mag, mt_decote, mt_ndd, num_ndd, etat, detruit, cmt, date_clos, last_maj) SELECT date_casse, id_web_user, id_operateur, nb_colis, id_categorie, article, dossier, gt, designation, pcb, uvc, valo, pu, fournisseur, id_origine, id_type, id_palette, mt_mag, mt_decote, mt_ndd, num_ndd, etat, detruit, cmt, date_clos, last_maj FROM casses WHERE id= :id");
	$req->execute([
		':id'	=>$_GET['id']
	]);
	return $req->rowCount();
	// return $req->errorInfo();
}
function deleteCasse($pdoCasse){
	$req=$pdoCasse->prepare("DELETE FROM casses WHERE id= :id");
	$req->execute([
		':id'	=>$_GET['id']
	]);
	return $req->rowCount();
}



if(isset($_GET['id'])){

	$copy=copyCasse($pdoCasse);
		echo "<pre>";
		print_r($copy);
		echo '</pre>';

	if($copy==1){
		$delete=deleteCasse($pdoCasse);
		if($delete==1){
			header('Location: bt-casse-dashboard.php?deleteOk='.$_GET['id'].'');
		}
		else{
			header('Location: bt-casse-dashboard.php?error=2');
		}
	}
	else{
	header('Location: bt-casse-dashboard.php?error=2');

	}


}
else{
	header('Location: bt-casse-dashboard.php?error=1');
}
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
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>