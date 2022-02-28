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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
include '../../config/db-connect.php';


require_once '../../vendor/autoload.php';
require_once '../../Class/mag/MagDao.php';
require_once '../../Class/mag/MagEntity.php';
require_once '../../Class/Helpers.php';
require_once '../../Class/UserHelpers.php';
require_once '../../Class/UserDao.php';



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
	$req=$pdoMag->prepare("UPDATE sca3 SET galec_sca= :galec_sca, deno_sca= :deno_sca, ad1_sca= :ad1_sca, ad2_sca= :ad2_sca, ad3= :ad3_sca, cp_sca= :cp_sca, ville_sca= :ville_sca, tel_sca= :tel_sca, fax_sca= :fax_sca, adherent_sca= :adh_sca, directeur_sca= :dir_sca, centrale_sca= :centrale_sca, centrale_doris= :centrale_doris, centrale_smiley= :centrale_smiley, surface_sca= :surface_sca, sorti= :sorti, date_ouverture= :date_ouverture, date_adhesion= :date_adhesion, date_fermeture= :date_fermeture, date_resiliation= :date_resiliation, date_sortie= :date_sortie, pole_sav_sca= :pole_sav_sca, nom_gesap= :gesap, affilie= :affilie, racine_list= :racine_list, occasion= :occasion, backoffice_sca= :backoffice_sca, date_update= :date_update WHERE btlec_sca= :btlec_sca");

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
		':dir_sca'		=>$_POST['dir_sca'],
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
		':racine_list'		=>$_POST['racine_list'],
		':affilie'		=>$_POST['affilie'],
		':occasion'		=>$_POST['occasion'],
		':backoffice_sca'		=>(empty($_POST['backoffice_sca']))?NULL :$_POST['backoffice_sca'],
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
function getCmtFiles($pdoMag,$idcmt){
	$req=$pdoMag->prepare("SELECT * FROM cmt_file WHERE id_cmt= :id_cmt ORDER BY filename");
	$req->execute([
		':id_cmt'		=>$idcmt
	]);
	$datas=$req->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($datas)){
		return $datas;
	}
	return "";
}

$userDao=new UserDao($pdoUser);
$droitExploit=$userDao->isUserAllowed([5]);



