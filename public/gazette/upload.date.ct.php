<?php
// initialisation
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  echo "pas de variable session";
  header('Location:'. ROOT_PATH.'/index.php');
}


		if (!empty($_FILES['file']))
		{


			if (0 === $_FILES['file']['error']) {
				$uploadDirectory = __DIR__.'\\upload\\';
				$fileInfo = new SplFileInfo($_FILES['file']['name']);
				$extension = $fileInfo->getExtension();
				$newFile = $_FILES['file']['name'];

				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDirectory.$newFile))
				{
				  header('Location: gazette.php?type=success');
				  $_GET['type']="success";

			  }
			  else
			  {
			  //  header('Location: index.php?type=error&code=3');
				  $_GET['type']='error';
				  $_GET['code']=3;
			  }
		  }
		  else
		  {
			  $_GET['type']='error';
			  $_GET['code']=2;

		   // header('Location: index.php?type=error&code=2');
		  }
	  }
	  else
	  {
		  $_GET['type']='error';
		  $_GET['code']=1;


		//header('Location: index.php?type=error&code=1');
	  }


	  if (!empty($_GET['type']))
	  {
		// if ($_GET['type'] === 'success')
		// {
		// 	$message = 'Fichier enregistré avec succès';

		// }
		if($_GET['type'] === 'error' && !empty($_GET['code']))
		{
			switch ($_GET['code']) {
				case 1:
				$message = 'Erreur, veuillez réessayez !';
				case 2:
				$message = 'Une erreur s\'est produite lors de l\'upload !';
				case 3:
				$message = 'Veuillez sélectionner un fichier';
			}
		}
	}
	else
	{
			   if (!empty($message)) echo '<p>'.$message.'</p>';
	}



include('../view/_head.php');
include('../view/_navbar.php');
?>


<?php

?>

<div class="container">
<h1 class="header center grey-text text-darken-2">Envoyer la gazette</h1>
<div class="row">
<form method="post" action="upload.ct.php" enctype="multipart/form-data">
<div class="col l4">
	<div class="upload">
		<label for="file">&nbsp;&nbsp;Sélectionnez le fichier gazette</label>
	</div>
		<input type="file" name="file" id="file" >
</div>
<div class="col l4">
	<div class="calendar">
		<label for="manualDate"><i class="fa fa-calendar fa-2x" aria-hidden="true"></i>&nbsp;&nbsp;Date de la gazette </label>
	</div>
	<input type="text" class="datepicker" name="manualDate" id="manualDate">
</div>
<div class="col l4 center">
	<button class="btn waves-effect waves-light orange darken-3" type="submit" name="upload" >Envoyer</button>
		<!-- <input type="submit" name="upload" value="Envoyer"> -->


</div>
</form>
</div>
Prévoir envoi de fichier joint
</div>
<?php
include('../view/_footer.php');

?>