<?php


// personnalisation de la page en fonction du service ($_GET => nom court du service)

$gt=$_GET['gt'];
$gtInfos=initForm($pdoBt,$gt);

foreach($gtInfos as $data){
 	$full_name= $data['full_name'];
 	$descr= $data['description'];
 	$idGT=$data['id'];
}
$names=getNames($pdoBt, $idGT);

//traitement du formulaire d'ajout de demande
$id_mag=$_SESSION['id'];
if(!empty($_POST))
{
	//var_dump($_POST);
	addMsg($pdoBt,$_POST['objet'],$_POST['msg'],$id_mag,$idGT);
	//header('Location:../mag/histo.php');
}
else
{
	//echo "la demande n'a pas pu être envoyée";
}

$allMagMsg=showAllMsg($pdoBt,$id_mag);
// pour le bouton retour
$_SESSION['page_contact']=$_SERVER['REQUEST_URI'];


?>

<div class="container">
	<div class="down"></div>
	<!-- ligne totale -->
	<div class="row">
		<!-- colonne gauche -->
		<div class="col l4">
			<div class="row">
				<!-- <div class="col l12"> -->
					<!-- service -->
					<!-- <div class="card grey"> -->
					<div class="card">
						<!-- light-blue darken-4 -->
						<div class="card-content>


						<div class="col l12 blue darken-3 white-text">
							<span class=" col l6 avatar">
							 <img src="../img/contact/img_avatar100.png" alt="Avatar" class="w3-circle">
								<!-- <img  src="../img/contact/miniman.png"> -->
							</span>

							<span class="col l6 card-title">SERVICE <?= $full_name ?> </span>
							<br>



						</div>
						<div class="col l12 grey lighten-2">

							<p><?= $descr ?></p>
						<hr>
							<p class="contact-name white-text">Vos interlocteurs :
								<?php
								foreach ($names as $name) {
									echo $name['prenom'] . ' '. $name['nom']. ' - ';
								}
								?>

							</p>
						</div>
					</div>

				</div>
			</div>
		</div>  <!-- fermeture col gauche -->

		<!-- colonne droite -->
		<div class="col l2"></div>
		<div class="col l6 grey lighten-4">
			<div class="padding-all">
			<div class="row">
				<h4 class="light-blue-text text-darken-2">Votre demande</h4>
			</div>

			<form class='down' action="contact.php?gt=<?=$gt ?>" method="post" enctype="multipart/form-data">
			<!-- <form class='down' action="" method="post" enctype="multipart/form-data"> -->

				<!--OBJET -->
				<div class="row">
					<div class="input-field">
						<label for="objet"></label>
						<input class="validate" placeholder="Objet" name="objet" id="objet" type="text" >
					</div>
				</div>
				<!--MESSAGE-->
				<div class="row">
					<div class="input-field">
						<label for="msg"></label>
						<textarea class="materialize-textarea" placeholder="Message" name="msg" id="msg" ></textarea>
					</div>
				</div>
				<!--BOUTONS-->
				<div class="row">
					<input type="submit" name="post-msg">
				</div>
			</form>
		</div>
	</div>
	</div><!--fin row1 -->



	<!--historique-->
	<div class="row">

		<h4 class="light-blue-text text-darken-2">Historique de vos demandes sur le portail BTLec</h4>

		<table class="striped grey-text text-darken-2">
			<thead>
				<tr>
					<!-- ajouter historique des demandes sur tous les services (date / service / titre/ repondu le / btn détail) -->
					<th>Date</th>
					<th>Service</th>
					<th>Objet</th>
					<th>Date de la réponse</th>
					<th>Détail (bouton)</th>
				</tr>
			</thead>
			<?php foreach($allMagMsg as $key => $value): ?>
			<tr>
				<td>
					<!--  H:i:s -->
					<?= date('d-m-Y', strtotime($value['date_msg']))?>
				</td>
				<td>
					<?= $value['id_service']?>

				</td>
				<td>
					<?= $value['objet']?>
				</td>
				<td>
					<?= $value['etat']?>

				</td>
				<td> <a class="btn-floating z orange" href="../mag/edit-msg.php?msg=<?= $value['id']?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>

		    	</td>
			</tr>
			<?php endforeach ?>
		</table>
	</div> <!-- fin row histo-->
</div> <!--container -->

<div class="down"></div>
<div class="down"></div>




