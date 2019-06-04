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



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


//--------------------------------------------------------------
//	CALCUL VALO => maj db pour avoir valo par ligne d'article
//--------------------------------------------------------------
/*

cas général

(tarif/qte_cde) *qte_litige
=tarif unitaire * qte litige

si id_reclamation =5 soit inversion de produit
le tarif inversé est ramené à l'unité au moment de l'insertion en db qd article trouvé dans statventelitige
((tarif /qte_cde)* qte_litige) - (inv_tarif *inv_qte)

si id_reclamation = 6 = excédent =>
-(tarif/qte_cde) *qte_litige

si inv_palette : pas besoin de calculer car qte_cde=qte_litige
donc recopie de tarif dans tarif
 */


function readDetails($pdoLitige){
	$req=$pdoLitige->prepare("SELECT id, id_reclamation, qte_cde,tarif,qte_litige, inv_qte, inv_tarif,valo_line, inv_palette FROM details ORDER BY id_reclamation");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function updateValoLine($pdoLitige,$valoLine,$id){
	$req=$pdoLitige->prepare("UPDATE details SET valo_line= :valo_line WHERE id= :id");
	$req->execute([
		':valo_line'		=>$valoLine,
		':id'				=>$id
	]);
	return $req->rowCount();
}


$majOk=0;

if(isset($_POST['submit'])){

	$details=readDetails($pdoLitige);

	foreach ($details as $line) {
		if(!empty($line['inv_palette']))
		{
			$do=updateValoLine($pdoLitige, $line['tarif'], $line['id']);
		}
		elseif($line['id_reclamation']==5){
			$valoLine=($line['tarif']/$line['qte_cde']* $line['qte_litige'])- ($line['inv_tarif']*$line['inv_qte']);
			$do=updateValoLine($pdoLitige, $valoLine, $line['id']);
		}
		elseif($line['id_reclamation']==6){
			$valoLine=-($line['tarif']/$line['qte_cde']* $line['qte_litige']);
			$do=updateValoLine($pdoLitige, $valoLine, $line['id']);
		}
		else
		{
			$valoLine=($line['tarif']/$line['qte_cde']* $line['qte_litige']);
			$do=updateValoLine($pdoLitige, $valoLine, $line['id']);
		}
		if($do!=1){
			$errors[]='impossible de calculer la valo pour la ligne' .$line['id'];
		}
		else{
			$majOk++;

		}

	}


		$success[]=$majOk. " ligne ont été mises à jour";



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

	<form method="post">
		<button name="submit">maj</button>
	</form>
	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>