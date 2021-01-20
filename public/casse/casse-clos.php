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
require_once '../../vendor/autoload.php';
require '../../Class/Uploader.php';
require '../../Class/MagHelpers.php';
require '../../Class/Helpers.php';


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

function addFacDate($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE palettes SET date_clos= :date_clos WHERE id_exp= :id");
	$req->execute([
		':id'	=>$_GET['id'],
		':date_clos'=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
	// return $req->errorInfo();
}

function closeCasse($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE casses LEFT JOIN palettes ON casses.id_palette=palettes.id LEFT JOIN exps ON palettes.id_exp=exps.id SET casses.etat=1 WHERE exps.id= :id");
	$req->execute([
		':id'	=>$_GET['id']
	]);
	return $req->rowCount();
}

function closeExp($pdoCasse,$mtFac,$mtBlanc,$mtBrun,$mtGris,$file){
	$req=$pdoCasse->prepare("UPDATE exps SET exp=1, date_fac=:date_fac, mt_fac= :mt_fac,mt_blanc= :mt_blanc, mt_brun= :mt_brun, mt_gris= :mt_gris, file= :file WHERE id= :id");
	$req->execute([
		':id'	=>$_GET['id'],
		':date_fac' =>date('Y-m-d H:i:s'),
		':mt_fac'	=>$mtFac,
		':mt_blanc'	=>$mtBlanc,
		':mt_brun'	=>$mtBrun,
		':mt_gris'	=>$mtGris,
		':file'		=>$file
	]);
	return $req->rowCount();
}

function closeExpNofac($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE exps SET exp=1 WHERE id= :id");
	$req->execute([
		':id'	=>$_GET['id'],

	]);
	return $req->rowCount();
}


function checkFac($pdoCasse){
	$req=$pdoCasse->prepare("SELECT mt_fac, mt_brun, mt_gris, mt_blanc, DATE_FORMAT(date_fac,'%d-%m-%Y') as datefac, galec FROM exps WHERE id= :id");
	$req->execute([
		':id'	=>$_GET['id']
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);
	if($data['mt_fac']==null || $data['mt_fac']==''){
		return false;
	}
	return $data;

}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];


$isFac=checkFac($pdoCasse);
$mag=MagHelpers::deno($pdoMag,$isFac['galec']);


if(isset($_POST['clos'])){


	$uploader   =   new Uploader();
	$uploader->setDir('..\..\..\upload\casse\\');
	$uploader->allowAllFormats();
	$uploader->setMaxSize(5);
	//                      //set max file size to be allowed in MB//

	if($uploader->uploadFile('file')){
		$file =$uploader->getUploadName();


	}
	else{//upload failed
		$errors[]=$uploader->getMessage();
			echo "<pre>";
			print_r($errors);
			echo '</pre>';
	}

	$added=addFacDate($pdoCasse);
	$close=closeCasse($pdoCasse);
	$closeExp=closeExp($pdoCasse,$isFac['mt_fac'], $isFac['mt_blanc'],$isFac['mt_brun'], $isFac['mt_gris'],$file);

	if($added>0 && $closeExp >0){
		if(VERSION=='_'){
			$to=['valerie.montusclat@btlec.fr']	;
			$cc=[];
			$bcc='';
		}else{
		$to=['isabelle.richard@btlec.fr','clement.anciaux@btlec.fr', 'sandie.lejeune@btlec.fr']	;
			$cc=['christelle.trousset@btlec.fr','nathalie.pazik@btlec.fr','luc.muller@btlec.fr'];
			$bcc='valerie.montusclat@btlec.fr';

		}
		$htmlMail = file_get_contents('mail-compta.html');
		$htmlMail=str_replace('{MAG}',$mag,$htmlMail);
		$htmlMail=str_replace('{FAC}',$isFac['mt_fac'],$htmlMail);
		$htmlMail=str_replace('{BLANC}',$isFac['mt_blanc'],$htmlMail);
		$htmlMail=str_replace('{BRUN}',$isFac['mt_brun'],$htmlMail);
		$htmlMail=str_replace('{GRIS}',$isFac['mt_gris'],$htmlMail);
		$subject='Portail BTLEC - facturation casse';
		$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
		$mailer = new Swift_Mailer($transport);
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
		// ->setTo(['valerie.montusclat@btlec.fr'])

		->setTo($to)
		->setCc($cc)
		->addBcc($bcc)
		->attach(Swift_Attachment::fromPath('..\..\..\upload\casse\\'.$file));
		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success='?id='.$_GET['id'].'&success=closfac';
			unset($_POST);
			header("Location: ".$_SERVER['PHP_SELF'].$success,true,303);
		}


	}
	else{
		$errors[]="impossible de clôturer l'expédition";

	}
}

if(isset($_POST['closhs'])){
	$added=addFacDate($pdoCasse);
	$close=closeCasse($pdoCasse);
	$closeExp=closeExpNofac($pdoCasse);
	if($added>0 && $close >0  && $closeExp >0){
		$success='?id='.$_GET['id'].'&success=clos';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$success,true,303);

	}
	else{
		$errors[]="impossible de clôturer l'expédition";
	}

}





