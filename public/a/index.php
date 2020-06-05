<?php

include("../config/autoload.php");
//------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."css/".$pageCss.".css";









//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];



 //------------------------------------------------------
//			traitement
//------------------------------------------------------
if(isset($_POST['submit'])){

	if(empty($_POST['pseudo'])||empty($_POST['pwd'])){
		$errors[]="Veuillez saisir votre identifiant et votre mot de passe";
	}
	if(empty($errors)){
		$req=$pdo->prepare("SELECT * FROM users WHERE pseudo= :pseudo");
		$req->execute([
			':pseudo'	=>trim($_POST['pseudo'])
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);


		if($data){
			if(password_verify( $_POST['pwd'],$data['pwd'])){
				// $success[]="connexion réussie";
				session_start();
				$_SESSION['id']=$data['id'];
				$_SESSION['staff']=$data['staff'];
				if($_SESSION['staff']==0){
					unset($_POST);
					header("Location:planning\planning.php",true,303);
				}else{
					unset($_POST);
					header("Location:exploit\\reservation.php",true,303);
				}


			}else{
				$errors[]="Mot de passe incorrect" ;
			}
		}else{
			$errors[]="Cet identifiant n'existe pas";
		}
	}


//------------------------------------------------------
//			VIEW
//------------------------------------------------------








include('../view/_head.php');


?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container-fluid h-100 justify-content-center">
	<div class="row justify-content-center align-middle h-100">
		<div class="col align-self-center">
			<div class="row mb-md-3 mt-5">
				<div class="col-auto mx-auto">
					<img src="img/logo/mysimrace.png" class="card-img-top" style="width: 300px; height:auto">

				</div>
			</div>
			<div class="row">
				<div class="col pt-3 mb-3 text-center">
					<h1 class="underline-anim">Espace club</h1>
					<h2>Planning de réservation</h2>
				</div>
			</div>

			<div class="row">
				<div class="col">
					<?php
					include('view/_errors.php');
					?>
				</div>
			</div>

			<div class="row mt-md-5">

				<div class="col-auto mx-auto">
					<div class="card bg-dark justify-content-center" style="width: 400px;">
						<div class="card-body">
							<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
								<div class="row px-5">
									<div class="col">
										<div class="form-group">
											<label for="pseudo">identifiant</label>
											<input type="text" class="form-control" name="pseudo" id="pseudo">
										</div>
										<div class="form-group">
											<label for="pwd">Mot de passe</label>
											<input type="password" class="form-control" name="pwd" id="pwd">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-auto mx-auto">
										<button class="btn btn-primary" name="submit">Se connecter</button>
									</div>
								</div>
							</form>


						</div>
					</div>
				</div>


			</div>
		</div>
	</div>






	<!-- ./container -->
</div>

<?php
require '../view/_footer.php';
?>
