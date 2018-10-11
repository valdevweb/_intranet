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

//compte des ligne pour afficher les checkbox en colonnes
$nbServices=count($services);
$nbServicesLine=round($nbServices /4);
$lig=0;


// info msg
$link="Cliquez <a href='http://172.30.92.53/". VERSION ."btlecest/index.php?".$idMsg."'>ici pour consulter le message</a>";

$tplt="../mail/new_mag_msg.tpl.html";
mb_internal_encoding('UTF-8');

$name=$oneMsg['who'];

//récup pano galec puis nom du mag
$mag=getPanoGalec($pdoUser,$oneMsg['id_mag']);

$magSca3=getMag($pdoBt,$mag['galec']);
$magName=$magSca3['mag'];




include '../view/_head-mig-bis.php';
include '../view/_navbar.php';



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
		$newService=$_POST['service'][0];
		//info service coché

		if(affectation($pdoBt,$idMsg,$newService))
		{

			$serviceInfo=service($pdoBt,$newService);
			$mailingList=$serviceInfo['mailing'];
			$objet="PORTAIL BTLec - demande magasin réaffectée au service " . $serviceInfo['full_name'];
			$objet = mb_encode_mimeheader($objet);
			// sendMail($mailingList,$objet,$tplt,$name,$magName,$link);

			// $msg ="demande réaffectée au service " . $serviceInfo['full_name'];
			$success[] ="demande réaffectée au service " . $serviceInfo['full_name'];
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
			$errors[]="erreur de traitement";
		}
	}
}



?>
<div class="container">
	<div class="row shadow-sm bg-white rounded border p-2">
		<div class="col">

			<h1>Réaffectation d'une demande</h1>


			<h4 id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i> Demande n °: <?= $oneMsg['id'] .' '.$oneMsg['objet']?></h4>
			<hr>
			<br><br>
			<form action="chg.php?msg=<?=$idMsg ?>" method="post" id="chg">
				<div class="row">
					<div class="col-3">
						<?php
						foreach ($services as $key => $service)
						{
							if($lig < $nbServicesLine)
							{

								echo '<div class="form-group form-check">';
								echo "<input type='checkbox' class='form-check-input' id='".$service['id']."' name='service[]' value= '".$service['id']."' />";
								echo "<label class='form-check-label' for='".$service['id']."'>".$service['full_name']."</label>";
								echo "</div>";
								$lig++;
							}
							else
							{
								$lig=0;
								echo '</div><div class="col-3">';
								echo '<div class="form-group form-check">';
								echo "<input type='checkbox' class='form-check-input' id='".$service['id']."' name='service[]' value= '".$service['id']."' />";
								echo "<label class='form-check-label' for='".$service['id']."'>".$service['full_name']."</label>";
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








