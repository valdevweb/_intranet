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
require_once  '../../Class/LitigeDao.php';
require_once  '../../Class/MagDao.php';
require_once  '../../Class/Mag.php';

$errors=[];
$success=[];
//------------------------------------------------------
//		MENU
//------------------------------------------------------

/*

Getter 												#1
Variables 											#2
Setter 												#3
traitment transp 									#4
traitement entrepot 								#5
traitement facturation 								#6

 */


// ----------------------------------------
//  GETTER 					#1
// ----------------------------------------
function getLitige($pdoLitige){
	$req=$pdoLitige->prepare("
		SELECT *
		FROM dossiers
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

// ----------------------------------
//  VARIABLES 					#2
// ---------------------------------

$fLitige=getLitige($pdoLitige);
$coutTotal=$fLitige['mt_transp']+$fLitige['mt_assur']+$fLitige['mt_fourn']+$fLitige['mt_mag'];
$thisId=$_GET['id'];
$litigeDao=new LitigeDao($pdoLitige);
$affretes=$litigeDao->getActiveAffrete();
$transporteurs=$litigeDao->getActiveTransporteur();
$transits=$litigeDao->getActiveTransit();
$equipes=$litigeDao->getActiveEquipe();
$etatTransp="etat-gris";
$etatentre="etat-gris";
$etatfac="etat-gris";

// ----------------------------------
// SETTER  							#3
// ---------------------------------
function addTransp($pdoLitige)
{
	if(empty($_POST['transporteur']))
	{
		$id_transp=NULL;
	}
	else
	{
		$id_transp=	$_POST['transporteur'];
	}
	if(empty($_POST['affrete']))
	{
		$id_affrete=NULL;
	}
	else
	{
		$id_affrete=$_POST['affrete'];
	}
	if(empty($_POST['transit']))
	{
		$id_transit=NULL;
	}
	else
	{
		$id_transit=$_POST['transit'];
	}
	if(isset($_POST['transit_check']))
	{
		$transit="oui"	;
	}
	else
	{
		$transit="";
		// IMPORTANT => si décoche passage à quai, la ld disparait mais le post contient la valeur stockée en db, il faut donc l'effacer
		$id_transit=NULL;

	}
	$req=$pdoLitige->prepare("UPDATE dossiers SET id_transp= :id_transp,id_affrete= :id_affrete,id_transit= :id_transit, transitok= :transitok WHERE id= :id");
	$req->execute(array(
		':id'	=>$_GET['id'],
		'id_transp'	=>$id_transp,
		'id_affrete'	=>$id_affrete,
		'id_transit'	=>$id_transit,
		':transitok'		=>$transit
	));
	return	$req->rowCount();
}

function addEquipe($pdoLitige)
{
	if(empty($_POST['preparateur']))
	{
		$id_prepa=NULL;
	}
	else
	{
		$id_prepa=$_POST['preparateur'];
	}
	if(empty($_POST['controleur']))
	{
		$id_ctrl=NULL;
	}
	else
	{
		$id_ctrl=$_POST['controleur'];
	}
	if(empty($_POST['chargeur']))
	{
		$id_chg=NULL;
	}
	else
	{
		$id_chg=$_POST['chargeur'];
	}


	if(empty($_POST['date_prepa']))
	{
		$date_prepa=NULL;
	}
	else
	{
		$date_prepa=$_POST['date_prepa'];
	}

	$req=$pdoLitige->prepare("UPDATE dossiers SET id_prepa=:id_prepa,id_ctrl=:id_ctrl,id_chg=:id_chg,date_prepa=:date_prepa WHERE id= :id");
	$req->execute(array(
		':id'	=>$_GET['id'],
		':id_prepa'		=>$id_prepa,
		':id_ctrl'		=>$id_ctrl,
		':id_chg'		=>$id_chg,
		':date_prepa'		=>$date_prepa,

	));
	return	$req->rowCount();
}

function addFac($pdoLitige)
{
	if(empty($_POST['mt_transp']))
	{
		$mt_transp=NULL;
	}
	else
	{
		$mt_transp=$_POST['mt_transp'];
	}
	if(empty($_POST['mt_assur']))
	{
		$mt_assur=NULL;
	}
	else
	{
		$mt_assur=$_POST['mt_assur'];
	}

	if(empty($_POST['mt_fourn']))
	{
		$mt_fourn=NULL;
	}
	else
	{
		$mt_fourn=$_POST['mt_fourn'];
	}

	if(empty($_POST['mt_mag']))
	{
		$mt_mag=NULL;
	}
	else
	{
		$mt_mag=$_POST['mt_mag'];
	}
	$req=$pdoLitige->prepare("UPDATE dossiers SET mt_transp=:mt_transp, mt_assur=:mt_assur, mt_fourn=:mt_fourn, mt_mag=:mt_mag,fac_mag=:fac_mag WHERE id= :id");
	$req->execute(array(
		':id'	=>$_GET['id'],
		':mt_transp'			=>$mt_transp,
		':mt_assur'			=>$mt_assur,
		':mt_fourn'			=>$mt_fourn,
		':mt_mag'			=>$mt_mag,
		':fac_mag'			=>$_POST['fac_mag'],
	));
	return	$req->rowCount();
}

function updateVingtQuatre($pdoLitige){
	if(isset($_POST['vingtquatre']))
	{
		$fast=1;
	}
	else{
		$fast=0;
	}
	$req=$pdoLitige->prepare("UPDATE dossiers SET vingtquatre=:vingtquatre WHERE id= :id");
	$req->execute(array(
		':vingtquatre'	=>$fast,
		':id'	=>$_GET['id'],
));
	return	$req->rowCount();

}


function updateVingtQuatreEsp($pdoLitige){
	if(isset($_POST['esp'])){
		$fast=1;
	}
	else{
		$fast=0;
	}
	$req=$pdoLitige->prepare("UPDATE dossiers SET esp=:esp WHERE id= :id");
	$req->execute(array(
		':esp'	=>$fast,
		':id'	=>$_GET['id'],
));
	return	$req->rowCount();
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


// ----------------------------------
// traitement transp			#4
// ---------------------------------

if(isset($_POST['submit_t']))
{
	$majOne=updateVingtQuatre($pdoLitige);
	$majTwo=updateVingtQuatreEsp($pdoLitige);


	$row=addTransp($pdoLitige);

	if($row>0 || $majOne >0 || $majTwo >0)
	{
		header('Location:bt-info-litige.php?id='.$_GET['id'].'&etatTransp=ok');

	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}

// ----------------------------------
// traitement entrepot			#5
// ---------------------------------
if(isset($_POST['submit_e']))
{
	$row=addEquipe($pdoLitige);
	if($row>0)
	{
		header('Location:bt-info-litige.php?id='.$_GET['id'].'&etatentre=ok');
	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}

// ----------------------------------
// traitement facture			#6
// ---------------------------------

if(isset($_POST['submit_f']))
{
	$row=addFac($pdoLitige);
	if($row==1)
	{
		$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&etatfac=ok';
		header($loc);
	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}
if(isset($_POST['submit_mail']))
{
	$row=addFac($pdoLitige);
	if($row==1)
	{
		if(!empty($_POST['fac_mag']))
		{

			if(VERSION =='_')
			{
				$mailMag=array('valerie.montusclat@btlec.fr');
			}
			else{

				$magDao=new MagDao($pdoMag);
				$infoMag=$magDao->getMagByGalec($fLitige['galec']);
				$codeBt=$infoMag->getId();
				$mailMag=array($codeBt.'-rbt@btlec.fr');
			}
			if($_POST['mt_mag']<0){
				$type='un avoir ';
				$mt=abs($_POST['mt_mag']);
			}else{
				$type='une facture ';
				$mt=$_POST['mt_mag'];

			}


			$magTemplate = file_get_contents('mail/mail-mag-fac.php');
			$magTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$magTemplate);
			$magTemplate=str_replace('{FACTURE}',$_POST['fac_mag'],$magTemplate);
			$magTemplate=str_replace('{TYPE}',$type,$magTemplate);
			$magTemplate=str_replace('{MONTANT}',$mt,$magTemplate);
			$subject='Portail BTLec Est  - information litige ' . $fLitige['dossier'];
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($magTemplate, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
			->setTo($mailMag)
			->setBcc(['btlecest.portailweb.litiges@btlec.fr']);
			$delivered=$mailer->send($message);
			if($delivered >0)
			{
				$action='envoi du mail avec le numéro de facture/avoir';
				$add=addAction($pdoLitige, $action);
				if($add==1)
				{
					$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&etatfac=ok';
					header($loc);
				}
				else{
					$errors[]='l\'action n\'a pas pu être enregistrée';
				}


			}
			else
			{
				$errors[]='Le mail n\'a pas pu être envoyé';
			}
		}
		else
		{
			$errors[]='Impossible d\'envoyer un mail si le numéro de facture n\'est pas renseigné';
		}

	}
	else
	{
		$errors[]="impossible de mettre à jour la base de donnée";
	}
}
// ----------------------------------
// traitement etat			#7
// ---------------------------------

if(isset($_GET['etatTransp']))
{
	$etatTransp="etat-vert";
}
if(isset($_GET['etatentre']))
{
	$etatentre="etat-vert";
}
if(isset($_GET['etatfac']))
{
	$etatfac="etat-vert";
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
	<div class="row py-3">
		<div class="col">
			<p class="text-right"><a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a></p>
		</div>
	</div>

	<h1 class="text-main-blue pb-5 ">Ajout d'informations au dossier N° <?= $fLitige['dossier']?></h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col">
			<!-- inside transporteurs -->
			<div class="row">
				<div class="col bg-alert-yellow">
					<div class="row">
						<div class="col">
							<h3 class="pt-2"><img src="../img/litiges/transport.png" class="pr-3">Informations relatives au transport</h3>
						</div>
						<div class="col-auto text-right pt-2">
							<i class="fas fa-save fa-lg <?= $etatTransp ?>"></i>
						</div>
					</div>

					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >


						<div class="row">
							<div class="col">
								<div class="form-group">
									<label>Transporteur :</label>
									<select class="form-control" name="transporteur">
										<option value="">Sélectionner</option>
										<?php
										foreach($transporteurs as $transp)
										{
											echo '<option value="'.$transp['id'].'"' ;
											if(isset($fLitige['id_transp']) && $fLitige['id_transp']==$transp['id'])
											{
												echo ' selected';
											}

											echo '>'.$transp['transporteur'].'</option>';
										}

										?>

									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label>Affreteur :</label>
									<select class="form-control" name="affrete">
										<option value="">Sélectionner</option>
										<?php
										foreach($affretes as $affrete)
										{
											echo '<option value="'.$affrete['id'].'"';
											if(isset($fLitige['id_affrete']) && $fLitige['id_affrete']==$affrete['id'])
											{
												echo ' selected';
											}

											echo '>'.$affrete['affrete'].'</option>';
										}

										?>

									</select>
								</div>
							</div>
							<!-- transit oui/non -->
							<div class="col">
								Passage à quai :
								<div class="form-check pt-3">
									<?php
									$isChecked="";
									$phpClass="hidden";
									if(isset($fLitige['transitok']) && $fLitige['transitok']=="oui")
									{
										$isChecked="checked";
										$phpClass="show";
									}

									?>
									<input class="form-check-input" type="checkbox" value="" id="transit_check" name="transit_check"  <?= $isChecked ?>>
									<label class="form-check-label" for="transit_check">Oui</label>
								</div>
							</div>

							<div class="col">
								<div class="<?=$phpClass?>" id="toogle_transit">
									<div class="form-group" >
										<label>Transit : </label>
										<select class="form-control" name="transit">
											<option value="">Sélectionner</option>
											<?php
											foreach($transits as $transit)
											{
												echo '<option value="'.$transit['id'].'"';
												if(isset($fLitige['id_transit']) && $fLitige['id_transit']==$transit['id'])
												{
													echo ' selected';
												}
												echo '>'.$transit['transit'].'</option>';
											}

											?>

										</select>
									</div>
								</div>
							</div>

						</div>
						<div class="row pb-3">
							<div class="col-3">
								<div class="form-check pb-3">
									<?php
									$isChecked="";
									$phpClass="hidden";
									if(isset($fLitige['vingtquatre']) && $fLitige['vingtquatre']==1){
										$isChecked="checked";

									}

									?>
									<input class="form-check-input" type="checkbox" value="" id="vingtquatre" name="vingtquatre"  <?= $isChecked ?>>
									<label class="form-check-label" for="vingtquatre">livraison 24/48h</label>
								</div>
							</div>
							<div class="col-5">
								<div class="form-check pb-3">
									<?php
									$isChecked="";
									$phpClass="hidden";
									if(isset($fLitige['esp']) && $fLitige['esp']==1){
										$isChecked="checked";

									}

									?>
									<input class="form-check-input" type="checkbox" value="" id="esp" name="esp"  <?= $isChecked ?>>
									<label class="form-check-label" for="esp">livraison 24/48h ESP</label>
								</div>
							</div>
							<div class="col">
								<div class="text-center">
									<button type="submit" id="submit_t" class="btn btn-yellow" name="submit_t"><i class="fas fa-save pr-3"></i>Enregistrer</button>
								</div>
							</div>
						</div>
					</form>


				</div>
			</div>
			<!-- ENTREPOT -->
			<div class="row mt-3">
				<div class="col border bg-alert-primary">
					<div class="row">
						<div class="col">
							<h3 class="mt-2"><img src="../img/litiges/warehouse.png" class="pr-3">Informations relatives à l'entrepôt</h3>
						</div>
						<div class="col-auto text-right pt-2">
							<i class="fas fa-save fa-lg  <?=$etatentre ?>"></i>
						</div>
					</div>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >

						<div class="row">
							<!-- col 1 entrepot-->
							<div class="col">
								<div class="form-group">
									<label>Préparateur</label>
									<select class="form-control" name="preparateur">
										<option value="">preparateur</option>
										<?php
										foreach($equipes as $equipe)
										{
											echo '<option value="'.$equipe['id'].'"';
											if(isset($fLitige['id_prepa']) && $fLitige['id_prepa']==$equipe['id'])
											{
												echo ' selected';
											}
											echo '>'.$equipe['name'].'</option>';
										}
										?>

									</select>
								</div>
							</div>
							<!-- col2 -->
							<div class="col">
								<div class="form-group">
									<label>Contrôleur</label>
									<select class="form-control" name="controleur">
										<option value="">controleur</option>
										<?php
										foreach($equipes as $equipe)
										{
											echo '<option value="'.$equipe['id'].'"';
											if(isset($fLitige['id_ctrl']) && $fLitige['id_ctrl']==$equipe['id'])
											{
												echo ' selected';
											}
											echo '>'.$equipe['name'].'</option>';
										}
										?>
									</select>
								</div>
							</div>
							<!-- col3 -->
							<div class="col">
								<div class="form-group">
									<label>Chargeur</label>
									<select class="form-control" name="chargeur">
										<option value="">Chargeur</option>
										<?php
										foreach($equipes as $equipe)
										{
											echo '<option value="'.$equipe['id'].'"';
											if(isset($fLitige['id_chg']) && $fLitige['id_chg']==$equipe['id'])
											{
												echo ' selected';
											}
											echo '>'.$equipe['name'].'</option>';
										}
										?>
									</select>
								</div>
							</div>
							<!-- col4 -->
							<div class="col"></div>
						</div>
						<!-- row 2 entrepot -->
						<div class="row">
							<div class="col">
								<div class="form-group">
									<?php
									if(isset($fLitige['date_prepa']))
									{
										$datePrepa=date('Y-m-d',strtotime($fLitige['date_prepa']));
									}
									else
									{
										$datePrepa="";
									}
									?>
									<label>Date de la prépa</label>
									<input type="date" name="date_prepa" class="form-control" value="<?=$datePrepa?>">

								</div>
							</div>


							<div class="col"></div>
							<div class="col">
								<div class="pt-4 mt-2 text-center">
									<button type="submit" id="submit_e" class="btn btn-primary" name="submit_e"><i class="fas fa-save pr-3"></i>Enregistrer</button>
								</div>
							</div>
						</div>

					</form>

				</div>
			</div>
			<!-- FACTURATION -->
			<div class="row mt-3">
				<div class="col bg-kaki-light">
					<div class="row">
						<div class="col">
							<h3 class="mt-2"><img src="../img/litiges/invoice.png" class="pr-3">Facturation</h3>
						</div>
						<div class="col-auto text-right pt-2">
							<i class="fas fa-save fa-lg <?= $etatfac ?>"></i>
						</div>
					</div>
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" id="fac_form">
						<div class="row">
							<!-- col 1 facturation-->
							<div class="col-6">
								<div class="form-group row">
									<?php
									if(isset($fLitige['mt_transp']))
									{
										$mt_transp=$fLitige['mt_transp'];
									}
									else
									{
										$mt_transp="";
									}
									?>
									<label class="col-6">Réglement transporteur :</label>
									<input type="text" name="mt_transp" class="text-right" value="<?=$mt_transp?>">
								</div>
							</div>
							<div class="col">
								<p class="bigger heavy text-blue text-center">Coût du litige BTLec : <?= number_format((float)$coutTotal,2,'.','')?> &euro;</p>

							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
									<?php
									if(isset($fLitige['mt_assur']))
									{
										$mt_assur=$fLitige['mt_assur'];
									}
									else
									{
										$mt_assur="";
									}
									?>
									<label class="col-6">Réglement assurance :</label>
									<input type="text"  class="text-right" name="mt_assur" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" value="<?= $mt_assur ?>">
								</div>
							</div>
							<div class="col"></div>

						</div>

						<div class="row">
							<!-- col 1 facturation-->
							<div class="col-6">
								<div class="form-group row">
									<?php
									if(isset($fLitige['mt_fourn']))
									{
										$mt_fourn=$fLitige['mt_fourn'];
									}
									else
									{
										$mt_fourn="";
									}
									?>
									<label class="col-6">Réglement fournisseur : </label>
									<input type="text" name="mt_fourn"  class="text-right" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" value="<?= $mt_fourn ?>">
								</div>
							</div>
							<div class="col"></div>
						</div>
						<div class="row">
							<div class="col-6">
								<div class="form-group row">
									<?php
									if(isset($fLitige['mt_mag']))
									{
										$mt_mag=$fLitige['mt_mag'];
									}
									else
									{
										$mt_mag="";
									}
									?>
									<label class="col-6">Réglement magasin : </label>
									<input type="text" name="mt_mag"  class="text-right" pattern="[-+]?[0-9]*[.]?[0-9]+" title="Seuls les chiffres sont autorisés" value="<?= $mt_mag ?>">
								</div>
							</div>
							<div class="col-6">
								<div class="form-group row">
									<?php
									if(isset($fLitige['fac_mag']))
									{
										$fac_mag=$fLitige['fac_mag'];
									}
									else
									{
										$fac_mag="";
									}
									?>
									<label class="col-6">N° avoir magasin :</label>
									<input type="text"  class="text-right" name="fac_mag" value="<?= $fac_mag ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
							</div>
							<div class="col">
								<div class="mb-3 text-right">
									<button type="submit" id="submit_f" class="btn btn-kaki" name="submit_f"><i class="fas fa-save pr-3"></i>Enregistrer</button>
									<button type="submit" id="submit_mail" class="btn btn-red" name="submit_mail"><i class="fas fa-envelope pr-3"></i>Enregistrer et envoyer le mail</button>
								</div>
							</div>

						</div>


					</form>
				</div>
			</div>



			<div class="row my-5">
				<div class="col-lg-1 col-xxl-2"></div>
				<div class="col mb-5">



				</div>
				<div class="col-lg-1 col-xxl-2"></div>
			</div>
		</div>
	</div>



</div>
<script type="text/javascript">
	$(document).ready(function(){
		// affichage ou non de l'input pur le transit
		$('#transit_check').change(function(){
			if($(this).prop("checked")) {
				// $('#toogle_transit').show();
				$('#toogle_transit').attr('class','show');

			} else {
				$('#toogle_transit').attr('class', 'hidden');
			}
		});

	});
</script>

<?php

require '../view/_footer-bt.php';

?>