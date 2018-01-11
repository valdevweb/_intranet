<?php
// personnalisation de la page en fonction du service ($_GET => nom court du service)
// pour le bouton retour
//$_SESSION['page_contact']=$_SERVER['REQUEST_URI'];

?>

<div class="container">
	<div class="down"></div>

	<!-- ligne totale -->
	<div class="row">
		<!-- colonne gauche -->
		<div class="col s12 m5 l4">
			<div class="row">
				<!-- <div class="col l12"> -->
					<!-- service -->
					<!-- <div class="card grey"> -->
					<div class="card ">
						<!-- light-blue darken-4 -->
						<div class="card-content blue darken-3 white-text">
							<div class=" col s5 m5 l5 avatar">
							 <img src="../img/contact/img_avatar100.png" alt="Avatar" class="w3-circle">
								<!-- <img  src="../img/contact/miniman.png"> -->
							</div>

							<div class="col s7 m7 l7 card-title">SERVICE <?= $full_name ?> </div>
							<br>



						</div>
						<div class="card-action col s12 m12 l12 grey lighten-3">

							<p class="grey-text text-darken-2">Description : <?= $descr ?></p>
						<hr>
							<p class="contact-name grey-text text-darken-2">Vos interlocteurs :
								<?php
								foreach ($serviceName as $n) {
									if($n['resp']){
										echo $n['prenom'] . ' '. $n['nom']. ' <br> ';
									}
									else
									{
										echo $n['prenom'] . ' '. $n['nom']. ' - ';

									}
								}

								?>

							</p>
						</div>
					</div>

				<!-- </div> -->
			</div>
		</div>  <!-- fermeture col gauche -->

		<!-- colonne droite -->
		<div class="col m1 l2"></div>
		<div class="col s12 m6 l6 grey lighten-4 z-depth-2">
			<div class="padding-all">
			<div class="row">
				<h3 class="light-blue-text text-darken-2">VOTRE DEMANDE</h3>
			</div>

			<form class='down' action="contact.php?gt=<?=$gt ?>" method="post" enctype="multipart/form-data">
			<!-- <form class='down' action="" method="post" enctype="multipart/form-data"> -->

				<!--OBJET -->
				<div class="row">
					<div class="input-field">
						<label for="objet"></label>
						<input class="validate" placeholder="Objet" name="objet" id="objet" type="text"  value="<?=isset($objet)? $objet: false?>">
					</div>
				</div>
				<!--MESSAGE-->
				<div class="row">
					<div class="input-field">
						<label for="msg"></label>
						<textarea class="materialize-textarea" placeholder="Message" name="msg" id="msg" ><?=isset($msg)? $msg: false?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col l6">
						<input class="validate" placeholder="Votre nom" name="name" id="name" type="text" value="<?=isset($name)? $name: false?>" >
					</div>
					<div class="col l6">
						<input class="validate" placeholder="email" name="email" id="email" type="email" value="<?=isset($email)? $email: false?>" >
					</div>

				</div>
				<div class="row">
					<div class="col l12"><p id="file-name"><?= isset($_FILES['name'])? $_FILES['name']: false?></p></div>
				</div>

				<div class="row">
					<div class="col l6">
						<div class="upload-ct">
							<label for="file">&nbsp;&nbsp;Joindre un fichier</label>
						</div>
						<input type="file" multiple="multiple" name="file[]" id="file" >
					</div>
					<div class="col l6 align-right">
						<!-- <div class="row align-right"> -->
							<button class="btn" type="submit" name="post-msg">Envoyer</button>
						<!-- </div> -->
					</div>
				</div>
				<!-- zone affichage erreurs -->
				<div class="row"><div class="col l6">
					<p class="warning-msg">
						<?php
						if(!empty($err)){
							foreach ($err as $error) {
								echo  $error ."</br>";
							}
						}
						?>
					</p>
					<p class="success-msg">
						<?php
						if(!empty($success)){
							foreach ($success as $s) {
								echo  $s ."</br>";
							}
						}
						?>
					</p>
			</div></div>


			</form>
		</div>
	</div>
	</div><!--fin row1 -->

</div> <!--container -->

<div class="down"></div>
<div class="down"></div>