if(isset($_GET['success'])){
	$arrSuccess=[
		'clos'=>'l\'expédition est clôturée',
		'closfac'=>'L\'expédition est clôturée et vous avez reçu en copie le mal d\'information envoyé au service comptabilité',
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
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<div class="row">
	<div class="col">
		<h1 class="text-main-blue py-5 ">Clôture de l'expédition n°<?=$_GET['id']?></h1>
	</div>

		<div class="col"><?=Helpers::returnBtn('bt-casse-dashboard.php')?></div>
	</div>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<?php if ($isFac): ?>

		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<div class="alert alert-primary text-center">
					<div class="text-left pb-3">Rappel des montants facturés le <b><?=$isFac['datefac']?></b> :</div>
					<table>
						<tr>
							<td>Facture : </td>
							<td class="text-right"><?=$isFac['mt_fac']?>&euro;</td>
						</tr>
						<tr>
							<td>Avoir blanc :</td>
							<td class="text-right"><?=$isFac['mt_blanc']?>&euro;</td>
						</tr>
						<tr>
							<td>Avoir brun :</td>
							<td class="text-right"><?=$isFac['mt_brun']?>&euro;</td>
						</tr>
						<tr>
							<td>Avoir Gris : </td>
							<td class="text-right"><?=$isFac['mt_gris']?>&euro;</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-lg-1"></div>
		</div>

		<div class="row pb-5">
			<div class="col-lg-1"></div>
			<div class="col">
				<form class="form-inline" method="post" action="<?=$_SERVER['PHP_SELF'].'?id='.$_GET['id']?>" enctype="multipart/form-data">
					<div class="form-group">
						<label for='file'>Joindre le compte rendu : </label><input type='file' class='form-control-file' id='file' name='file' >
					</div>
					<button type="submit" class="btn btn-primary mt-4" name="clos">Clôturer</button>
				</form>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<?php else: ?>
			<div class="row mt-5">
				<div class="col-lg-1"></div>
				<div class="col">
					<h5 class="text-center"><i class="fas fa-bomb text-red fa-2x  pr-3"></i>Cette expédition n'a pas été facturée, êtes vous sûr de vouloir la clôturer ?</h5>
				</div>
				<div class="col-lg-1"></div>
			</div>
			<div class="row mb-5 pb-5">
				<div class="col-lg-1"></div>
				<div class="col text-center">
					<form action="<?=$_SERVER['PHP_SELF'].'?id='.$_GET['id']?>" method="post">
						<button type="submit" class="btn btn-primary mt-4" name="closhs">oui, clôturer</button>
					</form>
				</div>
				<div class="col-lg-1"></div>
			</div>

		<?php endif ?>

		<!-- ./container -->
	</div>



	<?php
	require '../view/_footer-bt.php';
	?>