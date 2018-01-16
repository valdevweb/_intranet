<?php
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//on supprime la var qui mémorise le lien



require '../../functions/form.bt.fn.php';
require '../../functions/upload.fn.php';
require '../../functions/mail.fn.php';
//affichage de l'historique des réponses
require '../../functions/form.fn.php';
require "../../functions/stats.fn.php";


//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

//-----------------------------------------------
//				affichage
//-----------------------------------------------
//liste des services qui s'affichent dans les checkbox
$services=listServicesNoTest($pdoBt);
$idMsg=$_GET['msg'];
$oneMsg=showOneMsg($pdoBt,$idMsg);





// info msg
$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter le message</a>";

$tplt="../mail/new_mag_msg.tpl.html";
$objet="PORTAIL BTLec - demande magasin réaffectée à votre service";
mb_internal_encoding('UTF-8');
$objet = mb_encode_mimeheader($objet);
$name=$oneMsg['who'];

//récup pano galec puis nom du mag
$mag=getPanoGalec($pdoUser,$oneMsg['id_mag']);

$magSca3=getMag($pdoBt,$mag['galec']);
$magName=$magSca3['mag'];




include '../view/_head.php';
include '../view/_navbar.php';





if(isset($_POST['affect']))
{
	if(!isset($_POST['service']))
	{
		$msg="veuillez sélectionner un service";

	}
	else
	{
		$newService=$_POST['service'][0];
		//info service coché

		if(affectation($pdoBt,$idMsg,$newService))
		{

			$serviceInfo=service($pdoBt,$newService);
			$mailingList=$serviceInfo['mailing'];
			// sendMail($mailingList,$objet,$tplt,$name,$mag,$link);


			$msg ="demande réaffectée au service " . $serviceInfo['full_name'];
			//------------------------------
			//	ajout enreg dans stat
			//------------------------------<
			$descr="reaffectation d'une demande à " .$serviceInfo['full_name'];
			$page=basename(__file__);
			$action="consultation";
			addRecord($pdoStat,$page,$action, $descr);
			//------------------------------>
		}
		else
		{
			$msg="erreur de traitement";
		}
	}
}



?>
<div class="container">
	<h1 class="blue-text text-darken-2">Réaffectation d'une demande</h1>
	 <p> Demande n °: <?= $oneMsg['id'] .' '.$oneMsg['objet']?></p>
			<form action="chg.php?msg=<?=$idMsg ?>" method="post" id="chg">
					<p>
						<?php
						foreach ($services as $key => $service)


 							{
								echo "<input type='checkbox' class='filled-in' id='".$service['id']."' name='service[]' value= '".$service['id']."' />";
								echo "<label for='".$service['id']."'>".$service['full_name']."</label>";

 							}
 						?>

					<p class="center">
						<button class="btn" type="submit" name="affect">Affecter</button>
					</p>

			</form>
		<p><?= isset($msg)? $msg : '' ?>
		<div class="col l12">
			<p><a href="dashboard.php" class="orange-text text-darken-2"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>
		</div>
	</div>

</div>  <!--container


<?php



include('../view/_footer.php');
 ?>








