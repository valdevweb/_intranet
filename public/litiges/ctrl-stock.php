<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit;
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

// ---------------------------------------
// 	ajout enreg dans stat
// ---------------------------------------
require "../../functions/stats.fn.php";
$descr="formulaire saisie contrôle de stock" ;
$page=basename(__file__);
$action="";
// addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
addRecord($pdoStat,$page,$action, $descr, 101);

require_once '../../vendor/autoload.php';
require_once '../../Class/litiges/LitigeDao.php';
require_once '../../Class/mag/MagHelpers.php';
// require 'info-litige.fn.php';

$errors=[];
$success=[];
$litigeDao=new LitigeDao($pdoLitige);
unset($_SESSION['goto']);

//------------------------------------------------------
//			DESCRIPTIF PAGE
//------------------------------------------------------
/*

Doit permettre aux pilotes de répondre à la demande de contrôle de stock faite via la page action
=> les infos à reprendre sont plus ou moins celle du pdf que reçoivent les pilotes lors d'une demande de controle de stock
(uniquement par besoin des infos de transport)
=> stock ok ou non et/ou mvt est par article, controle de stock fait est par dossier

principe : pour chaque article : stock ok ou non
	=> si non ok : afficher zone pour saisir écart en plus ou moins constaté
					+ zone pour le mouvement + date mouvement
=> ajouter une zone de commentaire globale
quand contrôle temriné => case à cocher pour signaler contrôle terminé => mail + nouvelle action avec récap ou lien vers contrôle

=> doit pouvoir sélectionner un dossier demandé en contrôle
=> ou utiliser l'id du dossier envoyé dans le mail

 */



function getDdCtrl($pdoLitige){
	$req=$pdoLitige->prepare("SELECT id, dossier FROM dossiers  WHERE ctrl_ok=2 ORDER BY dossier ASC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);

}


function updateDetail($pdoLitige,$key, $ctrlKo, $ecart,$mvt)
{
	$req=$pdoLitige->prepare("UPDATE details SET ctrl_ko= :ctrlKo, ecart= :ecart, mvt= :mvt, date_ctrl= :date_ctrl WHERE id= :key");
	$req->execute([
		':ctrlKo'	=>$ctrlKo,
		':ecart'	=>$ecart,
		':mvt'		=>$mvt,
		':key'		=>$key,
		':date_ctrl'		=>date('Y-m-d H:i:s')

	]);
	return $req->rowCount();
	// return $req->errorInfo();

}

function updateDetailInv($pdoLitige,$key, $ctrlKo, $ecart,$mvt)
{
	$req=$pdoLitige->prepare("UPDATE details SET ctrl_ko_inv= :ctrlKo, ecart_inv= :ecart, mvt_inv= :mvt, date_ctrl= :date_ctrl WHERE id= :key");
	$req->execute([
		':ctrlKo'	=>$ctrlKo,
		':ecart'	=>$ecart,
		':mvt'		=>$mvt,
		':key'		=>$key,
		':date_ctrl'		=>date('Y-m-d H:i:s')

	]);
	return $req->rowCount();
	// return $req->errorInfo();

}

function addAction($pdoLitige,$contrainte,$reportAction)
{
	$req=$pdoLitige->prepare("INSERT INTO action_litiges (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'	=>$_GET['id'],
		':libelle'	=>	$reportAction,
		':id_contrainte'	=>1,
		':id_web_user'	=>$_SESSION['id_web_user'],
		':date_action'	=> date('Y-m-d H:i:s'),

	]);
	return $req->rowCount();
}


function updateCtrl($pdoLitige)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET ctrl_ok=:ctrl_ok, id_user_ctrl_stock= :id_user_ctrl_stock WHERE id=:id");
	$req->execute(array(
		':ctrl_ok'	=>1,
		':id'		=>$_GET['id'],
		':id_user_ctrl_stock'=>$_SESSION['id_web_user']
	));
	// return $req->rowCount();
	return $req->errorInfo();
}

