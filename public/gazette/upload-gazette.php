<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  echo "pas de variable session";
  header('Location:'. ROOT_PATH.'/index.php');
}

$uploadDir= '..\..\..\upload\gazette\\';
$link="http://172.30.92.53/".$version."upload/gazette/";


require '../../functions/upload.fn.php';
if (isset($_POST['upload']))
{
	if (empty($_FILES['file']))
	{

	}
	else
	{
		extract($_POST);
		gazetteExist($pdoBt);
		$upload=$_FILES['file'];
		$msg=checkUpload($upload, $uploadDir, $pdoBt);


	}
}


// if (!empty($_GET['uploaded']))
// {
// 	if ($_GET['uploaded'] === 'success')
// 	{
// 		$message = 'gazette envoyée avec succès ';
// 		// recup lien
// 	}
// 	elseif ($_GET['uploaded'] === 'error')
// 	{

// 		//$_GET[code]===1   => type de fichier interdit
// 		$message='erreur d\'envoi du fichier';
// 	}
// }

include('../view/_head.php');
include('../view/_navbar.php');
// affichage du message après upload

function gazetteExist($pdoBt)
{

	$today=new DateTime();
	$today=$today->format('Y-m-d');

	$req=$pdoBt->prepare("SELECT * FROM gazette WHERE date=:date AND category= :category");
	$req->execute(array(
		':date' 	=> $date,
		':category'	=>'gazette'
	));

	// si on a des gazettes à la date d'aujourd'hui
	if($data=$req->fetch())
	{
		header('location:upload-gazette.php?err');
		exit;
	}


}



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
						<label for="file">&nbsp;&nbsp;Sélectionnez le fichier gazette</label>
					</div>
					<input type="file" name="file" id="file" >
				</div>
				<div class="col l4 align-right">
					<button class="btn waves-effect waves-light orange darken-3" type="submit" name="upload" >Envoyer</button>
				</div>
				<div class="col l2"></div>
			</div>
			<div class="row">
			<!--	<div class="col l2"></div>
				<div class="col l8">
					<p>Si vous souhaitez remplacer la gazette du jour, merci de cocher la case</p>
						<input type="checkbox" name="remplace" id="oui" /> <label for="remplace">Remplacer</label><br />
					</p>
				</div>
				<div class="col l2"></div>
				date picker pour modif date gazette
			<div class="col l4">
				<div class="calendar">
					<label for="manualDate"><i class="fa fa-calendar fa-2x" aria-hidden="true"></i>&nbsp;&nbsp;Date de la gazette </label>
				</div>
				<input type="text" class="datepicker" name="manualDate" id="manualDate">
				!-->
			</div>



		</form>
	</div>
	<div class="down"></div>
	<div class="row">
			<div class="col l12 center">





<?php
if (isset($_GET['err']))
	{
		echo "<p>Une gazette a déja été envoyée aujourd'hui. Vous ne pouvez pas envoyer plusieurs gazettes par jour</p>";
	}

	if(isset($msg['success']))
	{
		echo "<p><a href='".$link.$msg['success'] ."'>voir</a></p>";
	}
		elseif (isset($msg['err'])) {
	# code...
		}
		{
			echo "<p>".$msg['err']."</p>";
		}


echo "</div></div></div>";

include('../view/_footer.php');

?>