if(isset($_POST['clear_form'])){
	$_POST=[];
	header("Location: ".$_SERVER['PHP_SELF']);

}
if (isset($_GET['id'])){
	$magDbHelper=new MagDao($pdoMag);
	$mag=$magDbHelper->getMagAndScaTroisInfo($_GET['id']);
	$occ=$mag->getOccasion();

	$histo=$magDbHelper->getHisto($mag->getGalec());
	$histoMagFerme=$magDbHelper->getHistoMagFerme($_GET['id']);

	$listCentralesSca=$magDbHelper->getDistinctCentraleSca();
	$listMainCentrale=$magDbHelper->getMainCentrale();


	$webuser=$magDbHelper->getWebUser($mag->getGalec());


	$centreRei=$magDbHelper->centreReiToString($mag->getCentreRei());
	$listTypesMag=$magDbHelper-> getListType();
	$listBackOffice=$magDbHelper->getListBackOffice();

	$listCm=UserHelpers::getUserByService($pdoUser, 17);
	$yearN=date('Y');
	$yearNUn= date("Y",strtotime("-1 year"));
	$yearNDeux= date("Y",strtotime("-2 year"));

	$financeN=$magDbHelper->getMagCaByYear($pdoQlik,$_GET['id'],$yearN);
	$financeNUn=$magDbHelper->getMagCaByYear($pdoQlik,$_GET['id'],$yearNUn);
	$financeNDeux=$magDbHelper->getMagCaByYear($pdoQlik,$_GET['id'],$yearNDeux);



	// ld
	$ldRbt=$magDbHelper-> getMagLd($mag->getId(),'-RBT');
	$ldRbtName=(!empty($ldRbt))? '<a class="text-orange" href="mailto:'.$ldRbt[0]['ld_full'].'">'.$ldRbt[0]['ld_full'].'</a>':  $mag->getRacineList()."-RBT";
	$ldRbtLink=convertArray($ldRbt,'email','</a><br>');
	$ldRbtExist=(!empty($ldRbt))? true:  false;
	$ldRbtLink=(!empty($ldRbtLink))? $ldRbtLink:  "Aucune adresse RBT";

	$ldDir=$magDbHelper-> getMagLd($mag->getId(),'-DIR');

	$ldDirName=(!empty($ldDir))? '<a class="text-orange" href="mailto:'.$ldDir[0]['ld_full'].'">'.$ldDir[0]['ld_full'].'</a>':  $mag->getRacineList()."-DIR";
	$ldDirLink=convertArray($ldDir,'email','</a><br>');
	$ldDirExist=(!empty($ldDir))? true:  false;
	$ldDirLink=(!empty($ldDirLink))? $ldDirLink : "Aucune adresse directeur";

	$ldAdh=$magDbHelper-> getMagLd($mag->getId(),'-ADH');
	$ldAdhName=(!empty($ldAdh))? '<a class="text-orange" href="mailto:'.$ldAdh[0]['ld_full'].'">'.$ldAdh[0]['ld_full'].'</a>':  $mag->getRacineList()."-ADH :";
	$ldAdhLink=convertArray($ldAdh,'email','</a><br>');
	$ldAdhExist=(!empty($ldAdh))? true:  false;
	$ldAdhLink=(!empty($ldAdhLink))? $ldAdhLink: "Aucune adresse adhérent ";

	$ldOkaz=$magDbHelper->getMagLd($mag->getId(), '-GT13');
	$ldOkazName=(!empty($ldOkaz))? '<a class="text-orange" href="mailto:'.$ldOkaz[0]['ld_full'].'">'.$ldOkaz[0]['ld_full'].'</a>':  $mag->getRacineList()."-GT13";
	$ldOkazLink=convertArray($ldOkaz,'email','</a><br>');
	$ldOkazExist=(!empty($ldOkaz))? true:  false;
	$ldOkazLink=(!empty($ldOkazLink))? $ldOkazLink:  "Pas d'adresse GT occasion";


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
	$magDbHelper=new MagDao($pdoMag);
	$mag=$magDbHelper->getMagAndScaTroisInfo($_GET['id']);
	$cmtList=$magDbHelper->getCmt($_GET['id']);
}

if(isset($_POST['maj'])){
	if(isset($_POST['occasion']) && $_POST['occasion']==1){
		$rightOccOk=$userDao->userHasThisRight($webuser['id_web_user'],84);
		if(empty($rightOccOk)){
			$userDao->addRight($webuser['id_web_user'],84);
		}
	}
	if(isset($_POST['occasion']) && $_POST['occasion']==0){
		$rightOccOk=$userDao->userHasThisRight($webuser['id_web_user'],84);
		if(!empty($rightOccOk)){
			$do=$userDao->removeRight($webuser['id_web_user'],84);
		}
	}
	$up=updateSca($pdoMag);
	if($up==1){
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


		$errors[]="impossible d'ajouter le magasin : ".$error[2];
	}
}

