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
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function search($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM sca3  WHERE concat(mag,sca3.galec,btlec, city) LIKE :search AND mag NOT LIKE '%*%'");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getMagLitiges($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT dossiers.id as id,dossiers.galec as galec, dossier,DATE_FORMAT(date_crea,'%d-%m-%Y')as datecrea, typo, imputation, etat, tablegt.gt, valo, analyse, conclusion, mt_transp, mt_assur, mt_fourn, mt_mag, btlec.sca3.mag, btlec.sca3.btlec, btlec.sca3.centrale  FROM dossiers
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN typo ON dossiers.id_typo=typo.id
		LEFT JOIN imputation ON dossiers.id_imputation=imputation.id
		LEFT JOIN gt as tablegt ON dossiers.id_gt=tablegt.id
		LEFT JOIN etat ON dossiers.id_etat=etat.id
		LEFT JOIN gt ON dossiers.id_gt=gt.id
		LEFT JOIN analyse ON dossiers.id_analyse=analyse.id
		LEFT JOIN conclusion ON dossiers.id_conclusion=conclusion.id
		WHERE dossiers.galec= :galec");
	$req->execute(array(
		':galec'	=>$_GET['galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}





if(isset($_POST['search_form']))
{
	$magList=search($pdoBt);
}

function getFinance($pdoQlik, $btlec, $year)
{
	$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
	$req->execute(array(
		':btlec' =>$btlec,
		':year'	=>$year
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getSumDeclare($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(valo) as sumValo FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

function getMtMag($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as sumMtMag FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getCoutTotalYear($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as mtMag, sum(mt_assur) as mtassur, sum(mt_transp) as mttransp, sum(mt_fourn) as mtfourn FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}



$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));



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

	<div class="row py-3">
		<div class="col">
			<p class="text-right"><a href="bt-litige-encours.php" class="btn btn-primary">Retour</a></p>
		</div>
	</div>
	<h1 class="text-main-blue ">Réclamations par magasin</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row mt-5">
		<div class="col-1"></div>
		<div class="col border shadow py-5">
			<p class="text-orange">Rechercher un magasin :</p>
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="form-inline">
				<div class="form-group">
					<input class="form-control mr-5 pr-5" placeholder="nom de magasin, ville, panonceau galec, btlec" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
				</div>
				<button class="btn btn-primary mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
				<button class="btn btn-black" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>
			</form>
		</div>
		<div class="col-1"></div>
	</div>

	<div class="row mt-3">
		<div class="col-1"></div>
		<div class="col">
			<?php
			if(isset($magList) && !empty($magList))
			{
				echo '<div class="row"><div class="col">';
				echo "<p>Veuillez sélectionner un magasin en cliquant sur son nom : </p>";
				echo '</div></div>';
				echo '<div class="row"><div class="col"></div><div class="col-auto">';
				foreach ($magList as $mag)
				{
					echo '<a href="?galec='.$mag['galec'].'">'.$mag['mag'] .' - '. $mag['galec'] .'</a><br>';
				}
				echo '</div><div class="col"></div></div>';


			}
			?>
		</div>
		<div class="col-1"></div>
	</div>
	<div class="row">
		<div class="col">
			<?php
			if(isset($_GET['galec']))
			{
				$listLitige=getMagLitiges($pdoLitige);
				// on vérifie que le mag a au moins un litige
				if(isset($listLitige[0]['btlec']))
				{

					$financeN=getFinance($pdoQlik,$listLitige[0]['btlec'],$yearN);
					$financeNUn=getFinance($pdoQlik,$listLitige[0]['btlec'],$yearNUn);
					$financeNDeux=getFinance($pdoQlik,$listLitige[0]['btlec'],$yearNDeux);
					// $reclameN=getSumDeclare($pdoBt,$listLitige[0]['galec'],$yearN);
					$reclameN=getSumDeclare($pdoLitige,$listLitige[0]['galec'],$yearN);
					$reclameNUn=getSumDeclare($pdoLitige,$listLitige[0]['galec'],$yearNUn);
					$reclameNDeux=getSumDeclare($pdoLitige,$listLitige[0]['galec'],$yearNDeux);

					$rembourseN=getMtMag($pdoLitige,$listLitige[0]['galec'],$yearN);
					$rembourseNUn=getMtMag($pdoLitige,$listLitige[0]['galec'],$yearNUn);
					$rembourseNDeux=getMtMag($pdoLitige,$listLitige[0]['galec'],$yearNDeux);

					$coutN=getCoutTotalYear($pdoLitige,$listLitige[0]['galec'],$yearN);
					$coutN=$coutN['mtMag']+$coutN['mtfourn']+$coutN['mttransp']+$coutN['mtassur'];
					$coutNUn=getCoutTotalYear($pdoLitige,$listLitige[0]['galec'],$yearNUn);
						$coutNUn=$coutNUn['mtMag']+$coutNUn['mtfourn']+$coutNUn['mttransp']+$coutNUn['mtassur'];

					$coutNDeux=getCoutTotalYear($pdoLitige,$listLitige[0]['galec'],$yearNDeux);

					$coutNDeux=$coutNDeux['mtMag']+$coutNDeux['mtfourn']+$coutNDeux['mttransp']+$coutNDeux['mtassur'];





					$nbLitiges=count($listLitige);
					$valoTotal=0;
					echo '<h3 class="text-center text-main-blue my-3">'.$listLitige[0]['mag'] .'</h3>';
					echo '<h4 class="text-main-blue heavy my-3"> Chiffres d\'affaire :</h4>';
					echo '<div class="row">';
					echo '<div class="col-lg-2"></div>';
					echo '<div class="col">';
					echo '<table class="table text-right table-bordered light-shadow">';
					echo '<thead class="thead-dark">';
					echo '<th></th>';
					echo '<th>'.$yearN.'</th>';
					echo '<th>'.$yearNUn .'</th>';
					echo '<th>'.$yearNDeux .'</th>';
					echo '</thead>';
					echo '<tbody>';
					echo '<tr>';
					echo '<td class="text-main-blue heavy"> Chiffres d\'affaire :</td>';
					echo '<td>'.number_format((float)$financeN['CA_Annuel'],2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$financeNUn['CA_Annuel'],2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$financeNDeux['CA_Annuel'],2,'.',' ').'&euro;</td>';
					echo '</tr>';

					echo '<tr>';
					echo '<td class="text-main-blue heavy"> Montant réclamé :</td>';
					echo '<td>'.number_format((float)$reclameN['sumValo'],2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$reclameNUn['sumValo'],2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$reclameNDeux['sumValo'],2,'.',' ').'&euro;</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td class="text-main-blue heavy"> Montant remboursé :</td>';
					echo '<td>'.number_format((float)$rembourseN['sumMtMag'],2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$rembourseNUn['sumMtMag'],2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$rembourseNDeux['sumMtMag'],2,'.',' ').'&euro;</td>';
					echo '</tr>';

						echo '<td class="text-main-blue heavy"> Coût BTlec</td>';
					echo '<td>'.number_format((float)$coutN,2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$coutNUn,2,'.',' ').'&euro;</td>';
					echo '<td>'.number_format((float)$coutNDeux,2,'.',' ').'&euro;</td>';
					echo '</tr>';

					echo '</tbody>';

					echo '</table>';
					echo '</div>';
					echo '<div class="col-lg-2"></div>';
					echo '</div>';

					echo '<h4 class="text-main-blue heavy my-3">Litiges :</h4>';

					echo '<table class="table light-shadow table-bordered ">';
					echo '<thead class="thead-dark">';
					echo '<tr>';
					echo '<th class="align-top">N°</th>';
					echo '<th class="align-top">Date</th>';
					echo '<th class="align-top">Service</th>';
					echo '<th class="align-top">Typologie</th>';
					echo '<th class="align-top">Imputation</th>';
					echo '<th class="align-top">Statut</th>';
					echo '<th class="align-top">Valorisation magasin</th>';
					echo '<th class="align-top">Analyse</th>';
					echo '<th class="align-top">Réponse</th>';
					echo '<th class="align-top">Coût BTlec</th>';
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';


					$coutTotal=0;
					foreach ($listLitige as $litige)
					{
						$cout=$litige['mt_transp']+$litige['mt_assur']+$litige['mt_fourn']+$litige['mt_mag'];
						$coutTotal=$coutTotal+$cout;
						$cout=number_format((float)$cout,2,'.','');
						echo '<tr>';
						echo '<td><a href="bt-detail-litige.php?id='.$litige['id'].'">'.$litige['dossier'].'</a></td>';
						echo '<td>'.$litige['datecrea'].'</td>';
						echo '<td>'.$litige['gt'].'</td>';
						echo '<td>'.$litige['typo'].'</td>';
						echo '<td>'.$litige['imputation'].'</td>';
						echo '<td>'.$litige['etat'].'</td>';
						echo '<td class="text-right" >'.$litige['valo'].'</td>';
						echo '<td>'.$litige['analyse'].'</td>';
						echo '<td>'.$litige['conclusion'].'</td>';
						echo '<td class="text-right">'.$cout.' &euro;</td>';
						echo '</tr>';
						$valoTotal=$valoTotal+$litige['valo'];
					}

					echo '<tr>';
					echo '<td  class="no-border" colspan="2">TOTAUX</td>';
					echo '<td  class="text-right no-border" colspan="5">'.$valoTotal.'</td>';
					echo '<td class="text-right no-border order" colspan="3">'.$coutTotal.'</td>';
					echo '</tr>';

					echo '</tbody>';
					echo '</table>';


					echo '</div>';
					echo '</div>';
					echo '<div class="row">';
					echo '<div class="col text-right pb-5">';
					echo '<a href="print-stat-litige-mag.php?galec='.$_GET['galec'].'" class="btn btn-primary" target="_blank"><i class="fas fa-print pr-3"></i>Imprimer</a>';

				}
				else
				{
					echo '<h5 class="text-red text-center heavy my-5"><i class="fas fa-info-circle pr-3"></i>Pas de dossier litige pour ce magasin</h5>';
				}




			}


			?>
		</div>
	</div>


	<div class="row">
		<div class="col">
			<p><a href="stats-mini.php"><i class="fas fa-external-link-alt pr-3"></i>Nb de fréquentations des pages litiges par jour</a></p>
		</div>
	</div>


	<!-- ./container -->
</div>


<?php
require '../view/_footer-bt.php';
?>