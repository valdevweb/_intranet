<?php
if(isset($_GET['success']))
{
	if($_GET['success']==1)
	{
		$success[]="votre demande à bien été prise en compte, les codes ont été envoyés aux personnes qui figurent dans la liste RBT de votre magasin";
	}
	else
	{
		$success[]="Une demande a été automatiquement envoyée au service informatique pour que votre mot de passe soit communiqué aux personnes qui figurent dans la liste RBT de votre magasin";
	}
}

if(isset($_GET['error']))
{
	if($_GET['error']==1)
	{
		$errors[]="erreur d'envoi du mail";
	}
	else
	{
		$errors[]="erreur - merci de signaler le problème à btlecest.portailweb.informatique@btlec.fr";

	}

}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="public/css/index.css"> -->
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<!-- <link rel="stylesheet" href="vendor/w3c/w3c.css"> -->
	<title>Demande d'identifiants</title>
</head>
<body>
	<div class="container">
		<br><br>
		<div class="row">
			<div class="col-md"></div>
			<div class="col-md-8 bg-light border">
				<h1 class="text-center">Demande d'identifiants</h1>
				<br>
				<br>
				<p>Les identifiants seront envoyés par mail aux contacts de la liste de diffusion responsables bazar technique de votre magasin</p>
				<form method="post" action="pwd.php">
					<div class="form-group">
						<label for="centrale">Votre centrale</label>
						<select class="form-control" id="centrale" name="centrale" required>
							<option value="">Sélectionnez votre centrale</option>
							<option value="BTLEC">BTLEC</option>
							<option value="LECASUD">LECASUD</option>
							<option value="PORTUGAL">PORTUGAL</option>
							<option value="SCACENTRE">SCACENTRE</option>
							<option value="SCAPALSACE">SCAPALSACE</option>
							<option value="SCAPARTOIS">SCAPARTOIS</option>
							<option value="SCAPEST">SCAPEST</option>
							<option value="SCADIF">SCADIF</option>
							<option value="SCAPNOR">SCAPNOR</option>
							<option value="SOCAMIL">SOCAMIL</option>
							<option value="SOCARA">SOCARA</option>
							<option value="SLOVENIE">SLOVENIE</option>
						</select>
					</div>
					<div class="form-group">

						<label for="mag">Votre magasin</label>
						<select class="form-control" id="mag" name="mag" required>
							<option value="">Sélectionnez votre magasin</option>
						</select>
						<br><br>
						<p>
							<button type="submit" class="btn btn-primary" name="submit" id="submit">Envoyer</button>
						</p>
					</div>
				</form>
			</div>
			<div class="col-md"></div>
		</div>
		<br>
		<div class="row">
			<div class="col-md"></div>
			<div class="col-md"><p class="text-center"><a href="index.php">Retour</a></p></div>
			<div class="col-md"></div>
		</div>
		<?php
		$startDiv="<div class='row'><div class='col-md'></div><div class='col-md-8'>";
		$endDiv="</div><div class='col-md'></div></div>";
		if(isset($errors)&& count($errors)!=0){
			echo $startDiv;
			echo '<div class="alert alert-danger text-center">';
			foreach ($errors as $error) {
				echo $error .'<br>';
			}

			echo '</div>';
			echo $endDiv;
		}

		if(isset($success) && count($success)!=0)
		{
			echo $startDiv;
			echo '<div class="alert alert-success text-center">';
			foreach ($success as $s) {
				echo $s .'<br>';
			}
			echo '</div>';
			echo $endDiv;

		}?>



	</div>

	<script src="vendor/jquery/jquery-3.2.1.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#centrale').on('change',function(){
				$('#mag').html('<option value="">Sélectionnez votre magasin</option>');
				var centrale = $(this).val();
				if(centrale){
					$.ajax({
						type:'POST',
						url:'ajaxMag.php',
						data:'centrale='+centrale,
						success:function(html){
							$('#mag').append(html);
						}
					});
				}else{

					// $('#centrale').html('<option value="">Sélectionnez votre centrale</option>');
					$('#mag').html('<option value="">Sélectionnez votre magasin</option>');
				}
			});

			// $('form').submit(function()
   //     		{
   //          $(":submit").text("Merci de patienter...")
   //          $("#submit").attr('disabled', true);

   //     		});


});
</script>
</body>
</html>