if(isset($_POST['submit-syno'])){
	if(!isset($_POST['galec-syno']) || !isset($_POST['bt-new'])){
		$errors[]="Merci de saisir le nouveau code BTLec ainsi que le panonceau galec";
	}

	if(empty($errors)){
		$req=$pdoMag->prepare("INSERT INTO magsyno (btlec_old, btlec_new, galec, date_insert) VALUES (:btlec_old, :btlec_new, :galec, :date_insert)");
		$req->execute([
			':btlec_old'		=>$_GET['id'],
			':btlec_new'		=>$_POST['bt-new'],
			':galec'		=>$_POST['galec-syno'],
			':date_insert'		=>date('Y-m-d H:i:s')
		]);
		$insert=$req->rowCount();
		if($insert==1){
			$successQ='success=ajoutsyno#syno';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&'.$successQ,true,303);
		}else{
			$error=$req->errorInfo();
			$errors[]="impossible d'ajouter le magasin synonyme: ".$error[2];
		}
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



if(isset($_POST['submit-mod-cmt'])){
	if(!empty($_POST['cmt-mod'])){
		$req=$pdoMag->prepare("UPDATE cmt_mag SET cmt= :cmt, created_by= :created_by, date_update= :date_update WHERE id = :id");
		$req->execute([
			':id'			=>$_POST['cmt-id'],
			':cmt'			=>$_POST['cmt-mod'],
			':created_by'	=>$_SESSION['id_web_user'],
			':date_update'	=>date('Y-m-d H:i:s')
		]);
		if($req->rowCount()!=1){
			$err=$req->errorInfo();
			$errors[]="impossible de mettre à jour l'observation : ".$err[2];
		}else{
			$lastInsertId=$_POST['cmt-id'];
		}
		if(isset($_FILES['files']) && !empty($_FILES['files']['name'][0]) ){
			$uploadDir=DIR_UPLOAD."xploit-mag\\";


			if(!is_dir($uploadDir)){
				$errors[]="Le répertoire de destination n'existe pas ";
			}
			if(!is_writable($uploadDir)){
				$errors[]="Le répertoire de destination n'autorise pas l'upload de fichiers !";
			}
			// 10mo
			$maxFileSize = 10 * 1024 * 1024;
			$totalSize=0;
			for($i=0;$i<sizeof($_FILES['files']['size']);$i++){
				$totalSize=$totalSize + $_FILES['files']['size'][$i];
			}

			if($totalSize > $maxFileSize){
				$errors[] = 'Attention la somme totale des fichiers dépasse la taille autorisée de 10Mo';

			}else{
				for($i=0;$i<sizeof($_FILES['files']['size']);$i++){
					$filename=$_FILES['files']['name'][$i];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$filenameNoExt = basename($filename, '.'.$ext);
					$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;

					$uploaded=move_uploaded_file($_FILES['files']['tmp_name'][$i],$uploadDir.$filenameNew );
					if($uploaded==false){
						$errors[]="Impossible d'uploader votre fichier ";
					}else{
						$req=$pdoMag->prepare("INSERT INTO cmt_file (id_cmt, filename, created_by, date_insert) VALUES (:id_cmt, :filename, :created_by, :date_insert)");
						$req->execute([
							':id_cmt' =>$lastInsertId,
							':filename' =>$filenameNew,
							':created_by'	=>$_SESSION['id_web_user'],
							':date_insert'=>date("Y-m-d H:i:s")
						]);
						if($req->rowCount()!=1){
							$err=$req->errorInfo();
							$errors[]="impossible d'ajouter la pièce jointe : ".$err[2];
						}
					}
				}
			}
		}

		if(empty($errors)){
			$successQ='?id='.$_GET['id'].'&success=upnote';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
		}else{
			$errors[]="le champs observation ne peut être vide";
		}
	}
}
if(isset($_POST['submit-mod-add'])){

	// print_r(ini_get('post_max_size'));
	if(!empty($_POST['cmt-mod'])){
		$req=$pdoMag->prepare("INSERT INTO cmt_mag (btlec, cmt, created_by, date_insert) VALUES (:btlec, :cmt, :created_by, :date_insert)");
		$req->execute([
			':btlec'		=>$_GET['id'],
			':cmt'			=>$_POST['cmt-mod'],
			':created_by'	=>$_SESSION['id_web_user'],
			':date_insert'	=>date('Y-m-d H:i:s')
		]);
		if($req->rowCount()!=1){
			$err=$req->errorInfo();
			$errors[]="impossible d'ajouter l'observation : ".$err[2];
		}else{
			$lastInsertId=$pdoMag->lastInsertId();

		}
		if(isset($_FILES['files']) && !empty($_FILES['files']['name'][0]) ){
			$uploadDir=DIR_UPLOAD."xploit-mag\\";


			if(!is_dir($uploadDir)){
				$errors[]="Le répertoire de destination n'existe pas ";
			}
			if(!is_writable($uploadDir)){
				$errors[]="Le répertoire de destination n'autorise pas l'upload de fichiers !";
			}
			// 10mo
			$maxFileSize = 10 * 1024 * 1024;
			$totalSize=0;
			for($i=0;$i<sizeof($_FILES['files']['size']);$i++){
				$totalSize=$totalSize + $_FILES['files']['size'][$i];
			}

			if($totalSize > $maxFileSize){
				$errors[] = 'Attention la somme totale des fichiers dépasse la taille autorisée de 10Mo';

			}else{
				for($i=0;$i<sizeof($_FILES['files']['size']);$i++){
					$filename=$_FILES['files']['name'][$i];
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$filenameNoExt = basename($filename, '.'.$ext);
					$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;

					$uploaded=move_uploaded_file($_FILES['files']['tmp_name'][$i],$uploadDir.$filenameNew );
					if($uploaded==false){
						$errors[]="Impossible d'uploader votre fichier ";
					}else{
						$req=$pdoMag->prepare("INSERT INTO cmt_file (id_cmt, filename, created_by, date_insert) VALUES (:id_cmt, :filename, :created_by, :date_insert)");
						$req->execute([
							':id_cmt' =>$lastInsertId,
							':filename' =>$filenameNew,
							':created_by'	=>$_SESSION['id_web_user'],
							':date_insert'=>date("Y-m-d H:i:s")
						]);
						if($req->rowCount()!=1){
							$err=$req->errorInfo();
							$errors[]="impossible d'ajouter la pièce jointe : ".$err[2];
						}
					}
				}
			}
		}
		if(empty($errors)){
			$successQ='?id='.$_GET['id'].'&success=insertnote';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

		}





	}else{
		$errors[]="le champs observation ne peut être vide";
	}

}

if(isset($_GET['success'])){
	$arrSuccess=[
		'maj'=>'Magasin mis à jour avec succès',
		'udocmail'=>'Mise à jour des codes docubases et envoi du mail effectués avec succès',
		'udoc'=>'Mise à jour des codes docubases effectuée avec succès',
		'majcm'=>'Attribution effectuée avec succès',
		'majacdlec'=>'Code ajouté avec succès',
		'ajoutmag'=>'infos magasin recopiée avec succès dans la table sca3',
		'upnote' =>'Mise à jour de l\'observation faite avec succès',
		'insertnote' =>'Ajout de l\'observation faite avec succès',
		'ajoutsyno' =>'Ajout du magasin synonyme fait avec succès'
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

				include('search-form/search-form.php')
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
							Leclerc <?= $mag->getDeno() ?>
						</h1>

						<h5 class="yanone">
							Code BTLec : <span class="text-orange" ><?= $mag->getId() ?></span>
							<span class="pl-5">Panonceau Galec : <span class="text-orange"><?=$mag->getGalec()?></span>
							<span class="pl-5">Centrale : </span><span class="text-orange"><?=$centraleSca?></span>
							<span class="pl-5">Occasion : </span><span class="text-orange"><?=($mag->getOccasion()==1)?"oui":"non"?></span>


						</h5>

					</div>
					<?php
					include('search-form/search-form.php')
					?>
					<div class="col-auto mt-3  pt-2">
						<?=Helpers::returnBtn('base-mag.php','btn-kaki')?>
					</div>
					<!-- <div class="col-lg-1"></div> -->
				</div>
			<?php endif?>


			<?php
			if (isset($mag)){
				include('fiche-mag/01-commun.php');
				if($droitExploit && $_SESSION['id_web_user']!=1040){
					include('fiche-mag/02-exploit.php');
				}
				include('fiche-mag/03-modal-observation.php');

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
			<!-- ./container -->
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

			$("#racine_list").keyup(function(){
				var racineList=$("#racine_list").val();

				$.ajax({
					type:'POST',
					url:'ajax-check-racine.php',
					data:{racine_list:racineList},
					success: function(html){
						$("#msg-racine").html(html)
					}
				});
			});

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
			$('.modal-target').click(function(){
				var target=$(this).attr('data-target');
				if(target=="#largeModal"){
					var id=$(this).attr('data-id');
					var idTextZone='#cmt-'+id;
					var textToCopy=$(idTextZone).text();

					$("textarea#cmt-mod").val(textToCopy);
					$("input#cmt-id").val(id);
				}


			});

		});
	</script>

	<?php
	require '../view/_footer-bt.php';
	?>