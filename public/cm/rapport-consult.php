<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../Class/Db.php';




require '../../Class/cm/Helpers.php';
require '../../Class/cm/RapportDao.php';
require '../../Class/cm/MagDao.php';
require '../../Class/cm/ProdHelpers.php';
require '../../Class/cm/ThemeHelpers.php';
require '../../Class/cm/RapportHelpers.php';
// require_once '../../vendor/autoload.php';

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0].'.css';
if(file_exists('../css/'.$pageCss)){
	$cssFile=$pageCss;
}
$errors=[];
$success=[];
$db=new Db();

$pdoUser=$db->getPdo('web_users');
$pdoCm=$db->getPdo('cm');
$pdoMag=$db->getPdo('magasin');



$prodPrecedent="";
// $cmtRayon=[];
$legend=[
	1 =>'<img src="../img/report/x.png">',
	2 =>'<img src="../img/report/xx.png">',
	3 =>'<img src="../img/report/xxx.png">',
	4 =>'<img src="../img/report/xxxx.png">',
];
$arrayIndex=0;
$reponsesRapportRayon=[];
$arTheme=ThemeHelpers::getThemes($pdoCm);
$listDoc=RapportHelpers::getFormDocnames($pdoCm);



$rapportDao=new RapportDao($pdoCm);
$magDao=new MagDao($pdoMag);
$rapport=$rapportDao->getOneRapportByIdRdv($_GET['id']);
$galec=$rapport['galec'];

if($_SESSION['id_type']!=1  && $_SESSION['id_type']!=4){
	if($galec!=$_SESSION['id_galec'])	{
		echo "vous n'avez pas de droits suffisants pour consulter cette page";
		exit();
	}
}





$listProdRepondu=$rapportDao->getListProdRepondu($_GET['id']);
$listTheme=$rapportDao->getListTheme();

$oblRep=$rapportDao->getOblRep($_GET['id']);
$formationCmt=$rapportDao->getFormationCmt($_GET['id']);
$remodelingCmt=$rapportDao->getRemodelingCmt($_GET['id']);

$listDocjoin=$rapportDao->getDocjoinName($_GET['id']);



if (!empty($listProdRepondu)) {

	foreach ($listProdRepondu as $prod){
		foreach ($listTheme as $theme){
			$reponses=$rapportDao->getReponseByProdAndTheme($_GET['id'],$prod['id_prod'], $theme['id']);
			$arrReponses=explode('-',$reponses);
			$uniqueRep=array_unique($arrReponses);
			$reponses=implode('-', array_unique($arrReponses));
			$smiley=$rapportDao->getSmiley($_GET['id'],$prod['id_prod'], $theme['id']);
			$reponsesRapportRayon[$arrayIndex]['id_produit']=$prod['id_prod'];
			if(!empty($reponses)){
				$sentence=$rapportDao->getSentence($reponses);
			}

			if(!empty($sentence)){
				$reponsesRapportRayon[$arrayIndex][$arTheme[$theme['id']]]=$sentence['sentence'];
			}elseif(empty($sentence) && !empty($reponses)){
			// pas de phrase correspondante, on récupère donc les réponses en découpant la réponse pour le theme
			// c'est avec les id réponses que l'on va pouvoir récupérer les questions
				$orgReponses=$rapportDao->getOriginalQuestionReponse($pdoCm,$reponses);
				$reponsesRapportRayon[$arrayIndex][$arTheme[$theme['id']]]=$orgReponses . $reponses;

			}else{
				$reponsesRapportRayon[$arrayIndex][$arTheme[$theme['id']]]="";

			}
			if(!empty($smiley)){
				$reponsesRapportRayon[$arrayIndex]['note_'.$arTheme[$theme['id']]]=$smiley['note'];
			}
		}

		$listPhoto=$rapportDao->getProdPhoto($_GET['id'], $prod['id_prod']);


		if(!empty($listPhoto)){
			foreach ($listPhoto as $key => $photo){
				$reponsesRapportRayon[$arrayIndex]['photo'][]=$photo['photo'];
			}
		}
		$arrayIndex++;
	}
}

