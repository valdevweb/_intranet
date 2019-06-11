<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}

require "../../functions/gazette.fn.php";
require "../../functions/stats.fn.php";



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------

$descr="page historique et recherche des gazettes " ;
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);
//----------------------------------------


//----------------------------------------------
// css dynamique
//----------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";




$errors=[];
$success=[];


//upload des gazette renvoi sur cette page
if (!empty($_GET['type'])) {
	if ($_GET['type'] === 'success') {
		$message = 'La gazette a été uploadé avec succés';
	}
}

function getGazette($pdoBt, $doccode)
{
	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE id_doc_type = :id_doc_type ORDER BY date DESC LIMIT 5");
	$req->execute([
		':id_doc_type'		=>$doccode
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}




function getGazetteSpeciale($pdoBt, $doccode, $dateStart,$dateEnd)
{
	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date BETWEEN :dateStart AND :dateEnd AND id_doc_type = :id_doc_type ORDER BY date DESC");
	$req->execute([
		':dateStart'	=> $dateStart,
		':dateEnd'	=> $dateEnd,
		':id_doc_type'		=>$doccode
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function getGazetteSuivi($pdoBt, $doccode, $dateEnd)
{

	$req=$pdoBt->prepare("SELECT * , DATE_FORMAT(date,'%d/%m/%Y') AS deb, DATE_FORMAT(date_fin,'%d/%m/%Y') AS fin FROM gazette WHERE id_doc_type=:id_doc_type AND  date_fin>= :today ORDER BY date DESC,file DESC");
	$req->execute(array(
		':today'	=> $dateEnd,
		':id_doc_type'  =>2

	));

	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}
//recherche gazette
if (isset($_POST['submit']))
{
	$linkSearch="";
	if ( !preg_match ( "/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/" , $_POST['date'] ) )
	{
		$linkSearch="merci de saisir une date valide";
		die;
	}
	$date=$_POST['date'];
	$req=$pdoBt->prepare("SELECT * FROM gazette where date= :date");
	$req->execute(array(
		':date'		=>$date
	));
	if($result=$req->fetchAll(PDO::FETCH_ASSOC))
	{
		foreach ($result as $value)
		{

			$file=$value['file'];
			$linkSearch.="<p><a href='http://172.30.92.53/".$version ."upload/gazette/" . $file . "'>" .$file ."</a></p>";
		}
		// $file=$result['file'];

		// $linkSearch= "<a href='http://172.30.92.53/".$version ."upload/gazette/" . $file . "'>" .$file ."</a>";
	}
	else
	{
		echo "pas de resultat";
	}
	unset( $_POST);

}
$today=new DateTime();
$monday=clone $today;
$monday->modify('Monday this week');
$saturday=clone $today;
$saturday->modify('saturday this week');

$quotidienne=getGazette($pdoBt, 1);
$suiviCata=getGazetteSuivi($pdoBt,2, $today->format('Y-m-d'));
$speciale=getGazetteSpeciale($pdoBt, 8, $monday->format('Y-m-d'), $saturday->format('Y-m-d'));

$opp="D:\www\intranet\opportunites\index.html";
if (file_exists($opp))
{
	$oppLastMaj=date ('Y-m-d', filemtime($opp));
	setlocale(LC_TIME, 'fr_FR.utf-8','fra');
	$oppLastMaj =  strftime("%A %d %B %Y", strtotime($oppLastMaj));
	$oppLastMaj = utf8_encode($oppLastMaj);

}



	// echo "<pre>";
	// print_r($monday);
	// echo '</pre>';
	// echo "<pre>";
	// print_r($saturday);
	// echo '</pre>';

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<div class="container-fluid bg-white">
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-5 ">L'essentielle des gazettes</h1>
		</div>
	</div>
	<div class="row mx-5 ">
		<div class="col row-eq-height">
			<div class="row mb-3  p-3 gaz-list quot  full-width">
				<div class="col-auto">
					<img src="../img/gazette/gaz-quotidienne.png">
				</div>
				<div class="col " >
					<p class="heavy">Les 5 dernières gazettes : </p>

					<?php if (!empty($quotidienne)): ?>
						<ul>
							<?php foreach ($quotidienne as $gazQuotidienne): ?>
								<li><a href="#"><?= $gazQuotidienne['file'] ?></a></li>
							<?php endforeach ?>
						</ul>
					<?php endif ?>
				</div>
			</div>



		</div>
		<!-- <div class="col-1"></div> -->
		<div class="col">
			<div class="row mb-3 p-3 gaz-list suivi-livraison">
				<div class="col-auto">
					<img src="../img/gazette/gaz-suivi-livraison.png">
				</div>
				<div class="col " >
					<p class="heavy">Gazettes suivi livraison catalogue(s) en cours :</p>
					<?php if (!empty($suiviCata)): ?>

						<?php foreach ($suiviCata as $suivi): ?>
							<?php $fileTitle=explode('.xls', $suivi['file']);?>
							<ul><li class="heavy"><a href="#"><?= $fileTitle[0] .' ('.$suivi['deb'].' au '.$suivi['fin'].') : ' ?></a></li></ul>
							<div class="detail"><?= $suivi['title'] ?></div>

						<?php endforeach ?>
						<?php else: ?>
							<p>Aucune gazette en cours</p>

						<?php endif ?>

					</div>
				</div>

			</div>

		</div>



		<div class="row mx-5 mt-3">
			<div class="col row-eq-height">
				<div class="row mb-3 p-3 gaz-list alerte-promo full-width">
					<div class="col-auto">
						<img src="../img/gazette/gaz-alerte-promo.png">
					</div>
					<div class="col">
						<ul>
							<li><a href="http://172.30.92.53/OPPORTUNITES/index.html">l'alerte promo</a> <br>date de la dernière mise à jour : <?=$oppLastMaj?></li>
						</ul>

					</div>
				</div>
			</div>
			<!-- <div class="col-1"></div> -->
			<div class="col">
				<div class="row mb-3 p-3 gaz-list speciale">
					<div class="col-auto">
						<img src="../img/gazette/gaz-speciale.png">
					</div>
					<div class="col" >
						<?php if (!empty($speciale)): ?>
							<ul>
								<?php foreach ($speciale as $spe): ?>
									<?php $fileTitle=explode('.xls', $spe['file']);?>

									<li><a href="#"><?= $fileTitle[0] ?></a></li>

								<?php endforeach ?>
							</ul>
							<?php else: ?>
								<p>Aucune gazette spéciale</p>
							<?php endif ?>
						</div>
					</div>

				</div>
			</div>










			<!-- formulaire de recherche -->
			<div class="row">
				<h4 class="light-blue-text text-darken-2" id="search">Chercher une gazette par date</h4>
				<form method="post" action="gazette.php#result" class="w3-container bg-white">
					<div class="col l2"></div>
					<div class="col l4">

						<label class="w3-text-grey" for="date">Selectionnez la date à partir du 1er décembre 2017</label>
						<input type="date" class="w3-input w3-border" name="date" id="date" >
					</div>

					<div class="col l4 align-left">

						<br>

						<button class="btn waves-effect waves-light orange darken-3 align-right" type="submit" name="submit" >Rechercher</button>
					</div>
					<div class="col l2"></div>


				</form>
				<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>

			</div>
			<div class="row" id="result">
				<?php if(isset($linkSearch)){echo "<p>Resultat(s) : </p>". $linkSearch ;} ?>
			</div>
			<!-- END formulaire de recherche -->
		</div><!-- END container -->




		<?php require '../view/_footer-bt.php'; ?>