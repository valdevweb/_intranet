<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

require "../../functions/gazette.fn.php";
require "../../functions/stats.fn.php";

require_once '../../Class/OpportuniteDAO.php';


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

function getGazette($pdoBt, $doccode){
	$req=$pdoBt->prepare("SELECT *, month(date) as month, day(date) as day, year(date) as year FROM gazette WHERE id_doc_type = :id_doc_type ORDER BY date DESC LIMIT 5");
	$req->execute([
		':id_doc_type'		=>$doccode
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


function getGazetteSpeciale($pdoBt, $doccode, $dateStart,$dateEnd){
	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date BETWEEN :dateStart AND :dateEnd AND id_doc_type = :id_doc_type ORDER BY date DESC");
	$req->execute([
		':dateStart'	=> $dateStart,
		':dateEnd'	=> $dateEnd,
		':id_doc_type'		=>$doccode
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getGazetteSuivi($pdoBt, $doccode, $dateEnd){

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
	if ( !preg_match ( "/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/" , $_POST['start'] ) )
	{
		$linkSearch="merci de saisir un début de période valide";
		die;
	}
	if ( !preg_match ( "/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/" , $_POST['end'] ) )
	{
		$linkSearch="merci de saisir une fin de période valide";
		die;
	}

	$req=$pdoBt->prepare("SELECT * FROM gazette where date BETWEEN :start AND :end");
	$req->execute(array(
		':start'		=>$_POST['start'],
		':end'		=>$_POST['end']
	));
	if($result=$req->fetchAll(PDO::FETCH_ASSOC))
	{
		$linkSearch="<p class='pl-5'>";
		foreach ($result as $value){
			$linkSearch.="<a href='".URL_UPLOAD."gazette/" . $value['file'] . "'>" .$value['file'] ."</a><br>";
		}
		$linkSearch.="</p>";
	}
	else
	{
		echo "pas de resultat";
	}
	unset( $_POST);

}
$today=new DateTime();
$todayInput=$today->format('Y-m-d');
$monday=clone $today;
$monday->modify('Monday this week');
$saturday=clone $today;
$saturday->modify('saturday this week');
$months= array('','janvier', 'février', 'mars', 'avril', 'mai', 'juin','juillet', 'août', 'septembre', 'octobre','novembre','décembre');

$quotidienne=getGazette($pdoBt, 1);
$suiviCata=getGazetteSuivi($pdoBt,2, $today->format('Y-m-d'));
$speciale=getGazetteSpeciale($pdoBt, 8, $monday->format('Y-m-d'), $saturday->format('Y-m-d'));


$oppDao=new OpportuniteDAO($pdoBt);
$listActiveOpp=$oppDao->getActiveOpp();


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

	<div class="row">
		<div class="col-xl-1"></div>

		<div class="col">
			<!-- ligne 1 -->
			<div class="row mx-5 ">
				<div class="col row-eq-height">
					<div class="row mb-3  p-3 gaz-list quot  full-width">
						<div class="col" >
							<img class="d-inline-block align-top" src="../img/gazette/gaz-quotidienne.png">
							<div class="d-inline-block pl-3" id="quot-list">
								<p class="heavy" >Les 5 dernières gazettes : </p>
								<?php if (!empty($quotidienne)): ?>
									<ul>
										<?php foreach ($quotidienne as $gazQuotidienne): ?>

											<li><a href="<?=URL_UPLOAD?>gazette/<?= $gazQuotidienne['file']?>">la gazette du <?= $gazQuotidienne['day'] . ' '. $months[$gazQuotidienne['month']] . ' '. $gazQuotidienne['year']?></a></li>
										<?php endforeach ?>
									</ul>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>

				<!-- <div class="col-1"></div> -->
				<div class="col">
					<div class="row mb-3 p-3 gaz-list suivi-livraison">
						<div class="col">
							<img class="d-inline-block align-top" src="../img/gazette/gaz-suivi-livraison.png">
							<div class="d-inline-block pl-3" id="suivi-livraison-list">
								<p class="heavy">Gazettes suivi livraison catalogue(s) en cours :</p>
								<?php if (!empty($suiviCata)): ?>

									<?php foreach ($suiviCata as $suivi): ?>
										<?php $fileTitle=explode('.xls', $suivi['file']);?>
										<ul><li class="heavy"><a href="<?=URL_UPLOAD?>gazette/<?=$suivi['file']?>"><?= $fileTitle[0] .' <br><i>('.$suivi['deb'].' au '.$suivi['fin'].') : ' ?></i></a></li></ul>
										<div class="detail"><?= $suivi['title'] ?></div>

									<?php endforeach ?>
									<?php else: ?>
										<p>Aucune gazette en cours</p>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ligne 2 -->
				<div class="row mx-5 mt-3">
					<div class="col row-eq-height">
						<div class="row mb-3 p-3 gaz-list alerte-promo full-width">
							<div class="col">
								<img class="d-inline-block align-top" src="../img/gazette/gaz-alerte-promo.png">
								<div class="d-inline-block pl-3" id="alerte-promo-list">
									<p class="heavy">Offres spéciales :</p>

									<ul>
										<?php foreach ($listActiveOpp as $activeOpp): ?>
											<li>
												<a class='stat-link' href="../gazette/opp-encours.php#<?=$activeOpp['id']?>"><?=$activeOpp['title'] ?></a>
												<?=($activeOpp['date_start']==date('Y-m-d') ||  $activeOpp['date_start']==(new DateTime('yesterday'))->format('Y-m-d')) ? "<span class='badge badge-warning ml-3'>Nouveau</span>" :""?>
												<br>
												<i class="fas fa-hourglass-half px-3"></i>fin de l'offre le  <?=date('d/m/Y', strtotime($activeOpp['date_end']))?>

											</li>
										<?php endforeach ?>

									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="col-1"></div> -->
					<div class="col">


					</div>
				</div>
			</div>
			<div class="col-xl-1"></div>

		</div>





		<!-- formulaire de recherche -->
		<div class="row mt-5">
			<div class="col">
				<h1 class="text-main-blue" id="search">Historique des gazettes</h1>
				<p class="pl-5">Pour rechercher une gazette, veuillez saisir la période dans le formulaire ci dessous (les gazettes antérieures à septembre 2017 n'ont pas été conservées)</p>
			</div>
		</div>
		<div class="row mb-5 pl-5">
			<div class="col-auto">
				<img src="../img/gazette/gaz-search.png">
			</div>
			<div class="col mt-3">
				<form method="post" action="">
					<div class="row">
						<div class="col-xl-2">
							<div class="form-group">
								<label for="date">Début de période</label>
								<input type="date" value="<?= $todayInput?>" class="form-control" name="start" min="2017-09-01" >
							</div>
						</div>
						<div class="col-xl-2">
							<div class="form-group">
								<label for="date">Début de période</label>
								<input type="date" value="<?= $todayInput?>" class="form-control" name="end" min="2017-09-01">
							</div>
						</div>
						<div class="col-xl-3 mt-4 pt-2">
							<button class="btn btn-primary" type="submit" name="submit" >Rechercher</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-xl-1"></div>
		</div>




		<div class="row" id="result">
			<div class="col">
				<?php if(isset($linkSearch)){echo "<p>Resultat de votre recherche : </p>". $linkSearch ;} ?>
			</div>

		</div>
		<!-- END formulaire de recherche -->
	</div><!-- END container -->




	<?php require '../view/_footer-bt.php'; ?>