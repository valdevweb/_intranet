<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//----------------------------------------------------------------

require '../../Class/BtUserManager.php';

require '../../functions/mail.fn.php';
require "../../functions/stats.fn.php";




//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

function addMsg($db,$id_service,$inc_file){
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$db->prepare('INSERT INTO msg (objet, msg, id_mag, id_service, date_msg, etat,inc_file,who,email, id_galec,code_bt,centrale)
		VALUE(:objet, :msg, :id_mag, :id_service, :date_msg, :etat, :inc_file, :who, :email, :id_galec, :code_bt, :centrale)');
	$req->execute(array(
		':objet'		=> strip_tags($_POST['objet']),
		':msg'			=> $msg,
		':id_mag'		=> strip_tags($_SESSION['id']),
		':id_service'	=> $id_service,
		':date_msg'		=>date('Y-m-d H:i:s'),
		':etat'			=> "en attente de réponse",
		':inc_file'		=>$inc_file,
		':who'			=>strip_tags($_POST['name']),
		':email'		=>strip_tags($_POST['email']),
		':id_galec'		=>$_SESSION['id_galec'],
		':code_bt'		=>$_SESSION['code_bt'],
		':centrale'		=>$_SESSION['centrale']
	));
	$req->fetch(PDO::FETCH_ASSOC);
	return $db->lastInsertId();
}




//----------------------------------------------------------------
//			affichage : infos du services
//----------------------------------------------------------------
$userManager=new BtUserManager();
$service=$userManager->getService($pdoUser,$_GET['id']);
$serviceMembers=$userManager->getListUserService($pdoBt,$_GET['id']);


$tplForBtlec="../mail/new_mag_msg.tpl.html";
$tplForMag="../mail/ar_mag.tpl.html";
mb_internal_encoding('UTF-8');
$objBt="PORTAIL BTLec - nouvelle demande : " .$_SESSION['nom'] ." pour le service " . mb_encode_mimeheader($service['service']);
$objMag="PORTAIL BTLec - demande envoyée";
mb_internal_encoding('UTF-8');
$objMag = mb_encode_mimeheader($objMag);

$uploadDir= DIR_UPLOAD. 'mag\\';



$descr="page demande mag au service ".$service['slug'] ;
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);


// templates et sujet des mails envoyés à la soumission du formulaire
// un mail informer le service qu'une demande à été posté
// un mail au mag pour lui confirmer que sa demande a été envoyé



//test valeur $_FILE, si renvoi true => au moins un fichier à uploader

//----------------------------------------------------------------
//			traitement formulaire : ajout à db et upload si fichier
//----------------------------------------------------------------
//initialisation des tableau de message d'erreur de succès
$errors=[];
$success=[];
$fileList="";
//soumission du formulaire
if(isset($_POST['post-msg'])){
	extract($_POST);
	// en dehors du file aucun champ ne doit être vide
	if(empty($objet) || empty($msg) || empty($name) || empty($email)){
		$errors[]= "merci de remplir tous les champs";
	}

	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errors[]='Veuillez indiquez une adresse email valide';
	}
	//formulaire conforme
	if(empty($errors)){
		for($i=0;$i<count($_FILES['file']['name']) ;$i++){
			if($_FILES['file']['name'][$i]!=""){
				$filename=$_FILES['file']['name'][$i];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$filenameNoExt = basename($filename, '.'.$ext);
				$filenameNoExt=str_replace(" ","_",$filenameNoExt);
				$filenameNoExt=str_replace(";","",$filenameNoExt);

				$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;
				if($fileList==""){
					$fileList= $filenameNew;

				}else{
					$fileList= $fileList.'; '.$filenameNew;
				}
				$uploaded=move_uploaded_file($_FILES['file']['tmp_name'][$i],$uploadDir.$filenameNew );
				if($uploaded==false){
					$errors[]="Impossible d'ajouter la pièce jointe";
				}

			}
		}
	}


		//------------------------------
		//			TRAITEMENT COMMUN
		//			ajoute le msg dans db et
		//			recup l'id du msg posté pour génération lien dans le mail : index.php?$lastId
		//------------------------------

	if(count($errors)==0){
		if($lastId=addMsg($pdoBt,$_GET['id'], $fileList)){
				//créa du lien pour le mail  BT
			$link="Cliquez <a href='" .SITE_ADDRESS."/index.php?btlec/answer.php?msg=".$lastId."'>ici pour consulter le message</a>";
			$linkMag="Cliquez <a href='".SITE_ADDRESS."/index.php?mag/edit-msg.php?msg=".$lastId."'>ici pour revoir votre demande</a>";
				//------------------------------
				//			ajout enreg dans stat
				//------------------------------
			$descr="demande mag au service ".$service['slug'] ;
			$page=basename(__file__);
			$action="envoi d'une demande";
			addRecord($pdoStat,$page,$action, $descr);
				//-----------------------------------------
				//				envoi des mails
				//-----------------------------------------
			if(VERSION=="_"){
				$dest="valerie.montusclat@btlec.fr";
			}else{
				$dest=$service['mailing'];

			}
				// echo "<pre>";
				// print_r($dest);
				// echo '</pre>';

			if(sendMail($dest,$objBt,$tplForBtlec,$name,$_SESSION['nom'], $link))
			{
				array_push($success,"Email envoyé avec succès");
				$contentTwo="";
				sendMail($email,$objMag,$tplForMag,$service['service'],$contentTwo,$linkMag);
					//on vide le formulaire et on redirige sur la page histo demande mag
				unset($objet,$msg,$name,$email);
				header('Location:'. ROOT_PATH. '/public/mag/histo-mag.php');



			}
			else
			{
				$errors[]= "Echec d'envoi d'email";
			}
		}
		else{
			$errors[]="Echec : votre demande n'a pas pu être enregistrée";
		}

	}
}

















//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('contact.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');