function getOperateur($pdoLitige){
	$req=$pdoLitige->prepare("SELECT concat(prenom, ' ', nom) as fullname FROM equipe WHERE id_web_user= :id_web_user");
	$req->execute([
		':id_web_user'	=>$_SESSION['id_web_user']
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getThisAction($pdoLitige){
	$req=$pdoLitige->prepare("SELECT * FROM action WHERE id_dossier= :id_dossier AND id_contrainte=2");
	$req->execute([
		':id_dossier'		=>$_GET['id']
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// SELECT * FROM (SELECT * FROM action WHERE id_contrainte=2 || id_contrainte=1 ORDER BY id_dossier ASC, id_contrainte ASC) sousreq GROUP BY id_dossier




if(isset($_GET['id'])){
	$litige= $litigeDao->getLitigeDossierDetailById($_GET['id']);
		// echo "<pre>";
		// print_r($litige);
		// echo '</pre>';

	$analyse=$litigeDao->getAnalyse($_GET['id']);
	$infos= $litigeDao->getInfos($_GET['id']);
	$actionList=$litigeDao->getAction($_GET['id']);
	$arCentrale=MagHelpers::getListCentrale($pdoMag);
}
$reportAction=$art=$ctrlKo=$ecart=$mvt="";
$ddStock=getDdCtrl($pdoLitige);
if(isset($_POST['submit'])){


//les champs de formoulaires renvoient des tableaus avec pour index soit i soit pour le champ art, l'id_detail
// les id_details sont stockés dans les champs hidden du tableau id_detail
// On boucle donc sur les $_POST[id_detail] pour récupérer les id_details ainsi que les noms de champs
// Dans le cas d'une inversion d'article, on a des champs supplementaires pour saisir les infos des articles inversés
// (meme nom de champ avec prefixe inv_). Etant donné que sur un litige on peut avoir plusieurs types de réclamations,
// l'index i n'est pas forcement en phase entre les articles normaux et les articles inversés => voir shéma ctrl_stock.pptx
// on vérifie si il existe un champ art-inv['id_detail']
	for($i=0; $i<count($_POST['id_detail']); $i++)
	{
 	//exemple $_POST['id_detail'][0]=488
		$key=$_POST['id_detail'][$i];
		$art=$_POST['art'][$key];
		$descr=$_POST['descr'][$key];
 	// si btn radio sur ko, on récupère les autres champs, sinon non
		if($_POST['ctrl'][$key]=="no")
		{
			$ctrlKo=1;
			$ecart=$_POST['ecart'][$art];
			$mvt=$_POST['mvt'][$art];
			$reportAction.='- article ' .$art . ' - '.$descr. ' : ' . $ecart .' pièce(s) - mouvement : '.$mvt .'<br>';

		}
		else{
			$ctrlKo=0;
			// 0 pour qu'une mise à jour soit faite sinon la fonction ne renvoie pas 1
			$ecart=0;
			$mvt=' ';
			$reportAction.='- article ' .$art . ' - '.$descr.  ' : contrôle ok<br>';
		}
		$majdetail=updateDetail($pdoLitige, $key, $ctrlKo, $ecart,$mvt);
		// dans le cas d'un inversion d'article, on fait une 2ème maj avec les données de l'article inversé
		if(isset($_POST['art-inv'][$key])){
			$artInv=$_POST['art-inv'][$key];

			$descrInv=$_POST['descr-inv'][$key];

			if($_POST['ctrl-inv'][$key]=="no")
			{
				$ctrlKo=1;
				$ecart=$_POST['ecart-inv'][$artInv];
				$mvt=$_POST['mvt-inv'][$artInv];
				$reportAction.='- article ' .$artInv . ' - '.$descrInv.  ' : ' . $ecart .' pièce(s) - mouvement : '.$mvt .'<br>';

			}
			else{
				$ctrlKo=0;
			// 0 pour qu'une mise à jour soit faite sinon la fonction ne renvoie pas 1
				$ecart=0;
				$mvt=' ';
				$reportAction.='- article ' .$artInv .  ' - '.$descrInv.  ' : ' . ' : contrôle ok<br>';
			}
			$majdetailInv=updateDetailInv($pdoLitige, $key, $ctrlKo, $ecart,$mvt);

		}


		if($majdetail!=1){
			$errors[]="impossible de mettre à jour la base détail article";
		}
		else{

		}
	}
	if(count($errors)==0)
	{
		$reportAction=$reportAction .'<br><strong>Commentaire : </strong> <br>'. $_POST['cmt'];
		$contrainte=1;
		$added=addAction($pdoLitige,$contrainte,$reportAction);
		if($added==1)
		{
			updateCtrl($pdoLitige);
		}else
		{
			$errors[]="impossible de mettre à jour les infos dossiers du litige";
		}
	}
	if(count($errors)==0){
			// envoi mail
		$operateur=getOperateur($pdoLitige);
		if(empty($operateur)){
			$operateur['fullname']='operateur inconnu';
		}
		if(VERSION=='_'){
			$dest[]=MYMAIL;

		}
		else{
			$dest=[EMAIL_LITIGES];

		}
		$htmlMail = file_get_contents('mail/mail-bt-ctrl-stock-ok.php');
		$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
		$htmlMail=str_replace('{CODEBT}',$litige[0]['btlec'],$htmlMail);
		$htmlMail=str_replace('{MAG}',$litige[0]['deno'],$htmlMail);
		$htmlMail=str_replace('{OPERATEUR}',$operateur['fullname'],$htmlMail);
		$htmlMail=str_replace('{RECAP}',$reportAction,$htmlMail);
		$subject='Portail BTLec - Litiges : retour contrôle de stock dossier - ' .$litige[0]['dossier'];

// ---------------------------------------
// initialisation de swift
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest);

		$delivered=$mailer->send($message);
		if($delivered !=0)
		{
			$successQ='?id='.$_GET['id'].'&success';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}

	}
}
if(isset($_POST['choose']))
{
	header('Location:'.$_SERVER['PHP_SELF'].'?id='.$_POST['listDossier']);
}

if(isset($_GET['success'])){
	$success[]="Vos informations ont bien été enregistrées, un mail récapitulatif a été envoyé";

}


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

//------------------------------------------------------
//			DECLARATIONS
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

		<?php if (!isset($_GET['id'])): ?>
		<h1 class="text-main-blue py-5 ">Contrôle de stock </h1>

			<div class="row pb-5">
				<div class="col-lg-1"></div>
				<div class="col">
					<form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>">
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label for="listDossier">Dossiers à contrôler :</label>
									<select class="form-control" id="listDossier" name="listDossier">
										<option value="">Sélectionnez</option>
										<?php foreach ($ddStock as $dd): ?>
											<option value="<?=$dd['id']?>"><?=$dd['dossier']?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col mt-4 pt-2">
								<p>&nbsp;</p>
								<button class="btn btn-primary" name="choose">Sélectionner</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-lg-1"></div>
			</div>
			<?php else: ?>

				<h1 class="text-main-blue py-5 ">Contrôle de stock du dossier <?= isset($litige[0]['dossier'])? $litige[0]['dossier'] : ''?></h1>

				<div class="row">
					<div class="col-lg-1"></div>
					<div class="col">
						<?php
						include('../view/_errors.php');
						?>
					</div>
					<div class="col-lg-1"></div>
				</div>

				<!-- info dossier -->
				<div class="row">
					<div class="col-1"></div>
					<div class="col bg-alert bg-light-blue">
						<!-- mag -->
						<div class="row ">
							<div class="col">
								<span class="heavy">Magasin : </span>
								<span>	<?= isset($litige[0]['btlec'])? $litige[0]['btlec'] : ''?> - <?= isset($litige[0]['deno'])? $litige[0]['deno'] : ''?></span>
							</div>
							<div class="col">
								<span class="heavy">Centrale : </span>
								<span><?= isset($arCentrale[$litige[0]['centrale']])? $arCentrale[$litige[0]['centrale']] : ''?></span>

							</div>
						</div>
						<!--  personnel-->
						<div class="row mt-3">
							<div class="col">
								<span class="heavy">Préparateur : </span>
								<?= isset($infos['fullprepa'])? $infos['fullprepa'] : ''?>
							</div>
							<div class="col">
								<span class="heavy">Contrôleur : </span>
								<?= isset($infos['fullctrl'])? $infos['fullctrl'] : ''?>

							</div>
							<div class="col">

								<span class="heavy">Chargeur : </span>
								<?= isset($infos['fullchg'])? $infos['fullchg'] : ''?>

							</div>
						</div>

					</div>
					<div class="col-1"></div>
				</div>



				<div class="row mt-5 mb-3">
					<div class="col text-center text-main-blue">
						<h5>Historique des actions : </h5>
					</div>
				</div>

				<div class="row">
					<div class="col-1"></div>
					<div class="col">

						<div class="alert alert-secondary">
							<?php foreach ($actionList as $key => $action): ?>
								<div class="font-weight-bold">Action du <?=$action['dateFr']?> par <?=$action['name']?></div>
								<div class="pl-5"><?=$action['libelle']?></div>
							<?php endforeach ?>
						</div>
					</div>
					<div class="col-1"></div>
				</div>
				<div class="row mt-5 mb-3">
					<div class="col text-center text-main-blue">
						<h5>Articles à contrôler : </h5>
					</div>
				</div>
				<div class="row">
					<div class="col-1"></div>
					<div class="col">
						<?php if (isset($litige)): ?>


							<form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">
								<?php foreach ($litige as $key => $prod): ?>
									<div class="row pb-3">
										<div class="col-5">
											<span class="heavy">
												<?=$prod['article']?> :
											</span>
											<?=$prod['descr']?>

										</div>
										<div class="col-auto">
											Stock :

										</div>
										<div class="col-2">
											<div class="form-check">
												<input class="form-check-input ctrl-ok" type="radio" name="ctrl[<?=$prod['id_detail']?>]" value="ok" data="<?=$prod['article']?>" required>
												<label class="form-check-label text-green" for="ctrl">ok</label>
											</div>
											<div class="form-check">
												<input class="form-check-input ctrl-ko" type="radio" name="ctrl[<?=$prod['id_detail']?>]" id="<?=$prod['id_detail']?>" value="no" data="<?=$prod['article']?>" required>
												<label class="form-check-label text-red" for="ctrl">ko</label>
											</div>
											<input type="hidden" name="id_detail[]" value="<?=$prod['id_detail']?>">
											<input type="hidden" name="art[<?=$prod['id_detail']?>]" value="<?=$prod['article']?>">
											<input type="hidden" name="descr[<?=$prod['id_detail']?>]" value="<?=$prod['descr']?>">
										</div>
										<div class="col ctrl-ko-<?=$prod['article']?>"></div>
									</div>
									<!-- si inversion de produit, on demande aussi le contrôle des produits inversés -->
									<?php if ($prod['inversion']!=''): ?>

										<div class="row pb-3">
											<div class="col-5">
												<span class="heavy">
													<?=$prod['inv_article']?> :
												</span>
												<?=$prod['inv_descr']?>

											</div>
											<div class="col-auto">
												Stock :

											</div>
											<div class="col-2">
												<div class="form-check">
													<input class="form-check-input ctrl-ok-inv" type="radio" name="ctrl-inv[<?=$prod['id_detail']?>]" value="ok" data="<?=$prod['inv_article']?>" required>
													<label class="form-check-label text-green" for="ctrl">ok</label>
												</div>
												<div class="form-check">
													<input class="form-check-input ctrl-ko-inv" type="radio" name="ctrl-inv[<?=$prod['id_detail']?>]" id="<?=$prod['id_detail']?>" value="no" data="<?=$prod['inv_article']?>" required>
													<label class="form-check-label text-red" for="ctrl">ko</label>
												</div>
												<input type="hidden" name="id_detail_inv[<?=$prod['id_detail']?>]" value="<?=$prod['id_detail']?>">
												<input type="hidden" name="art-inv[<?=$prod['id_detail']?>]" value="<?=$prod['inv_article']?>">
												<input type="hidden" name="descr-inv[<?=$prod['id_detail']?>]" value="<?=$prod['inv_descr']?>">

											</div>
											<div class="col ctrl-ko-inv-<?=$prod['inv_article']?>"></div>
										</div>



									<?php endif ?>
								<?php endforeach ?>
								<div class="row">
									<div class="col">
										<div class="form-group">
											<label>Commentaires : </label>
											<textarea class="form-control" row="3" name="cmt"></textarea>
										</div>
									</div>

								</div>
								<div class="row mb-5">
									<div class="col text-right"><button class="btn btn-primary" name="submit" type="submit"><i class="fas fa-save pr-3"></i>Enregistrer</button></div>
								</div>

							</form>
							<?php else: ?>
								<div class="alert alert-primary">Veuillez sélectionner un dossier</div>
							<?php endif ?>

						</div>
						<div class="col-1"></div>
					</div>
				<?php endif ?>

				<!-- ./container -->
			</div>
			<script type="text/javascript">

				$(".ctrl-ko").click(function () {
					function createCtrl(article){
						var ctrlInputs='';
						ctrlInputs+='<div class="form-group">';
						ctrlInputs+='<label for="ecart">Ecart constaté (nb colis +/-) : </label>';
						ctrlInputs+='<input type="text" class="form-control" name="ecart['+article+']" id="ecart" title="chiffres positif ou négatif uniqement" pattern="[-+]?[0-9]*[.]?[0-9]+" required>';
						ctrlInputs+='</div>';
						ctrlInputs+='<div class="form-group">';
						ctrlInputs+='<label for="mvt">Mouvement passé :</label>';
						ctrlInputs+='<input type="text" class="form-control" name="mvt['+article+']" id="mvt">';
						ctrlInputs+='</div>';
						return ctrlInputs;
					}
					var article=$(this).attr('data');
					var ctrlInputs=createCtrl(article);
					$(".ctrl-ko-"+article).append(ctrlInputs);

				});
				$(".ctrl-ok").click(function () {
					var article=$(this).attr('data');
					$(".ctrl-ko-"+article).empty();
				});



				$(".ctrl-ko-inv").click(function () {
					function createCtrl(article){
						var ctrlInputs='';
						ctrlInputs+='<div class="form-group">';
						ctrlInputs+='<label for="ecart">Ecart constaté (nb colis +/-) : </label>';
						ctrlInputs+='<input type="text" class="form-control" name="ecart-inv['+article+']" id="ecart" title="chiffres positif ou négatif uniqement" pattern="[-+]?[0-9]*[.]?[0-9]+" required>';
						ctrlInputs+='</div>';
						ctrlInputs+='<div class="form-group">';
						ctrlInputs+='<label for="mvt">Mouvement passé :</label>';
						ctrlInputs+='<input type="text" class="form-control" name="mvt-inv['+article+']" id="mvt">';
						ctrlInputs+='</div>';
						return ctrlInputs;
					}
					var article=$(this).attr('data');
					var ctrlInputs=createCtrl(article);
					$(".ctrl-ko-inv-"+article).append(ctrlInputs);

				});
				$(".ctrl-ok-inv").click(function () {
					var article=$(this).attr('data');
					console.log(article);

					$(".ctrl-ko-inv-"+article).empty();
				});


			</script>
			<?php
			require '../view/_footer-bt.php';
			?>