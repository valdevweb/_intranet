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

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//  on récupère le info flash en cours et future donc date de fin supérieure ou égale à aujourd'hui et tout les états de valid  0 (statut par défaut, non validé) ou 1(validé)   2 (réfusé)

function getFlashList($pdoBt)
{
	$req=$pdoBt->prepare("SELECT title, flash.id as flashid, content, vignette, pj, DATE_FORMAT(date_start, '%d-%m-%Y') as datestart,DATE_FORMAT(date_end, '%d-%m-%Y') as dateend , CONCAT (prenom, ' ', nom) as fullname, valid FROM flash LEFT JOIN btlec ON created_by=btlec.id_webuser WHERE date_end >= :today ORDER BY date_start");
	$req->execute(array(
		':today'	=>date('Y-m-d 00:00:00')
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$flashList=getFlashList($pdoBt);
$statArr=['en attente', 'validé','refusé'];

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
	<h1 class="text-main-blue py-5 ">Validation /modification de flash info</h1>

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
					<table class="table table-bordered">
					<thead class="thead-dark">
						<tr>
							<th class="align-top">Titre</th>
							<th class="align-top">Contenu</th>
							<th class="align-top">Vignette + document</th>
							<th class="align-top">Date de début</th>
							<th class="align-top">Date de fin</th>
							<th class="align-top">Auteur</th>
							<th class="align-top">Etat</th>
							<th class="align-top">Modifier / Valider</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($flashList as $flash)
						{
							echo '<tr>';
							echo '<td>'.$flash['title'].'</td>';
							echo '<td>'.$flash['content'].'</td>';
							echo '<td><a href="'.UPLOAD_DIR.'/flash/'.$flash['pj'].'" target="_blank"><img src="'.UPLOAD_DIR.'/flash/'.$flash['vignette'].'"></a></td>';
							echo '<td>'.$flash['datestart'].'</td>';
							echo '<td>'.$flash['dateend'].'</td>';
							echo '<td>'.$flash['fullname'].'</td>';
							echo '<td>'.$statArr[$flash['valid']].'</td>';
							echo '<td><a href="flash-ok.php?id='.$flash['flashid'].'" class="green-link">Valider</a><br><a href="flash-ko.php?id='.$flash['flashid'].'" class="red-link">Refuser</a><br><a href="flash-modif.php?id='.$flash['flashid'].'" class="nounderline-link">Modifier</a></td>';
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