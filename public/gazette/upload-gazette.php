<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
require '../../functions/upload.gaz.fn.php';
require "../../functions/stats.fn.php";

//construction du lien pour visualiser la gazette uploadée
$link="http://172.30.92.53/".$version."upload/gazette/";

//soumission formulaire
if (isset($_POST['upload']))
{
	if (!empty($_FILES['file']) && !empty($_POST['date']))
	{
		extract($_POST);
		gazetteExist($pdoBt);
		$uploadDir= '..\..\..\upload\gazette\\';
		$upload=$_FILES['file'];
		$msg=checkUpload($upload, $uploadDir, $pdoBt);
		//-------------------------------------<
		//	ajout enreg dans stat
		//--------------------------------------
		$descr="fichier : " .$upload['name'] ;
		$page=basename(__file__);
		$action="upload gazette";
		addRecord($pdoStat,$page,$action, $descr);
		//------------------------------------->


	}
	else
	{
		unset($_FILES, $_POST);
		header('location:upload-gazette.php?empty');
		die;
	}
}
//vérifie si déjà gazette à la date selectionnée => si oui erreur et stop
function gazetteExist($pdoBt)
{
	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date=:date AND category= :category");
	$req->execute(array(
		':date' 	=> $_POST['date'],
		':category'	=>'gazette'
	));
	// si on a des gazettes à la date spécifiée
	if($data=$req->fetch())
	{
		// echo "<pre>";
		// var_dump($data);
		// echo '</pre>';
		unset($_FILES, $_POST);
		header('location:upload-gazette.php?err');
		die;
	}
}

include('../view/_head.php');
include('../view/_navbar.php');

?>


<div class="container">
	<h1 class="header center grey-text text-darken-2">Envoyer la gazette du jour</h1>
	<div class="row">
		<form method="post" action="upload-gazette.php" enctype="multipart/form-data">
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<label class="w3-text-grey" for="date">Selectionnez la date de la gazette à uploader</label>
					<input type="date" class="w3-input w3-border" name="date" id="date" >
				</div>
				<div class="col l6"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">
					<div class="upload">
						<label for="gazette">&nbsp;&nbsp;Sélectionnez le fichier gazette</label>
					</div>
					<input type="file" name="file" id="gazette" >
				</div>
				<div class="col l4 align-right">
					<button class="btn waves-effect waves-light orange darken-3" type="submit" name="upload" >Envoyer</button>
				</div>
				<div class="col l2"></div>
			</div>
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4"><p id="file-name"><?=isset($_FILES['name'])? $_FILES['name']: false?></p></div>
				<div class="col l6"></div>
			</div>
		</form>
	</div>
		<div class="down"></div>
		<div class="row">
		<div class="col l12 center">
			<?php
			if (isset($_GET['err']))
			{
				echo "<p>Une gazette existe déjà pour cette date</p>";
			}
			if (isset($_GET['empty']))
			{
				echo "<p>Merci de sélectionner un fichier et une date</p>";
			}
			if(isset($msg['success']))
			{

				echo "<p><a href='".$link.$msg['success'] ."'>voir la gazette uploadée</a></p>";
			}
			elseif (isset($msg['err']))
			{
				echo "<p>".$msg['err']."</p>";
			}
			elseif (isset($msg))
			{
				var_dump($msg);

			}
			?>


	</div>
	</div>
</div>
	<?php
			include('../view/_footer.php');



