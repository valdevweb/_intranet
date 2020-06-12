
<?php
// require('../config/config.inc.php');

// //------------------------------------------------------
// //			css dynamique
// //----------------------------------------------------------------
// $pageCss=explode(".php",basename(__file__));
// $pageCss=$pageCss[0];
// $cssFile=$_SERVER['DOCUMENT_ROOT'] ."css/".$pageCss.".css";





function createUser($pdoUser){
	$req=$pdoUser->prepare("INSERT INTO users (pseudo, nom, prenom, pwd, email,date_insert) VALUES (:pseudo, :nom, :prenom, :pwd, :email, :date_insert)");
	$req->execute([
		':pseudo'	=>trim($_POST['pseudo']),
		':nom'	=>trim($_POST['nom']),
		':prenom'	=>trim($_POST['prenom']),
		':pwd'	=>password_hash(trim($_POST['pwd']),PASSWORD_DEFAULT),
		':email'	=>trim($_POST['email']),
		':date_insert'		=>date('Y-m-d H:i:s')
	]);

	$err=$req->errorInfo();
	if(!empty($err[2])){
		return $err[2];
	}
	return false;

}










 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$pageTitle="Inscription";




//------------------------------------------------------
//			TRAITEMENT
//------------------------------------------------------
if(isset($_POST['submit'])){
	if(empty($_POST['pseudo']) || empty($_POST['pwd']) || empty($_POST['pwd-confirm']) || empty($_POST['nom']) || empty($_POST['prenom']) ){
		$errors[]="Merci de renseigner tous les champs";
	}


	// if(empty($errors)){
	// 	if(!$piloteManager->pseudoUniq($_POST['pseudo'])){
	// 		$errors[]="Le pseudo que vous avez choisi est déjà utilisé, merci d'en choisir un autre";
	// 	}
	// }
	//

	if(empty($errors)){
		$err=createUser($pdo);
		if(!$err){
			unset($_POST);

			header("Location:".$_SERVER['PHP_SELF'].$successQ,true,303);
		}

	}

}

if(isset($_GET['registered'])){

	$success[]= "Votre préinscription a bien été prise en compte";
}

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
// include('../view/_head.php');

include('../view/_head-bt.php');



?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<h1 class="text-center logo-header pt-3">
				<img src="../img/logos/logo-200.png" >
				<span class="text-primary ">Espace Club - </span> <span class="text-dark">bienvenue</span></h1>
			</div>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col pt-3  mb-3 text-center">
			<h2 class="text-primary">S'INSCRIRE</h2>
			<h6 class="sub-title">à l'espace de réservation de cours</h6>
		</div>
	</div>
	<!-- ./titre -->
	<!-- error/success -->
	<div class="row">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
	</div>




	<div class="row justify-content-center mt-5 ">
		<div class="col-md-4 pt-4 pb-5 px-5 bg-primary rounded-lg text-white">
			<div class="row mb-4">
				<div class="col text-center"><h5>Créer mon compte adhérent</h5></div>
			</div>
			<div class="row">
				<div class="col">
					<form  action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label for="pseudo">Identifiant : </label>
									<input type="text" class="form-control" name="pseudo" id="pseudo" required>
									<div id="pseudo-result"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nom">Nom :</label>
									<input type="text" class="form-control" name="nom" id="nom" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="prenom">Prenom :</label>
									<input type="text" class="form-control" name="prenom" id="prenom" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="email">Adresse email : </label>
									<input type="text" class="form-control" name="email" id="email">
									<div id="email-helper"></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="pwd">Mot de passe : </label>
									<input type="password" class="form-control" name="pwd" id="pwd" required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="pwd-confirm">Confirmer votre mot de passe :</label>
									<input type="password" class="form-control" name="pwd-confirm" id="pwd-confirm">
									<div id="pwd-msg"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<div class="text-right">
									<button class="btn btn-light" type="submit" name="submit">S'inscrire</button>
								</div>
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
	$(document).ready(function() {
		var x_timer;
		$("#pseudo").keyup(function (e){
			clearTimeout(x_timer);
			var pseudo = $(this).val();

			x_timer = setTimeout(function(){
				check_username_ajax(pseudo);
			}, 500);
		});
		function check_username_ajax(pseudo){
			$("#pseudo-result").html('<img src="../img/ajax-loader.gif" />');
			$.ajax({
				type:'POST',
				url:'ajax-pseudo-check.php',
				data:{pseudo:pseudo},
				success: function(html){
					$("#pseudo-result").html(html)
				}
			});

		}

		$("#email").keyup(function(){
			console.log("jkzejd");
			var email = $("#email").val();
			var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
			 //alert('Please provide a valid email address');
			 $("#email-helper").text(email+" n'est pas une adresse valide");
			 $("#email-helper").addClass('alert alert-danger');
			 email.focus;
			} else {
				$("#email-helper").text("adresse mail valide");
				$("#email-helper").removeClass('alert-danger');
				$("#email-helper").addClass('alert-success');

			}
		});

		$('#pwd, #pwd-confirm').on('keyup', function () {
			if ($('#pwd').val() == $('#pwd-confirm').val()) {
				$('#pwd-msg').removeClass('alert-danger');
				$('#pwd-msg').html('Mot de passe confirmé').addClass('alert-success');
			} else
			$('#pwd-msg').html('Mots de passe différents').addClass('alert-danger');
		});

	});


</script>


<?php

require '../view/_footer-bt.php';

// require '../view/_footer.php';
?>