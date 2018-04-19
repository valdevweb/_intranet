<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}

// require 'pdfgenmail.php';
//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";
//----------------------------------------------------
// INFOS PAGE
//----------------------------------------------------
// pour un mag, la page affiche l'adresse et le nom du mag
// si cdm trouvé, les coordonnés du cdm
// pour un utilisateur non mag, pas d'affichage


// <---------------------------------------------------
// STATS - add rec
//-----------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="demande mag au service ".$gt ;
// $page=basename(__file__);
// $action="envoi d'une demande";
// addRecord($pdoStat,$page,$action, $descr);
//------------------------------------->


//----------------------------------------------------
// DATAS
//----------------------------------------------------
if(isset($_SESSION['id_galec']))
{
	// chargé de mission
	$req=$pdoBt->prepare("SELECT * FROM sca3 WHERE galec= :galec");
	$req->execute(array(
		':galec'	=>$_SESSION['id_galec']
	));

	$result=$req->fetch(PDO::FETCH_ASSOC);

	$name="LECLERC " .$result['mag'];
	$ad1=$result['ad1'];
	$city=$result['cp'] .' '. $result['city'];

	$reqCdm=$pdoBt->prepare("SELECT * FROM cdm WHERE galec= :galec");
	$reqCdm->execute(array(
		':galec'	=> $_SESSION['id_galec']
	));
	$cdm=$reqCdm->fetch(PDO::FETCH_ASSOC);

	$nomCdmComplet=$cdm['cdm'];
	$nomCdmDecoupe=explode(' ',$nomCdmComplet);
	$prenomCdm=$nomCdmDecoupe[0];
	$nomCdm=$nomCdmDecoupe[1];
	$qrcode=strtolower($prenomCdm[0] . $nomCdm .".jpg");
	$reqInfo=$pdoBt->prepare("SELECT * FROM btlec WHERE nom= :nom");
	$reqInfo->execute(array(
		':nom'		=>$nomCdm
	));
	$infoCdm=$reqInfo->fetch(PDO::FETCH_ASSOC);

	//demandes
	// $reqDde=$pdoBt->prepare("SELECT count(id) as nbDde FROM msg WHERE id_mag= :id");
	// $reqDde->execute(array(
	// 	":id"	=>$_SESSION['id']
	// ));
	// $nbDde=$reqDde->fetch(PDO::FETCH_ASSOC);
	// $nbDde['nbDde'];
	// $reqDdeClos=$pdoBt->prepare("SELECT count(id) as ddeClos FROM msg WHERE id_mag= :id AND etat='clos'");
	// $reqDdeClos->execute(array(
	// 	":id"	=>$_SESSION['id']
	// ));
	// $nbDdeClos=$reqDdeClos->fetch(PDO::FETCH_ASSOC);

	// $nbDdeClos['ddeClos'];
}



//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-mig.php');
include('../view/_navbar.php');
?>
<div class="container">
	<h1 class="blue-text text-darken-4">Mon profil</h1>
	<br><br>

	<p><?=$name?></p>
	<p><?= $ad1 ?></p>
	<p><?= $city?> </p>
	<br><br>
	<?php
	//----------------------->
	ob_start();
 	?>
	<div class="card">
		<div class="card-header">VOTRE CHARGE DE MISSION</div>
		<div class="row">
			<div class="col-md-5">
				<p><br><img id="qrcode" src="../img/user/<?=$qrcode?>"></p>
			</div>
			<div class="col-md-7">
				<div class="card-body">
					<h5 class="card-title"> <?= $cdm['cdm'] ?></h5>
					<p class="card-text"><i class="fa fa-phone fa-2x"></i><?=$infoCdm['mobile']?></p>
					<p class="card-text"><i class="fa fa-envelope fa-2x"></i><?=$infoCdm['email']?></p>

				</div>
			</div>
		</div>
	</div>
	<?php
	$cdmContent=ob_get_contents();
	ob_end_clean();
	//----------------------->

	// si on a trouvé un chargé de mission, on affiche ses infos
	if($cdm !="")
	{
		echo $cdmContent;
	}
	?>

</div><!-- fin de container -->


<?php
include('../view/_footer.php');