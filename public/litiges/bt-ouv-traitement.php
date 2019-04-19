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

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisOuverture($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM ouverture LEFT JOIN btlec.sca3 ON ouverture.galec=btlec.sca3.galec WHERE ouverture.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function getRep($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM ouverture_rep WHERE id_ouv= :id ORDER BY date_saisie");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getBtName($pdoBt, $idwu)
{
	$req=$pdoBt->prepare("SELECT CONCAT (prenom, ' ', nom) as fullname FROM btlec WHERE id_webuser= :id_webuser");
	$req->execute(array(
		':id_webuser'	=>$idwu
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-grey"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
}


$thisOuv=getThisOuverture($pdoLitige);
$theseRep=getRep($pdoLitige);

if(isset($_POST['submit']))
{



}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

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
	<h1 class="text-main-blue py-5 ">Traitement de la demande n° <?= $_GET['id'] ?> de <?= $thisOuv['mag']?></h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row py-3">
		<div class="col">
			<h5 class="khand text-main-blue"> Rappel de la demande initiale: </h5>
		</div>
	</div>
	<div class="row">
		<div class="col  alert alert-primary"><?= $thisOuv['msg'] ?></div>
	</div>
	<?php
// si échange de msg
	if(!empty($theseRep))
	{
		echo '<div class="bg-separation"></div>';
		echo '<div class="row py-3">';
		echo '<div class="col">';
		echo '<h5 class="khand text-main-blue">Echanges avec le magasin : </h5>';
		echo '</div></div>';
		foreach ($theseRep as $rep)
		{
			$pj='';
			if($rep['mag']==0)
			{
				$alertColor='alert-warning';
				$from=getBtName($pdoBt, $rep['id_web_user']);


				$from=$from['fullname'];
			}
			else
			{
				$alertColor='alert-primary';
				$from=$thisOuv['mag'];
			}
			if(!empty($rep['pj']))
			{
				$pjtemp=createFileLink($rep['pj']);
				$pj='<br>Pièce jointe : '. $pjtemp ;
			}
			echo '<div class="row">';
			echo '<div class="col alert '.$alertColor.'">';
			echo $rep['msg'];
			echo $pj;
			echo '<br><br>';
			echo '<i class="fas fa-user-circle pr-3"></i>' .$from .' - le ' .$rep['date_saisie'];
			echo '</div>';
			echo '</div>';
		}
	}
	?>
	<div class="bg-separation"></div>
	<div class="row py-3">
		<div class="col">
			<h5 class="khand text-main-blue"> Traitement / envoi de messages : </h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="row bg-alert-primary rounded mb-5">
				<div class="col p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col heavy">
							Action :

							</div>
						</div>
						<div class="row py-3">
							<div class="col">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="action" id="inlineRadio1" value="2">
									<label class="form-check-label" for="inlineRadio1">Refuser le dossier</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="action" id="inlineRadio2" value="1">
									<label class="form-check-label" for="inlineRadio2">Accepter le dossier</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="action" id="inlineRadio3" value="autre">
									<label class="form-check-label" for="inlineRadio3">Demander un complément d'information</label>
								</div>

							</div>
						</div>



						<div class="row pt-3">
							<div class="col">
								<div class="form-group">
									<label for="action" class="heavy pb-3">Message : </label>
									<textarea type="text" class="form-control" row="6" name="msg" id="msg" required></textarea>
								</div>

							</div>
						</div>
						<div class="row align-items-end">
							<div class="col">
								<div id="file-upload">
									<fieldset>
										<p class="heavy pt-2">Pièces jointes :</p>
										<div class="form-group">
											<p><input type="file" name="form_file[]" class='form-control-file' multiple=""></p>
										</div>
									</fieldset>
								</div>
								<div id="filelist"></div>
							</div>
							<div class="col-auto">
								<p class="text-right "><button type="submit" id="submit" class="btn btn-primary" name="submit"><i class="fas fa-envelope pr-3"></i>Envoyer</button></p>
							</div>
						</div>

					</form>
				</div>
			</div>

		</div>

	</div>



	<!-- ./container -->
</div>

	<script type="text/javascript">

$(document).ready(function (){

var textarea="";
$('input[type=radio][name=action]').change(function() {
    if (this.value == '1') {
        textarea="Bonjour, \nNous vous informons que nous allons ouvrir un dossier litige dont le numéro vous sera communiqué très prochainement";
    }
    else if (this.value == '2') {
        textarea="Bonjour, \nNous clôturons votre dossier sans suite car ";
    }
     else if (this.value == 'autre') {
        textarea="Bonjour,";
    }
    $('#msg').val(textarea);


});
});





</script>

<?php
require '../view/_footer-bt.php';
?>