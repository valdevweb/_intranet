<?php

 // require('../../config/pdo_connect.php');
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



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getMagName($pdoBt)
{
	$req=$pdoBt->prepare("SELECT mag FROM sca3 WHERE galec=:galec");
	$req->execute(array(
		':galec'		=>$_SESSION['id_galec']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function searchEan($pdoQlik)
{
	// if(explode($_POST['search_ean']))
	// {

	// }

	$req=$pdoQlik->prepare("SELECT id, `GESSICA.CodeArticle` as article,`GESSICA.CodeDossier` as dossier,`GESSICA.PANF` as panf,`GESSICA.PFNP` as pfnp,`GESSICA.LibelleArticle` as descr, `GESSICA.PCB` as pcb,`GESSICA.NomFournisseur` as fournisseur,`GESSICA.Gencod` as ean FROM basearticles WHERE `GESSICA.Gencod` LIKE :ean");
	$req->execute(array(
		':ean'	=>'%'.$_POST['search_ean'].'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$foundProd=searchEan($pdoQlik);
$string='123456,1595735, 1245545';
$eanAr=explode(',',$string);
$nbEan=count($eanAr);
if($nbEan==1)
{
	echo 'ean unique';
}
else
{
	for ($i=0; $i < $nbEan ; $i++)
	{
			echo str_replace(' ','',$eanAr[$i]) .'<br>';
		}
}
//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$magtxt="";
if($_SESSION['type']=='btlec')
{
	$mag=getMagName($pdoBt);
	$magtxt="<span class='text-reddish'>pour ".$mag['mag']."</span>";

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
	<h1 class="text-main-blue py-5 ">Saisie libre <?=$magtxt?></h1>

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
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col bg-alert bg-alert-primary">
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" id="search">
				<!-- start row -->
				<!-- start row -->
				<div class="row pb-3">
					<div class="col">
						<p>EAN du produit ou des produits (merci de séparer les EAN par une virgule): </p>
					</div>
				</div>
				<!-- ./row -->

				<div class="row pl-5">
					<div class="col">
						<div class="form-group">
							<input type="text" class="form-control" name="search_ean" required>
						</div>
					</div>
					<div class="col">
						<p class="text-left"><button class="btn btn-primary" type="submit" name="submit">Rechercher</button></p>
						<div id="waitun"></div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" id="submit">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Article</th>
							<th>Dossier</th>
							<th>EAN</th>
							<th>PCB</th>
							<th>PANF</th>
							<th>Désignation</th>
							<th>Fournisseur</th>
							<th><i class="fas fa-times-circle"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if(!empty($foundProd)){
							foreach ($foundProd as $prod)
							{
								echo '<tr>';
								echo '<td>'.$prod['article'].'</td>';
								echo '<td>'.$prod['dossier'].'</td>';
								echo '<td>'.$prod['ean'].'</td>';
								echo '<td>'.$prod['pcb'].'</td>';
								echo '<td>'.$prod['panf'].'</td>';
								echo '<td>'.$prod['descr'].'</td>';
								echo '<td>'.$prod['fournisseur'].'</td>';
								echo '<td>'.$prod['id'].'</td>';

								echo '</tr>';
							}
						}
						else
						{
								echo '<tr>';
								echo '<td colspan="8">Aucun résultat trouvé. Souhaitez vous saisir la totalité des informations</td>';
								echo '</tr>';


						}
						?>

					</tbody>
				</table>
				<p class="text-right"><button class="btn btn-primary">Sélectionner</button></p>
			</form>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>