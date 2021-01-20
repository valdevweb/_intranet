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

require_once  '../../vendor/autoload.php';
require '../../Class/MagDao.php';
require '../../Class/Mag.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dossiers WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


$fLitige=getLitige($pdoLitige);


function getanalyse($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM analyse WHERE mask=0 ORDER BY analyse");
	$req->execute(array());
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$analyses=getanalyse($pdoLitige);

function getconclusion($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM conclusion WHERE mask=0 ORDER BY conclusion");
	$req->execute(array());
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$conclusions=getconclusion($pdoLitige);

function getetat($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM etat WHERE mask=0 ORDER BY etat");
	$req->execute(array());
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$etats=getetat($pdoLitige);

function getgt($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM gt WHERE mask=0 ORDER BY gt");
	$req->execute(array());
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$gts=getgt($pdoLitige);

function getimputation($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM imputation WHERE mask=0 ORDER BY imputation");
	$req->execute(array());
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$imputations=getimputation($pdoLitige);

function gettypo($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM typo WHERE mask=0 ORDER BY typo");
	$req->execute(array());
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$typos=gettypo($pdoLitige);

function updateDossier($pdoLitige)
{
	$gtform=(empty($_POST['gt-form'])) ? NULL : $_POST['gt-form'];
	$imputationform=(empty($_POST['imputation-form'])) ? NULL : $_POST['imputation-form'];
	$typoform=(empty($_POST['typo-form'])) ? NULL : $_POST['typo-form'];
	$etatform=(empty($_POST['etat-form'])) ? NULL : $_POST['etat-form'];
	$analyseform=(empty($_POST['analyse-form'])) ? NULL : $_POST['analyse-form'];
	$conclusionform=(empty($_POST['conclusion-form'])) ? NULL : $_POST['conclusion-form'];
	$datecloture=(empty($_POST['date_cloture'])) ? NULL : $_POST['date_cloture'];
	$solde=isset($_POST['cloture_check']) ? 1 : 0;

	$req=$pdoLitige->prepare("UPDATE dossiers SET etat_dossier=:solde,id_gt=:gtform,id_imputation=:imputationform,id_typo=:typoform,id_etat=:etatform,id_analyse=:analyseform,id_conclusion=:conclusionform,date_cloture=:date_cloture WHERE id=:id");
	$req->execute(array(
		':solde'	=>$solde,
		':gtform'	=>$gtform,
		':imputationform'	=>$imputationform,
		':typoform'	=>$typoform,
		':etatform'	=>$etatform,
		':analyseform'	=>$analyseform,
		':conclusionform'	=>$conclusionform,
		':date_cloture'	=>$datecloture,
		':id'	=>$_GET['id']
	));
	return $req->rowCount();
	// return $req->errorInfo();

}


// ajout action quand envoi mail cloture au mag
function addAction($pdoLitige, $action){
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'		=> $_GET['id'],
		':libelle'			=>$action,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_action'		=> date('Y-m-d H:i:s'),
	]);
	return $req->rowCount();
}

// -------------------------------
// Variables
// -------------------------------
$errors=[];
$success=[];
$etat="etat-grey";
if(isset($_POST['submit']))
{
	$maj=updateDossier($pdoLitige);
	if($maj==1)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&etat=ok';
		header($loc);
	}
	else
	{
		$errors[]="enregistrement impossible";

	}
}
		// on prend la date de cloture car on peut choisir de cloturer un dossier alors qu'on attend toujours des élements sur le dossier
if(isset($_POST['submit_mail']))
{

	$maj=updateDossier($pdoLitige);
	if($maj==1)
	{
		if(!empty($_POST['date_cloture']))
		{
			if(VERSION =='_'){
				$mailMag=array('valerie.montusclat@btlec.fr');
			}
			else{
				$magDao=new MagDao($pdoMag);
				$infoMag=$magDao->getMagByGalec($fLitige['galec']);
				$codeBt=$infoMag->getId();
				$mailMag=array($codeBt.'-rbt@btlec.fr');
			}

			$magTemplate = file_get_contents('mail/mail-mag-cloture.php');
			$magTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$magTemplate);
			$subject='Portail BTLec Est  - clôture du dossier litige ' . $fLitige['dossier'];
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($magTemplate, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
			->setTo($mailMag)
			->setBcc(['valerie.montusclat@btlec.fr', 'nathalie.pazik@btlec.fr', 'jonathan.domange@btlec.fr']);
			$delivered=$mailer->send($message);
			if($delivered >0){
				// ajout d'une entrée dans les actions
				$action='envoi du mail de clôture au magasin';
				$add=addAction($pdoLitige, $action);
				if($add==1){
					$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&etat=ok';
					header($loc);
				}else{
					$errors[]='l\'action n\'a pas pu être enregistrée';
				}

			}else{
				$errors[]='Le mail n\'a pas pu être envoyé';
			}
		}
		else
		{
			$errors[]='vous devez renseigner une date de clôture';
		}
	}
	else
	{
		$errors[]="enregistrement impossible";
	}

}
if(isset($_GET['etat']))
{
	$etat="etat-vert";
}

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
	<div class="row pt-3">
		<div class="col">
			<p class="text-right"><a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a></p>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue pb-3 ">Dossier N° <?= $fLitige['dossier']?></h1>
			<h4 class="khand text-main-blue">Analyse du litige</h4>

		</div>
	</div>
	<?php
	include('../view/_errors.php');
	?>

	<div class="row">
		<div class="col">
			<div class="row border p-3 mb-5">
				<div class="col">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
						<!-- gt -->
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label>Nature de la marchandise</label>
									<select class="form-control" name="gt-form">
										<option value="">Sélectionner</option>
										<?php

										foreach ($gts as $gt)
										{

											if($gt['id']==$fLitige['id_gt'])
											{
												$selected='selected';
											}
											else
											{
												$selected='';
											}
											echo '<option value="'.$gt['id'].'" '.$selected.'>'.$gt['gt'].'</option>';

										}
										?>

									</select>
								</div>
							</div>
							<div class="col text-right">
								<i class="fas fa-save fa-lg <?= $etat ?>"></i>
							</div>
						</div>
						<!-- imputation et typo -->
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label>Imputation : </label>
									<select class="form-control" name="imputation-form">
										<option value="">Sélectionner</option>
										<?php
										foreach ($imputations as $imputation)
										{

											if($imputation['id']==$fLitige['id_imputation'])
											{
												$selected='selected';
											}
											else
											{
												$selected='';
											}
											echo '<option value="'.$imputation['id'].'" '.$selected.'>'.$imputation['imputation'].'</option>';

										}
										?>

									</select>
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									<label>Typologie : </label>
									<select class="form-control" name="typo-form">
										<option value="">Sélectionner</option>
										<?php
										foreach ($typos as $typo)
										{

											if($typo['id']==$fLitige['id_typo'])
											{
												$selected='selected';
											}
											else
											{
												$selected='';
											}
											echo '<option value="'.$typo['id'].'" '.$selected.'>'.$typo['typo'].'</option>';

										}
										?>

									</select>
								</div>
							</div>
							<div class="col"></div>
						</div>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label>Statut : </label>
									<select class="form-control" name="etat-form">
										<option value="">Sélectionner</option>
										<?php
										foreach ($etats as $etat)
										{

											if($etat['id']==$fLitige['id_etat'])
											{
												$selected='selected';
											}
											else
											{
												$selected='';
											}
											echo '<option value="'.$etat['id'].'" '.$selected.'>'.$etat['etat'].'</option>';

										}
										?>

									</select>
								</div>
							</div>
							<div class="col"></div>
						</div>
						<div class="row">
							<div class="col-3">
								<?php
								if(isset($fLitige['date_cloture']))
								{
									$datecloture=date('Y-m-d',strtotime($fLitige['date_cloture']));
								}
								else
								{
									$datecloture="";
								}
								?>
								<div class="form-group">
									<label>Date solde : </label>
									<input type="date" name="date_cloture" class="form-control" value="<?= $datecloture?>">
								</div>
							</div>
							<div class="col-3 mt-3">
								<div class="form-check pt-4">
									<?php
									$isChecked="";
									if($fLitige['etat_dossier']==1)
									{
										$isChecked="checked";
									}

									?>
									<input class="form-check-input" type="checkbox" name="cloture_check"  <?= $isChecked ?>>
									<label class="form-check-label">Soldé</label>

								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label>Analyse : </label>
									<select class="form-control" name="analyse-form">
										<option value="">Sélectionner</option>
										<?php
										foreach ($analyses as $analyse)
										{

											if($analyse['id']==$fLitige['id_analyse'])
											{
												$selected='selected';
											}
											else
											{
												$selected='';
											}
											echo '<option value="'.$analyse['id'].'" '.$selected.'>'.$analyse['analyse'].'</option>';

										}
										?>

									</select>
								</div>
							</div>
							<div class="col-3">
								<div class="form-group">
									<label>Réponse : </label>
									<select class="form-control" name="conclusion-form">
										<option value="">Sélectionner</option>
										<?php
										foreach ($conclusions as $conclusion)
										{

											if($conclusion['id']==$fLitige['id_conclusion'])
											{
												$selected='selected';
											}
											else
											{
												$selected='';
											}
											echo '<option value="'.$conclusion['id'].'" '.$selected.'>'.$conclusion['conclusion'].'</option>';

										}
										?>

									</select>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="text-center">
									<button type="submit" id="submit" class="btn btn-primary" name="submit"><i class="fas fa-save pr-3"></i>Enregistrer</button>
									<button type="submit" id="submit_mail" class="btn btn-red" name="submit_mail"><i class="fas fa-save pr-3"></i>Enregistrer et envoyer le mail de clôture</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row mb-5">
		<div class="col">&nbsp;</div>
	</div>





</div>
<?php
require '../view/_footer-bt.php';
?>