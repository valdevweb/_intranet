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


require "../../Class/LitigeDao.php";

function createFileLink($filelist){
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.URL_UPLOAD.'/litiges/'.$filelist[$i].'" class="link-main-blue"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}


$errors=[];
$success=[];


$litigeDao=new LitigeDao($pdoLitige);

$ddeOuv=$litigeDao->getDdeOuverture(0);
if(isset($_POST['submit'])){
	$ddeOuv=$litigeDao->getDdeOuverture($_POST['etat']);

}



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
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row justify-content-center">
					<div class="col-auto">
						<div class="form-group">
							<label for="etat">Afficher les demandes :</label>
							<select class="form-control" name="etat" id="etat">
								<?php foreach ($etatAr as $keyEtat => $value): ?>
									<option value="<?=$keyEtat?>" <?=isset($_POST['etat']) && ($_POST['etat']=="$keyEtat") ? "selected" :""?>><?=$etatAr[$keyEtat]?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col-auto mt-4 pt-2">
						<button class="btn btn-primary" name="submit">Filtrer</button>

					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th>N°</th>
						<th>Magasin</th>
						<th>Date demande</th>
						<th>Message</th>
						<th>Etat</th>
						<th class="text-center">Répondre</th>
						<th class="text-center">Créer</th>
						<th class="text-right">Dossier</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($ddeOuv as $key => $ouv): ?>
					<tr>
						<td class="text-right"><?=$ouv['id_ouv']?></td>
						<td><?=$ouv['deno']?></td>
						<td><?=$ouv['datesaisie']?></td>
						<td><?=substr(str_replace('<br />',', ', $ouv['msg']),0, 50) .'...';?></td>
						<td class="<?=$classAr[$ouv['etat']]?>"><?=$etatAr[$ouv['etat']]?></td>
						<td class="text-center"><a href="bt-ouv-traitement.php?id=<?=$ouv['id_ouv']?>" ><i class="far fa-comments"></i></a></td>
						<td class="text-center"><a href="bt-ouv-saisie.php?id_ouv=<?=$ouv['id_ouv']?>&galec=<?=$ouv['galec']?>" ><i class="fas fa-folder-plus"></i></a></td>
						<td class="text-center"><a href="bt-detail-litige.php?id=<?=$ouv['id_dossier_litige']?>"><?=$ouv['dossier']?></a></td>

						</tr>
					<?php endforeach ?>


				</tbody>
			</table>

		</div>
	</div>






	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>