//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="sub-container bg-white">

	<div class="row pt-5">
		<div class="col-lg-1"></div>

		<div class="col">
			<h1 class="text-main-blue">Compte rendu <?=$rapport['deno']?></h1>
		</div>
	</div>



	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col ubuntu text-main-blue">Visite du <?=date('d/m/Y', strtotime($rapport['date_start']))?> de Monsieur <?= Helpers::cmFullName($rapport['cal_owner'])?></div>
	</div>

	<?php if (!empty($reponsesRapportRayon)): ?>

		<div class="row my-3">
			<div class="col-lg-1"></div>
			<div class="col-lg-7"><h2 class="text-main-blue ubuntu text-center">Inventaire</h2></div>
			<div class="col-lg-1"></div>
		</div>
		<div class="row ">
			<div class="col-lg-1"></div>
			<div class="col produits">
				<?php foreach ($reponsesRapportRayon as $repRayon): ?>


					<div class="row detail-cat mb-5">
						<div class="col-lg-8 text-cat">
							<h5 class="ubuntu text-center title-cat"><?=ProdHelpers::getProd($pdoCm, $repRayon['id_produit']);?></h5>
							<p>
								<!-- smiley -->
								<div class="ubuntu font-weight-bold d-inline-block">
									Agencement : <?= isset($repRayon['note_agencement'])?$legend[$repRayon['note_agencement']] :''?>

								</div>
								<div class="ubuntu font-weight-bold d-inline-block">Balisage : <?=isset($repRayon['note_balisage']) ? $legend[$repRayon['note_balisage']] : ''?>

							</div>
							<div class="ubuntu font-weight-bold d-inline-block">Gamme : <?=isset($repRayon['note_gamme']) ?$legend[$repRayon['note_gamme']]:''?>

						</div>
					</p>

					<div class="cmt">
						<div class="cmt-text">
							<div class="font-weight-bold  ubuntu">Agencement : </div>
							<?=$repRayon['agencement']?>
							<?php $cmtRayon=$rapportDao->getCmtRayonByProdAndTheme($_GET['id'],$repRayon['id_produit'], 1);?>
							<?php if (!empty($cmtRayon)): ?>
								<br><?=nl2br($cmtRayon['cmt'])?>
							<?php endif ?>

							<div class="font-weight-bold  ubuntu">Balisage :</div>
							<?=$repRayon['balisage']?>
							<?php $cmtRayon=$rapportDao->getCmtRayonByProdAndTheme($_GET['id'],$repRayon['id_produit'], 2);?>

							<?php if (!empty($cmtRayon)): ?>
								<br><?=nl2br($cmtRayon['cmt'])?>
							<?php endif ?>

							<div class="font-weight-bold  ubuntu">Gamme</div>
							<?=$repRayon['gamme']?>
							<?php $cmtRayon=$rapportDao->getCmtRayonByProdAndTheme($_GET['id'],$repRayon['id_produit'], 3);?>
							<?php if (!empty($cmtRayon)): ?>
								<br><?=nl2br($cmtRayon['cmt'])?>
							<?php endif ?>
						</div>
					</div>
				</div>
				<div class="col img-container img-cat">
					<div class="ubuntu font-weight-bold pt-3 pb-5">Photos :</div>
					<?php if (isset($repRayon['photo'])): ?>
						<?php for($i=0;$i<count($repRayon['photo']);$i++):?>
							<img src="<?=ANDROID_UPLOAD.$repRayon['photo'][$i]?>" class="prod-img">
						<?php endfor ?>

					<?php endif ?>
				</div><!-- ./img -->
			</div><!-- ./detail-cat -->
		<?php endforeach ?>
	</div>
	<div class="col-lg-1"></div>
</div>

<?php endif ?>

<?php if (isset($oblRep) && !empty($oblRep)): ?>

<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-7"><h2 class="text-main-blue ubuntu text-center">Obligations légales</h2></div>
	<div class="col-lg-1"></div>
</div>
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-lg-7">
		<div class="cmt mr-5">
			<div class="cmt-text">
				<?php foreach ($oblRep as $key => $rep): ?>
					<div class="row">
						<div class="col">
							<?=$rep['question']?>
						</div>
						<div class="col-2">
							<?=$rep['reponse']?>

						</div>
					</div>

				<?php endforeach ?>
			</div>
		</div>
	</div>
	<div class="col-lg-1"></div>
</div>
<?php endif ?>
<?php if (!empty($formationCmt)): ?>
	<div class="row mt-5">
		<div class="col-lg-1"></div>
		<div class="col-lg-7"><h2 class="text-main-blue ubuntu text-center">Formation</h2></div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col-lg-7">
			<div class="cmt mr-5">
				<div class="cmt-text">
					<?=nl2br($formationCmt['cmt'])?>
				</div>
			</div>
		</div>
	</div>
	<?php if (!empty($listDocjoin)): ?>

	<?php endif ?>
	<div class="row mt-5">
		<div class="col-lg-1"></div>
		<div class="col ubuntu font-weight-bold">
			Documents à consulter :
		</div>
	</div>

	<div class="row mb-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php foreach ($listDocjoin as $key => $doc): ?>
				<a href="<?=CM_UPLOAD_URL.$doc['file']?>"><i class="fas fa-file-pdf pr-3"></i><?=$doc['docname']?></a><br>
			<?php endforeach ?>
		</div>
		<div class="col-lg-1"></div>
	</div>
<?php endif ?>
<?php if (!empty($remodelingCmt)): ?>
	<div class="row mt-5">
		<div class="col-lg-1"></div>
		<div class="col-lg-7"><h2 class="text-main-blue ubuntu text-center">Remodeling</h2></div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col-lg-7">
			<div class="cmt mr-5">
				<div class="cmt-text">

					<?=nl2br($remodelingCmt['cmt'])?>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>

<?php if (!empty($rapport['signature'])): ?>
	<div class="row mt-5">
		<div class="col-lg-6">
		</div>
		<div class="col">
			Visite faite en présence de :<br>
			<?=$rapport['signataire']?><br>
			<img src="<?=ANDROID_UPLOAD.$rapport['signature']?>" class="signature">
		</div>
	</div>

<?php endif ?>


</div>

<?php
require '../view/_footer-bt.php';
?>