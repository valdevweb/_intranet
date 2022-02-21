<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

require '../../Class/litiges/LitigeDao.php';
require '../../Class/MagHelpers.php';

$arMagOcc=MagHelpers::getListMagOcc($pdoMag);


function search($pdoMag){
	$req=$pdoMag->prepare("SELECT * FROM mag  WHERE concat(deno, galec, id, ville) LIKE :search ORDER BY deno");
	$req->execute(array(
		':search' =>'%'.$_POST['search_strg'] .'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

if(isset($_POST['search_form'])){
	$magList=search($pdoMag);
}


if(isset($_GET['galec'])){
	$litigeDao=new LitigeDao($pdoLitige);
	$listLitige=$litigeDao->getLitigesByGalec($_GET['galec']);
}

if(isset($listLitige[0]['btlec'])){
	$codeBt=$listLitige[0]['btlec'];
	$codeGalec=$_GET['galec'];

	include 'ca/01-caphp.php';

}




//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$backUrl="";
if(isset($_SERVER['HTTP_REFERER'])){
$backUrl=$_SERVER['HTTP_REFERER'];

}

$coutTotal=0;
$valoTotal=0;

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
			<p class="text-right"><a href="<?=$backUrl?>" class="btn btn-primary">Retour</a></p>
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
					echo '<a href="?galec='.$mag['galec'].'">'.$mag['deno'] .' - '. $mag['galec'] .'</a><br>';
				}
				echo '</div><div class="col"></div></div>';


			}
			?>
		</div>
		<div class="col-1"></div>
	</div>
	<div class="row">
		<div class="col">
			<h3 class="text-center text-main-blue my-3"><?=$listLitige[0]['deno']?></h3>
			<h4 class="text-main-blue heavy my-3">Chiffres d'affaire :</h4>

			<div class="row">
				<div class="col-auto">
					<?php if(isset($listLitige[0]['btlec'])):?>
						<?php include 'ca/10-cahtml.php'; ?>
					<?php endif	?>
				</div>
			</div>
			<?php if(isset($listLitige[0]['btlec'])):?>
				<div class="row">
					<div class="col">
						<h4 class="text-main-blue heavy my-3">Litiges :</h4>
						<table class="table light-shadow table-bordered ">
							<thead class="thead-dark">
								<tr>
									<th class="align-top">N°</th>
									<th class="align-top">Date</th>
									<th class="align-top">Service</th>
									<th class="align-top">Typologie</th>
									<th class="align-top">Imputation</th>
									<th class="align-top">Statut</th>
									<th class="align-top">Valorisation magasin</th>
									<th class="align-top">Analyse</th>
									<th class="align-top">Réponse</th>
									<th class="align-top">Coût BTlec</th>
								</tr>
							</thead>
							<tbody>


								<?php foreach ($listLitige as $litige): ?>
									<?php
									$cout=$litige['mt_transp']+$litige['mt_assur']+$litige['mt_fourn']+$litige['mt_mag'];
									$coutTotal=$coutTotal+$cout;
									$cout=number_format((float)$cout,2,'.','');
									?>
									<tr>
										<td><a href="bt-detail-litige.php?id=<?=$litige['id']?>"><?=$litige['dossier']?></a></td>
										<td class="nowrap"><?=$litige['datecrea']?></td>
										<td><?=$litige['gt']?></td>
										<td><?=$litige['typo']?></td>
										<td><?=$litige['imputation']?></td>
										<td><?=$litige['etat']?></td>
										<td class="text-right" ><?=$litige['valo']?></td>
										<td><?=$litige['analyse']?></td>
										<td><?=$litige['conclusion']?></td>
										<td class="text-right"><?=$cout?> &euro;</td>
									</tr>
									<?php $valoTotal=$valoTotal+$litige['valo']; ?>

								<?php endforeach?>
								<tr>
									<td  class="no-border" colspan="2">TOTAUX</td>
									<td  class="text-right no-border" colspan="5"><?=$valoTotal?></td>
									<td class="text-right no-border order" colspan="3"><?=$coutTotal?></td>
								</tr>

							</tbody>
						</table>


					</div>
				</div>
				<div class="row">
					<div class="col text-right pb-5">
						<a href="print-stat-litige-mag.php?galec=<?=$_GET['galec']?>" class="btn btn-primary" target="_blank"><i class="fas fa-print pr-3"></i>Imprimer</a>
					<?php else:?>
						<h5 class="text-red text-center heavy my-5"><i class="fas fa-info-circle pr-3"></i>Pas de dossier litige pour ce magasin</h5>
					<?php endif	?>
				</div>
			</div>




			<!-- ./container -->
		</div>


		<?php
		require '../view/_footer-bt.php';
	?>