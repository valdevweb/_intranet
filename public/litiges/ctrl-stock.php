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
require 'info-litige.fn.php';

$errors=[];
$success=[];
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
	$req=$pdoLitige->prepare("UPDATE details SET ctrl_ko= :ctrlKo, ecart= :ecart, mvt= :mvt WHERE id= :key");
	$req->execute([
		':ctrlKo'	=>$ctrlKo,
		':ecart'	=>$ecart,
		':mvt'		=>$mvt,
		':key'		=>$key

	]);
	return $req->rowCount();
}

function addAction($pdoLitige,$contrainte,$reportAction)
{
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
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
	return $req->rowCount();
}



// SELECT * FROM (SELECT * FROM action WHERE id_contrainte=2 || id_contrainte=1 ORDER BY id_dossier ASC, id_contrainte ASC) sousreq GROUP BY id_dossier


if(!isset($_GET['id'])){

	$ddStock=getDdCtrl($pdoLitige);


	$ldDossier='<form method="post" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">';
	$ldDossier.='<div class="row">';
	$ldDossier.='<div class="col-3">';
	$ldDossier.='<div class="form-group">';
	$ldDossier.='<label for="listDossier">Dossiers à contrôler :</label>';
	$ldDossier.='<select class="form-control" id="listDossier" name="listDossier">';
	$ldDossier.='<option value="">Sélectionnez</option>';

	foreach ($ddStock as $dd)
	{
		$ldDossier.='<option value="'.$dd['id'].'">'.$dd['dossier'].'</option>';

	}
	$ldDossier.='</select>';
	$ldDossier.='</div>';
	$ldDossier.='</div>';
	$ldDossier.='<div class="col mt-4 pt-2">';
	// $ldDossier.='<p>&nbsp;</p>';

	$ldDossier.='<button class="btn btn-primary" name="choose">Sélectionner</button>';
	$ldDossier.='</div>';
	$ldDossier.='</div>';

	$ldDossier.='</form>';

	if(isset($_POST['choose']))
	{
		header('Location:'.$_SERVER['PHP_SELF'].'?id='.$_POST['listDossier']);
	}

}
else{

	$ldDossier="";
}

if(isset($_GET['id']))
{
	$litige=getlitige($pdoLitige);
	$analyse=getanalyse($pdoLitige);
	$infos=getInfos($pdoLitige);
}
$reportAction=$art=$ctrlKo=$ecart=$mvt="";



if(isset($_POST['submit']))
{
// liste des article à contrôler : dans le input hidden 'id_detail' => on doit parcourir ce tableau pour
// récupérer l'index des tableau post puisque l'id_detail est passé en clé des tableau
	for($i=0; $i<count($_POST['id_detail']); $i++)
	{
 	//exemple $_POST['id_detail'][0]=488
		$key=$_POST['id_detail'][$i];
 	// si btn radio sur ko, on récupère les autres champs, sinon non
		if($_POST['ctrl'][$key]=="no")
		{
			$ctrlKo=1;
			$ecart=$_POST['ecart'][$key];
			$mvt=$_POST['mvt'][$key];
			$art=$_POST['art'][$key];
			$reportAction.='- article ' .$art . ' : ' . $ecart .' pièce(s) - mouvement : '.$mvt .'<br>';

		}
		else{
			$ctrlKo=0;
			// 0 pour qu'une mise à jour soit faite sinon la fonction ne renvoie pas 1
			$ecart=0;
			$mvt='';
			$art=$_POST['art'][$key];
			$reportAction.='- article ' .$art . ' : contrôle ok<br>';
		}
		$majdetail=updateDetail($pdoLitige, $key, $ctrlKo, $ecart,$mvt);
		if($majdetail!=1){
			$errors[]="impossible de mettre à jour la base détail article";
		}
		else{

		}
	}
	if(count($errors)==0)
	{
		$reportAction=$reportAction .'<strong>Commentaire : </strong> '. $_POST['cmt'];
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
		$htmlMail = file_get_contents('mail-bt-ctrl-stock-ok.php');
		$htmlMail=str_replace('{DOSSIER}',$litige[0]['dossier'],$htmlMail);
		$htmlMail=str_replace('{RECAP}',$reportAction,$htmlMail);
		$subject='Portail BTLec - Litiges : retour contrôle de stock dossier - ' .$litige[0]['dossier'];

// ---------------------------------------
// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')

		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail SAV Leclerc'))
		->setTo(array('btlecest.portailweb.logistique@btlec.fr'))
		// ->setTo(array('valerie.montusclat@btlec.fr'))
		// ->addCc($copySender['email'])
		->addBcc('valerie.montusclat@btlec.fr');
		// ->attach($attachmentPdf)
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));
// ou
// ->setBcc([adress@btl.fr, adresse@bt.fr])

// echec => renvoie 0
		$delivered=$mailer->send($message);
		if($delivered !=0)
		{
		$success[]="Vos informations ont bien été enregistrées, un mail récapitulatif a été envoyé";

		}

	}



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


		<div class="row">
			<div class="col-lg-1"></div>

			<div class="col">
				<?= $ldDossier?>
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
						<span>	<?= isset($litige[0]['btlec'])? $litige[0]['btlec'] : ''?> - <?= isset($litige[0]['mag'])? $litige[0]['mag'] : ''?></span>
					</div>
					<div class="col">
						<span class="heavy">Centrale : </span>
						<span><?= isset($litige[0]['centrale'])? $litige[0]['centrale'] : ''?></span>

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
				<h5>Articles à contrôler : </h5>
			</div>
		</div>


		<div class="row">
			<div class="col-1"></div>
			<div class="col">
				<form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">
					<?php if (isset($litige)): ?>
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
										<input class="form-check-input ctrl-ok" type="radio" name="ctrl[<?=$prod['id_detail']?>]" value="ok" data="<?=$prod['id_detail']?>" required>
										<label class="form-check-label text-green" for="ctrl">ok</label>
									</div>
									<div class="form-check">
										<input class="form-check-input ctrl-ko" type="radio" name="ctrl[<?=$prod['id_detail']?>]" id="<?=$prod['id_detail']?>" value="no" required>
										<label class="form-check-label text-red" for="ctrl">ko</label>
									</div>
									<input type="hidden" name="id_detail[]" value="<?=$prod['id_detail']?>">
									<input type="hidden" name="art[<?=$prod['id_detail']?>]" value="<?=$prod['article']?>">
								</div>
								<div class="col ctrl-ko-<?=$prod['id_detail']?>">


								</div>


							</div>
						<?php endforeach ?>
					<?php endif ?>
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

			</div>
			<div class="col-1"></div>
		</div>
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


			var article=$(this).attr('id');
			var ctrlInputs=createCtrl(article);
			$(".ctrl-ko-"+article).append(ctrlInputs);

		});
		$(".ctrl-ok").click(function () {
			var article=$(this).attr('data');
			$(".ctrl-ko-"+article).empty();


		});

	</script>
	<?php
	require '../view/_footer-bt.php';
	?>