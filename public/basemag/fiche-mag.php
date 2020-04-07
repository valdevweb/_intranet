<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
require_once '../../vendor/autoload.php';
require_once '../../Class/MagDbHelper.php';
require_once '../../Class/Mag.php';
require_once '../../Class/Helpers.php';
require_once '../../Class/UserHelpers.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "fiche mag", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
// $searchResults=false;
function convertArray($data, $field,$separator){
	if(!empty($data)){
		if (preg_match('</a>', $separator)){
			$rValue='';
			foreach ($data as $key => $value) {
				$rValue.='<a class="text-norm" href="mailto:'.$value[$field].'">'.$value[$field].$separator;
			}
			return $rValue;
		}else{
			$rValue='';
			foreach ($data as $key => $value) {
				$rValue.=$value[$field].$separator;
			}
			return $rValue;
		}
	}
	return '';
}

function updateSca($pdoMag){
	$req=$pdoMag->prepare("UPDATE sca3 SET galec_sca= :galec_sca, deno_sca= :deno_sca, ad1_sca= :ad1_sca, ad2_sca= :ad2_sca, ad3= :ad3_sca, cp_sca= :cp_sca, ville_sca= :ville_sca, tel_sca= :tel_sca, fax_sca= :fax_sca, adherent_sca= :adh_sca, centrale_sca= :centrale_sca, centrale_doris= :centrale_doris, centrale_smiley= :centrale_smiley, surface_sca= :surface_sca, sorti= :sorti, date_ouverture= :date_ouverture, date_adhesion= :date_adhesion, date_fermeture= :date_fermeture, date_resiliation= :date_resiliation, date_sortie= :date_sortie, pole_sav_sca= :pole_sav_sca, nom_gesap= :gesap, affilie= :affilie, date_update= :date_update WHERE btlec_sca= :btlec_sca");

	$req->execute([
		':btlec_sca'		=>$_GET['id'],
		':galec_sca'		=>$_POST['galec_sca'],
		':deno_sca'		=>$_POST['deno_sca'],
		':ad1_sca'		=>$_POST['ad1_sca'],
		':ad2_sca'		=>$_POST['ad2_sca'],
		':ad3_sca'		=>$_POST['ad3_sca'],
		':cp_sca'		=>$_POST['cp_sca'],
		':ville_sca'		=>$_POST['ville_sca'],
		':tel_sca'		=>$_POST['tel_sca'],
		':fax_sca'		=>$_POST['fax_sca'],
		':adh_sca'		=>$_POST['adh_sca'],
		':centrale_sca'		=>(empty($_POST['centrale_sca']))? NULL:$_POST['centrale_sca'] ,
		':centrale_doris'		=>(empty($_POST['centrale_doris']))? NULL :$_POST['centrale_doris'],
		':centrale_smiley'		=>(empty($_POST['centrale_smiley']))? NULL :$_POST['centrale_smiley'],
		':surface_sca'		=>$_POST['surface_sca'],
		':sorti'		=>$_POST['sorti'],
		':date_ouverture'		=>(empty($_POST['date_ouverture']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_ouverture']) )->format('Y-m-d'),
		':date_adhesion'		=>(empty($_POST['date_adhesion']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_adhesion']))->format('Y-m-d'),
		':date_fermeture'		=>(empty($_POST['date_fermeture']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_fermeture']))->format('Y-m-d'),
		':date_resiliation'		=>(empty($_POST['date_resiliation']))? NULL:(DateTime::createFromFormat('d/m/Y',$_POST['date_resiliation']))->format('Y-m-d'),
		':date_sortie'		=>(empty($_POST['date_sortie']))? NULL:( DateTime::createFromFormat('d/m/Y',$_POST['date_sortie']))->format('Y-m-d'),
		':pole_sav_sca'		=>(empty($_POST['pole_sav_sca']))? NULL:$_POST['pole_sav_sca'],
		':gesap'		=>$_POST['gesap'],
		':affilie'		=>$_POST['affilie'],
		':date_update'	=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
}

function updateDocubase($pdoMag){
	$req=$pdoMag->prepare("UPDATE sca3 SET docubase_login= :docubase_login, docubase_pwd= :docubase_pwd WHERE btlec_sca= :id");
	$req->execute([
		':id'	=>$_GET['id'],
		':docubase_login'		=>$_POST['docubase_login'],
		':docubase_pwd'		=>$_POST['docubase_pwd']
	]);
	return $req->rowCount();
}



if(isset($_POST['clear_form'])){
	$_POST=[];
	header("Location: ".$_SERVER['PHP_SELF']);

}
if (isset($_GET['id'])){
	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_GET['id']);
	$histo= $magDbHelper->getHisto($mag->getGalec());
	$listCentralesSca=$magDbHelper->getDistinctCentraleSca();
	$webusers=$magDbHelper->getWebUser($mag->getGalec());
	$centreRei=$magDbHelper->centreReiToString($mag->getCentreRei());
	$listTypesMag=$magDbHelper-> getListType();
	$listCm=UserHelpers::getUserByService($pdoUser, 17);

	// ld
	$ldRbt=$magDbHelper-> getMagLd($mag->getGalec(),'-RBT');
	$ldRbtName=(!empty($ldRbt))? '<a class="text-orange" href="mailto:'.$ldRbt[0]['ld_full'].'">'.$ldRbt[0]['ld_full'].'</a>':  $mag->getRacineList()."-RBT";
	$ldRbtLink=convertArray($ldRbt,'email','</a><br>');
	$ldRbtExist=(!empty($ldRbt))? true:  false;
	$ldRbtLink=(!empty($ldRbtLink))? $ldRbtLink:  "Aucune adresse RBT";

	$ldDir=$magDbHelper-> getMagLd($mag->getGalec(),'-DIR');
	$ldDirName=(!empty($ldDir))? '<a class="text-orange" href="mailto:'.$ldDir[0]['ld_full'].'">'.$ldDir[0]['ld_full'].'</a>':  $mag->getRacineList()."-DIR";
	$ldDirLink=convertArray($ldDir,'email','</a><br>');
	$ldDirExist=(!empty($ldDir))? true:  false;
	$ldDirLink=(!empty($ldDirLink))? $ldDirLink : "Aucune adresse directeur";

	$ldAdh=$magDbHelper-> getMagLd($mag->getGalec(),'-ADH');
	$ldAdhName=(!empty($ldAdh))? '<a class="text-orange" href="mailto:'.$ldAdh[0]['ld_full'].'">'.$ldAdh[0]['ld_full'].'</a>':  $mag->getRacineList()."-ADH :";
	$ldAdhLink=convertArray($ldAdh,'email','</a><br>');
	$ldAdhExist=(!empty($ldAdh))? true:  false;
	$ldAdhLink=(!empty($ldAdhLink))? $ldAdhLink: "Aucune adresse adhérent dans ";

	$docubaseLink="http://172.30.101.66/rheaweb/controler?cmd=home&baseid=1&view=1&j_username=".$mag->getDocubaseLogin() ."&j_password=".$mag->getDocubasePwd();


	if(!empty($mag->getCentrale())){
		// $centraleGessica=$mag->getCentrale();
		$centraleGessica=$magDbHelper->centraleToString($mag->getCentrale());
	}else{
		$centraleGessica="Pas de centrale renseignée";
	}
	if(!empty($mag->getCentraleSca())){
		// $centraleGessica=$mag->getCentrale();
		$centraleSca=$magDbHelper->centraleToString($mag->getCentraleSca());
	}else{
		$centraleSca="Pas de centrale renseignée";
	}

	$ad2=!empty($mag->getAd2()) ? $mag->getAd2().'<br>' :'';


}

if(isset($_GET['id'])){
	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_GET['id']);

}

if(isset($_POST['maj'])){
	$up=updateSca($pdoMag);
	if(count($up)==1){
		$successQ='success=maj';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
	}else{
		$errors[]="Une erreur est survenue, impossible de mettre  à jour la base de donnée";
	}
}

if(isset($_POST['submit_acdlec'])){
	$req=$pdoMag->prepare("INSERT INTO acdlec (code, nom_ets) VALUES(:code, :nom_ets)");
	$req->execute([
		':code'	=>$_POST['code'],
		':nom_ets'	=>$_POST['nom']
	]);
	$added=$req->rowCount();
	if($added==1){
		$successQ='success=majacdlec';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
	}else{
		$error=$req->errorInfo();
		$errors[]="impossible d'ajouter le code : ".$error[2];
	}
}

if(isset($_POST['submit_cm'])){
	if(!empty($_POST['cmSelected'])){
		//  vérifier si adéja attribution
		$req=$pdoMag->prepare("UPDATE mag SET id_cm_web_user= :id_cm_web_user, date_update= :date_update WHERE id= :id");
		$req->execute([
			':id'		=>$_GET['id'],
			':id_cm_web_user'	=>$_POST['cmSelected'],
			':date_update'	=>date('Y-m-d H:i:s')
		]);
		$updated=$req->rowCount();


		if($updated==1){
			$successQ='success=majcm';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
		}else{
			$error=$req->errorInfo();

			$errors[]="impossible de mettre à jour la base de donnée : " . $error[2];
		}
	}
}

if(isset($_POST['submit_crea'])){

	$req=$pdoMag->prepare("INSERT INTO sca3 (btlec_sca, galec_sca, deno_sca, ad1_sca, ad2_sca, cp_sca, ville_sca, tel_sca, fax_sca, centrale_sca, surface_sca, date_ouverture, pole_sav_sca, adherent_sca, sorti) VALUES (:btlec_sca, :galec_sca, :deno_sca, :ad1_sca, :ad2_sca, :cp_sca, :ville_sca , :tel_sca, :fax_sca, :centrale_sca, :surface_sca, :date_ouverture, :pole_sav_sca, :adherent_sca, :sorti)");
	$req->execute([
		':btlec_sca'			=>$mag->getId(),
		':galec_sca'			=>$mag->getGalec(),
		':deno_sca'				=>$mag->getDeno(),
		':ad1_sca'				=>$mag->getAd1(),
		':ad2_sca'				=>$mag->getAd2(),
		':cp_sca'				=>$mag->getCp(),
		':ville_sca'			=>$mag->getVille(),
		':tel_sca'				=>$mag->getTel(),
		':fax_sca'				=>$mag->getFax(),
		':centrale_sca'			=>$mag->getCentrale(),
		':surface_sca'			=>$mag->getSurface(),
		':date_ouverture'		=>$mag->getDateOuv(),
		':pole_sav_sca'			=>$mag->getPoleSavGessica(),
		':adherent_sca'			=>$mag->getAdherent(),
		':sorti'				=>$mag->getGel(),
	]);


	$inserted=$req->rowCount();
	if($inserted==1){
		$successQ='success=ajoutmag';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
	}else{
		$error=$req->errorInfo();
		echo "<pre>";
		print_r($error);
		echo '</pre>';

		$errors[]="impossible d'ajouter le magasin : ".$error[2];
	}
}

if(isset($_POST['submitdocubase'])){

	// maj db dans tous les cas
	$upDocubase=updateDocubase($pdoMag);
	// on envoie un mail avec les codes si dest n'est pas vide, sinon juste maj db
	$dest=[];
	if($upDocubase==1){
		if(isset($_POST['ldadh'])){
			foreach ($ldAdh as $key => $mail) {
				$dest[]=$mail['email'];
			}
		}
		if(isset($_POST['lddir'])){
			foreach ($ldDir as $key => $mail) {
				$dest[]=$mail['email'];
			}
		}
		if(isset($_POST['ldrbt'])){
			foreach ($ldRbt as $key => $mail) {
				$dest[]=$mail['email'];
			}
		}
		if(isset($_POST['emails']) && !empty($_POST['emails'])){
			$arrEmail=explode(',', $_POST['emails']);
			for ($i=0; $i < sizeof($arrEmail) ; $i++) {
				$dest[]=trim($arrEmail[$i]);
			}
		}
	}else{
		$errors[]="Une erreur est survenue, impossible de mettre à jour la base de donnée avec les codes docubase";
	}
	if(!empty($dest)){

		$cc=['clement.delahoche@bltec.fr', 'david.syllebranque@btlec.fr','valerie.montusclat@btlec.fr'];

		if(VERSION=="_"){
			$dest=['valerie.montusclat@btlec.fr'];
			$cc=[];
		}

		// gestion du template
		$htmlMail = file_get_contents('mail-codes-docubase.html');
		$htmlMail=str_replace('{LOGIN}',$_POST['docubase_login'],$htmlMail);
		$htmlMail=str_replace('{PWD}',$_POST['docubase_pwd'],$htmlMail);
		$subject='Portail BTLec Est - Codes docubase - Magasin '.$mag->getDeno();

		// ---------------------------------------
		// initialisation de swift
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec Est'))
		->setTo($dest)
		->setCc($cc);
		// ---------------------------------------
		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$successQ='?id='.$_GET['id'].'&success=udocmail';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}
	}elseif (empty($dest) && $upDocubase==1) {
		$successQ='?id='.$_GET['id'].'&success=udoc';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}

}


if(isset($_GET['success'])){
	$arrSuccess=[
		'maj'=>'Magasin mis à jour avec succès',
		'udocmail'=>'Mise à jour des codes docubases et envoi du mail effectués avec succès',
		'udoc'=>'Mise à jour des codes docubases effectuée avec succès',
		'majcm'=>'Attribution effectuée avec succès',
		'majacdlec'=>'Code ajouté avec succès',
		'ajoutmag'=>'infos magasin recopiée avec succès dans la table sca3'
	];
	$success[]=$arrSuccess[$_GET['success']];
}


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------



include('../view/_head-bt.php');

?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->

<!-- <section class="info-main pb-5 "> -->
	<section class="info-main fixed-top pb-5 ">
		<?php include('../view/_navbar.php');?>

		<div class="container">
			<div class="row">
				<div class="col-lg-1"></div>
				<div class="col">
					<?php
					include('../view/_errors.php');
					?>
				</div>
				<div class="col-lg-1"></div>
			</div>


			<?php if (!isset($mag)): ?>
				<div class="row  pb-3 ">
					<div class="col">
						<h1 class="text-main-blue pt-5 ">F</span>iche magasin</h1>
					</div>
					<?php
					include('search-form.php')
					?>
					<div class="col-auto mt-4 pt-2">
						<?=Helpers::returnBtn('base-mag.php','btn-kaki')?>
					</div>
					<!-- <div class="col-lg-1"></div> -->
				</div>
				<!-- ajout zone vide pour ne pas avoir le pied de page qui monte si pas de données -->
				<div class="force-height"></div>
				<?php else: ?>
					<div class="row pb-3 ">
						<div class="col">
							<h1 class="text-main-blue pt-5 ">
								<?= (isset($mag))? 'Leclerc '.$mag->getDeno(): "Fiche magasin" ?>
							</h1>

							<h5 class="yanone">Code BTLec : <span class="text-orange" ><?= $mag->getId() .'</span><span class="pl-5">Panonceau Galec : <span class="text-orange">'.$mag->getGalec().'</span>'?> <span class="text-orange pl-5">Centrale : </span><?=$centraleSca?> </h5>

						</div>
						<?php
						include('search-form.php')
						?>
						<div class="col-auto mt-3  pt-2">
							<?=Helpers::returnBtn('base-mag.php','btn-kaki')?>
						</div>
						<!-- <div class="col-lg-1"></div> -->
					</div>
				<?php endif?>
				<?php
				if (isset($mag)){
					include('fiche-mag-commun.php');
					if($d_strictAdmin){
						include('fiche-mag-exploit.php');
					}

				}
				?>

				<div class="fixed-zone">
					<div class="text-center font-weight-bold">
						<i class="fas fa-clipboard pr-2"></i>Aller à : Ctrl + Alt +
					</div>
					<div class="fixed-zone-row">
						<div class="fixed-zone-col">
							<span class="font-weight-bold"><u>d</u></span>ocubase edit<br>
							<span class="font-weight-bold"><u>e</u></span>xploitation<br>
						</div>
						<div class="fixed-zone-col">
							<span class="font-weight-bold"><u>i</u></span>dentifiants<br>
							<span class="font-weight-bold"><u>l</u></span>istes de diffusion<br>

						</div>
					</div>

				</div>


			</div>


			<script src="../js/autocomplete-searchmag.js"></script>

			<script type="text/javascript">

				function getScroll() {
					var position = $( document ).scrollTop();
					return position;
				}

				$(document).ready(function(){
	// masque pour saisie date et etc
	$('#date_ouverture').mask('00/00/0000');
	$('#date_fermeture').mask('00/00/0000');
	$('#date_adhesion').mask('00/00/0000');
	$('#date_resiliation').mask('00/00/0000');
	$('#date_sortie').mask('00/00/0000');
	$('#tel_sca').mask('00 00 00 00 00');

	// gestion du scroll qd met à jour sca3 avec une donnée de la base mag
	$('.btn-ico').on('click',function(){
		var position=getScroll();
					var oldUrl = $(this). attr("href"); // Get current url.
					$(this). attr("href", oldUrl+"&position="+position); // Set herf value.
				});
	window.onload = function () {
		var url = window.location.href;
		url=url.split("#");
		window.scrollTo(0, url[1]);
	}

	$("#email").keyup(function(){

		var email = $("#email").val();
		var filter = /^(([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)(\s*(;|,)\s*|\s*$))*$/;
		if (!filter.test(email)) {
			 //alert('Please provide a valid email address');
			 $("#error_email").text(email+" is not a valid email");
			 $("#error_email").addClass('text-alert');
			 email.focus;
			} else {
				$("#error_email").text("");
				$("#error_email").removeClass('text-alert');
			}
		});

	document.onkeyup = function(e) {
		if (e.ctrlKey && e.altKey  && e.which == 68) {
			$('html, body').animate({scrollTop: $('#docubase').offset().top -360 }, 'slow');
		}else if(e.ctrlKey && e.altKey  && e.which == 69){
			$('html, body').animate({scrollTop: $('#exploit').offset().top -360 }, 'slow');
		}else if (e.ctrlKey && e.altKey  && e.which == 73) {
			$('html, body').animate({scrollTop: $('#identifiants').offset().top -360 }, 'slow');
		}else if (e.ctrlKey && e.altKey  && e.which == 76) {
			$('html, body').animate({scrollTop: $('#ld').offset().top -360 }, 'slow');
		}
	};


});




</script>

<?php
require '../view/_footer-bt.php';
?>