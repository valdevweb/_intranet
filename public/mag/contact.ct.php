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

								<div class="col s7 m7 l7 card-title">SERVICE <?= $service['service'] ?> </div>
								<br>



							</div>
							<div class="card-action col s12 m12 l12 grey lighten-3">

								<p class="grey-text text-darken-2">Description : <?= $service['description'] ?></p>
								<hr>
								<p class="contact-name grey-text text-darken-2">Vos interlocteurs :
									<?php
									foreach ($serviceMembers as $n) {
										if($n['resp']){
											echo $n['fullname']. ' <br> ';
										}
										else
										{
											echo $n['fullname']. ' - ';

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
				<div class="col m2 l2"></div>
				<div class="col s12 m6 l6 grey lighten-4 z-depth-2">
					<div class="padding-all">
						<div class="row">
							<h3 class="blue-text text-darken-2"><i class="fa fa-pencil" aria-hidden="true"></i>VOTRE DEMANDE</h3>
						</div>

						<form class='down' id="msg-form" action="<?=htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'] ?>" method="post" enctype="multipart/form-data">
							<!-- <form class='down' action="" method="post" enctype="multipart/form-data"> -->

								<!--OBJET -->
								<div class="row">
									<div class="input-field">
										<label for="objet"></label>
										<input class="validate" placeholder="Objet" name="objet" id="objet" type="text"  required="require" value="<?=isset($_POST['objet'])? $_POST['objet']: ""?>">
									</div>
								</div>
								<!--MESSAGE-->
								<div class="row">
									<div class="input-field">
										<label for="msg"></label>
										<textarea class="materialize-textarea" placeholder="Message" name="msg" required="require" id="msg" ><?=isset($_POST['msg'])? $_POST['msg']: ""?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col l6">
										<input class="validate" placeholder="Votre nom" name="name" id="name" title="seules les lettres sont autorisées" type="text" required="require" pattern="[a-zA-Z ]+" value="<?=isset($_POST['name'])? $_POST['name']: ""?>" >
									</div>
									<div class="col l6">
										<input class="validate" placeholder="email" name="email" id="email" type="email" required="require" value="<?=isset($_POST['email'])? $_POST['email']: ""?>" >
									</div>

								</div>
								<div class="row" id="file-upload">
									<fieldset>
										<legend>ajouter des pièces jointes</legend>
										<div class="col l6">
											<p><input type="file" name="file[]" class='input-file'></p>
											<p id="p-add-more"><a id="addmore" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Envoyer d'autres fichiers</a></p>
										</div>
									</fieldset>
								</div>
								<div class="row">
									<div class="col l12">
										<!-- <div class="row align-right"> -->
											<p class="align-right"><input class="btn" type="submit" name="post-msg" id="post-msg"></p>
											<p class="align-right" id="wait"></p>
											<!-- </div> -->
										</div>
									</div>
								</form>
							</div>
						</div>
					</div><!--fin row1 -->
					<?php if (!empty($errors)): ?>
						<div class='row'>
							<div class='col l12'>
								<?php foreach ($errors as $key => $err): ?>
									<p class='warning-msg'><?=$err?></p>
								<?php endforeach ?>

							</div>
						</div>
					<?php endif ?>
					<?php if (!empty($success)): ?>
						<div class='row'>
							<div class='col l12'>
								<?php foreach ($success as $key => $s): ?>
									<p class='success-msg'><?=$s?></p>
								<?php endforeach ?>

							</div>
						</div>
					<?php endif ?>


				</div> <!--container -->

				<div class="down"></div>
				<div class="down"></div>

				<script type="text/javascript">
					$(document).ready(function (){


						$('#addmore').click(function(){
							$('#p-add-more').prepend('<p><input type="file" name="file[]"></p>');
							$('input[type="file"]').val();
						});

						$("#msg-form").submit(function(e){
							if($("#email").val()!="" && $("#objet").val()!="" && $("#msg").val()!="" && $("#nom").val()!=""){
								if ($("#email").val()) {

									var email = $("#email").val();
									var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
									if (filter.test(email)) {
										$('input[name="post-msg"]').hide();
										$('#wait').text("Merci de patienter...");
									}
								}
							}
						});
					});
				</script>


