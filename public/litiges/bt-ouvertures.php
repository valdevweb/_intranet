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
// lien mail mis dans session
unset($_SESSION['goto']);

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
//			FONCTION
//------------------------------------------------------
function getWaiting($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT ouv.id as id_ouv, DATE_FORMAT(date_saisie, '%d-%m-%Y') as datesaisie, msg, pj, mag, btlec, etat,ouv.galec, dossiers.dossier,  dossiers.id as id_dossier_litige FROM ouv
		LEFT JOIN btlec.sca3 ON ouv.galec=btlec.sca3.galec
		LEFT JOIN dossiers ON ouv.id_litige=dossiers.id
		ORDER BY etat, date_saisie");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$waiting=getWaiting($pdoLitige);

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-main-blue"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$etatAr=['en cours','accepté','refusé'];
$classAr=['text-red heavy','text-dark-grey','text-dark-grey'];
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
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5 ">Demandes d'ouvertures de dossiers</h1>
			<p><i class="fas fa-info-circle text-main-blue pr-3"></i>Pour répondre à une demande, veuillez cliquer sur le bouton répondre <i class="far fa-comments text-main-blue px-2"></i>Le bouton créer <i class="fas fa-folder-plus text-main-blue px-2"></i> vous permettra d'accéder à la saisie libre du dossier</p>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
			<div class="spinner-border" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row">
		<div class="col">
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th>N°</th>
						<th>Magasin</th>
						<th>Date</th>
						<th>Message</th>
						<th>Etat</th>
						<th class="text-center">Répondre</th>
						<th class="text-center">Créer</th>
						<th class="text-right">Dossier</th>
					</tr>
				</thead>
				<tbody>


					<?php
					foreach ($waiting as $wait)
					{
						$msg=str_replace('<br />',', ', $wait['msg']);
						$msg=substr($msg,0, 50) .'...';

						echo '<tr>';
						echo '<td class="text-right">'.$wait['id_ouv'].'</td>';
						echo '<td>'.$wait['mag'].'</td>';
						echo '<td>'.$wait['datesaisie'].'</td>';
						echo '<td>'.$msg.'</td>';
						echo '<td class="'.$classAr[$wait['etat']].'">'.$etatAr[$wait['etat']].'</td>';
						echo '<td class="text-center"><a href="bt-ouv-traitement.php?id='.$wait['id_ouv'].'" ><i class="far fa-comments"></i></a></td>';
						echo '<td class="text-center"><a href="bt-ouv-saisie.php?id_ouv='.$wait['id_ouv'].'&galec='.$wait['galec'].'" ><i class="fas fa-folder-plus"></i></a></td>';
						echo '<td class="text-center"><a href="bt-detail-litige.php?id='.$wait['id_dossier_litige'].'">'.$wait['dossier'].'</a></td>';

						echo '</tr>';


					}

					?>

				</tbody>
			</table>

		</div>
	</div>






	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>