<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

include '../../config/db-connect.php';


require '../../functions/mail.fn.php';
//affichage de l'historique des réponses
require "../../functions/stats.fn.php";
require('../../Class/BtUserManager.php');
require('../../Class/MsgManager.php');
require('../../Class/MagHelpers.php');
require('../../Class/UserHelpers.php');


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
$userManager=new BtUserManager();
$msgManager=new MsgManager();
$listServicesContact=$userManager->listServicesContactStrict($pdoUser);


$idMsg=$_GET['msg'];
$oneMsg=$msgManager->getDemande($pdoBt,$_GET['msg']);


if ($_SESSION['id_service']!= 5 && $_SESSION['id_service']!= 16 && $_SESSION['id_service'] != 6 &&  $oneMsg['id_service'] != $_SESSION['id_service']){
	echo "vous ne pouvez pas réaffecter cette demande, elle n'est plus destinée à votre service";

	exit();

}


//compte des ligne pour afficher les checkbox en colonnes
$nbServices=count($listServicesContact);
$nbServicesLine=round($nbServices /4);
$lig=0;


// info msg
$link="Cliquez <a href='".SITE_ADDRESS."/index.php?".$idMsg."'>ici pour consulter la demande magasin</a>";

$tplt="../mail/reaffectation.html";
mb_internal_encoding('UTF-8');




$success=[];
$errors=[];

if(isset($_POST['affect']))
{
	if(!isset($_POST['service']))
	{
		$errors[]="veuillez sélectionner un service";

	}
	else
	{

		$newServiceInfo=$userManager-> getServiceById($pdoUser,$_POST['service'][0]);
		$msgManager->affectation($pdoBt,$idMsg,$_POST['service'][0]);
		$username=UserHelpers::getFullname($pdoUser,$_SESSION['id_web_user']);



		if (VERSION =="_"){
			$mailingList='valerie.montusclat@btlec.fr';
		}else{
			$mailingList=$newServiceInfo['mailing'];
		}
		$objet="PORTAIL BTLec - demande magasin réaffectée au service " . $newServiceInfo['service'];
		$objet = mb_encode_mimeheader($objet);
		$done=sendMail($mailingList,$objet,$tplt,$username,$oneMsg['deno'],$link);



			// $msg ="demande réaffectée au service " . $serviceInfo['full_name'];
		$success[] ="demande réaffectée au service " . $newServiceInfo['mailing'];
			//------------------------------
			//	ajout enreg dans stat
			//------------------------------<
		$descr="reaffectation d'une demande à " .$newServiceInfo['mailing'];
		$page=basename(__file__);
		$action="consultation";
		addRecord($pdoStat,$page,$action, $descr);
			//------------------------------>

	}
}


include '../view/_head-mig-bis.php';
include '../view/_navbar.php';

?>
<div class="container">
	<div class="row shadow-sm bg-white rounded border p-2">
		<div class="col">

			<h1>Réaffectation d'une demande</h1>


			<h4 id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Demande n °: <?= $oneMsg['idMsg'] .' '.$oneMsg['objet']?></h4>
			<hr>
			<br><br>
			<form action="chg.php?msg=<?=$idMsg ?>" method="post" id="chg">
				<div class="row">
					<div class="col-3">
						<?php
						foreach ($listServicesContact as $key => $service)
						{
							if($lig < $nbServicesLine)
							{

								echo '<div class="form-group form-check">';
								echo "<input type='checkbox' class='form-check-input' id='".$service['id']."' name='service[]' value= '".$service['id']."' />";
								echo "<label class='form-check-label' for='".$service['id']."'>".$service['service']."</label>";
								echo "</div>";
								$lig++;
							}
							else
							{
								$lig=0;
								echo '</div><div class="col-3">';
								echo '<div class="form-group form-check">';
								echo "<input type='checkbox' class='form-check-input' id='".$service['id']."' name='service[]' value= '".$service['id']."' />";
								echo "<label class='form-check-label' for='".$service['id']."'>".$service['service']."</label>";
								echo "</div>";
								$lig++;

							}

						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p class="text-right">
							<button class="btn btn-primary" type="submit" name="affect">Affecter</button>
						</p>
					</div>
				</div>

			</form>
			<?php include('../view/_errors.php') ?>


			<p class="back"><a href="dashboard.php"><i class="fa fa-chevron-circle-left fa-2x" aria-hidden="true"></i>&nbsp; &nbsp;Retour</a></p>

		</div>
	</div>
</div>

</div>  <!--container


<?php



include('../view/_footer-mig-bis.php');
 ?>








