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


require_once  '../../vendor/autoload.php';

// if($_SESSION['type']=='btlec'){
// 	unset($_SESSION['id_galec']);
// 	if(isset($_SESSION['palette']))
// 	{
// 		unset($_SESSION['palette']);
// 		unset($_SESSION['vol-id']);

// 	}
// 	if(isset($_SESSION['dossier_litige'])){
// 		$numDossier=$_SESSION['dossier_litige'];
// 		unset($_SESSION['dossier_litige']);

// 	}
// }


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// le numéro de dossier du litige et non l'id du litige
function getLastNumDossier($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT dossier FROM dossiers ORDER BY dossier DESC LIMIT 1");
	$req->execute();
	return $req->fetch(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}



function getLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT dossiers.id as id, dossier, mag, btlec FROM dossiers LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$fLitige=getLitige($pdoLitige);

function getSumLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT sum(valo_line) as sumValo, dossiers.valo, id_reclamation FROM details LEFT JOIN dossiers ON details.id_dossier= dossiers.id WHERE details.id_dossier= :id");
	$req->execute([
		':id'		=>$_GET['id']
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getSumPaletteRecu($pdoLitige){
	$req=$pdoLitige->prepare("SELECT sum(tarif) as sumValo FROM palette_inv  WHERE palette_inv.id_dossier= :id");
	$req->execute([
		':id'		=>$_GET['id']
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updateValoDossier($pdoLitige,$sumValo){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo WHERE id= :id");
	$req->execute([
		':valo'			=>$sumValo,
		':id'			=>$_GET['id']
	]);
	return $req->rowCount();
}
// on a normalement toute les infos donc on peut recopier de la table temp à la table de prod

if(isset($_GET['id']))
{
	// on profite du recap pour calculer la valo totale d'un litige
// 2 cas
// normal : somme,
// inversion de palette = somme palette cammandé - sommme palette reçue
// inversion de palette =7
$sumLitige=getSumLitige($pdoLitige);

if($sumLitige['id_reclamation']==7)
{
	$sumRecu=getSumPaletteRecu($pdoLitige);
	$sumCde=$sumLitige['sumValo'];
	$sumRecu=$sumRecu['sumValo'];
	$sumValo=$sumCde -$sumRecu;
	$update=updateValoDossier($pdoLitige,$sumValo);

}
else{
	$sumValo=$sumLitige['sumValo'];
	$update=updateValoDossier($pdoLitige,$sumValo);
}

}






$errors=[];
$success=[];
















//------------------------------------------------------
//			PRINCIPE ENVOI DE MAIL
//------------------------------------------------------
/*
1- on vérifie si on est sur le site de prod ou de dev,
	=> site de dev => adresses = moi
	=> site de prod, si code bt du litige n'est pas celui du mag de test,
	les adresses sont les adresses de prod, sinon adresses de dev
2- on vérifie qui est connecté
	=> si c'est un mag
		on envoi le mail
	=> si ce n'est pas un mag, on n'envoie pas de mail
3- si le user connecté a les droits exploit litige, il a alors un bouton
lui permettant de forcer l'envoi de mail
(mail différent => 3 modeles de mail : un pour mag qui déclare son litige, un pour avertir bt nouveau litige,
un pour mag qd bt déclare un litige  à  sa place)
*/
//------------------------------------------------------
//			ENVOI MAIL
//------------------------------------------------------
// mail mag = 4444-rbt@btlec.fr
if(VERSION =='_')
{
	$mailMag=array('valerie.montusclat@btlec.fr');
	$mailBt=array('valerie.montusclat@btlec.fr');
}
else
{
	if($_SESSION['code_bt']!='4201')
	{
		$mailMag=array($fLitige['btlec'].'-rbt@btlec.fr');
		$mailBt=array('litigelivraison@btlec.fr');
	}
	else
	{
		$mailMag=array('valerie.montusclat@btlec.fr');
		$mailBt=array('valerie.montusclat@btlec.fr');
	}
}



if($_SESSION['type']=='mag')
{
// ---------------------------------------
// gestion du template mag
	$magTemplate = file_get_contents('mail-mag-new-litige.php');
	$magTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$magTemplate);
	$subject='Portail BTLec Est  - ouverture du dossier litige ' . $fLitige['dossier'];
// ---------------------------------------
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($magTemplate, 'text/html')
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
	->setTo($mailMag)
	->addBcc('valerie.montusclat@btlec.fr');
	$delivered=$mailer->send($message);
	if($delivered >0)
	{
	}
	else
	{
		$errors[]='Nous n\'avons pas pu vous faire parvenir le mail accusant réception de votre dossier';
	}


// ---------------------------------------
// gestion du template mag
	$btTemplate = file_get_contents('mail-bt-new-litige.php');
	$btTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$btTemplate);
	$btTemplate=str_replace('{MAG}',$fLitige['mag'],$btTemplate);
	$btTemplate=str_replace('{BTLEC}',$fLitige['btlec'],$btTemplate);
	$subject='Portail BTLec Est  - nouveau dossier litige ' . $fLitige['dossier'];
// ---------------------------------------
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($btTemplate, 'text/html')
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
	->setTo($mailBt)
	->addBcc('valerie.montusclat@btlec.fr');
	$delivered=$mailer->send($message);
	if($delivered >0)
	{
	}
	else
	{
		$errors[]='Le mail n\'a pas pu être envoyé à notre service livraison';
	}
}




if(isset($_POST['submit']))
{
	$mailMag=array($fLitige['btlec'].'-rbt@btlec.fr');
	$magTemplate = file_get_contents('mail-mag-force-litige.php');
	$magTemplate=str_replace('{DOSSIER}',$fLitige['dossier'],$magTemplate);
	$subject='Portail BTLec Est  - ouverture du dossier litige ' . $fLitige['dossier'];
	// ---------------------------------------
	$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
	$mailer = new Swift_Mailer($transport);
	$message = (new Swift_Message($subject))
	->setBody($magTemplate, 'text/html')
	->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
	->setTo($mailMag)
	->setBcc(['valerie.montusclat@btlec.fr', 'btlecest.portailweb.logistique@btlec.fr']);
	$delivered=$mailer->send($message);
	if($delivered >0)
	{
		$success[]='mail envoyé avec succès à '.$mailMag[0];
	}
	else
	{
		$errors[]='Nous n\'avons pas pu vous faire parvenir le mail accusant réception de votre dossier';
	}
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

	<p class="text-right pt-1">
		<h1 class="text-main-blue py-5 ">Ouverture du dossier de litige n° <?=$fLitige['dossier']?></h1>
		<?php
		ob_start();
		?>
		<div class="row">
			<div class="col-lg-1 col-xxl-2"></div>
			<div class="col">
				<div class="alert alert-success">
					Votre dossier litige <strong>n° <?= $fLitige['dossier'] ?> </strong>a été transmis à BTLec pour étude. <br> Vous pourrez consulter l'avancement de votre dossier sur le portail sous cette référence.
				</div>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>
		<?php
		$magContent=ob_get_contents();
		ob_end_clean();
		ob_start();
		?>
		<div class="row">
			<div class="col-lg-1 col-xxl-2"></div>
			<div class="col">
				<div class="alert alert-success">
					<div class="row">
						<div class="col">
							Le dossier litige <strong>n° <?= $fLitige['dossier'] ?> </strong>a bien été enregistré. Souhaitez vous envoyer le mail pour avertir le magasin ?
						</div>
					</div>
					<div class="row">
						<div class="col">
							<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" >
								<div class="text-center pt-3">
									<button type="submit" id="submit" class="btn btn-secondary" name="submit"><i class="fas fa-envelope pr-3"></i>Envoyer</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>
		<?php

		$exploitLitigeContent=ob_get_contents();
		ob_end_clean();
		if($d_litigeBt)
		{
			echo $exploitLitigeContent;
		}
		else
		{
			echo $magContent;
		}


		?>
		<!-- ./row -->
		<div class="row">
			<div class="col-lg-1 col-xxl-2"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>

		</div>





	</div>
	<?php
	require '../view/_footer-bt.php';
	?>