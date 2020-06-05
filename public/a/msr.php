<?php
require('config/config.inc.php');

// if(!isset($_SESSION['id'])){
// 	echo "pas de variable session";
// 	header('Location:'. DIR_NOSESSION);
// }
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
		$errors[]="Veuillez saisir votre GT/PSN et votre mot de passe";
	}
	if(empty($errors)){
		$req=$pdoUser->prepare("SELECT * FROM pilotes WHERE pseudo= :pseudo");
		$req->execute([
			':pseudo'	=>trim($_POST['pseudo'])
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);


		if($data){
			if(password_verify( $_POST['pwd'],$data['pwd'])){
				// $success[]="connexion réussie";
				session_start();
				$_SESSION['id']=$data['id'];
				$_SESSION['pseudo']=$data['pseudo'];
				$_SESSION['authorized']=$data['authorized'];
				$_SESSION['organisateur']=$data['organisateur'];
				if($_SESSION['authorized']==0){
					$errors[]="Votre accès au site n'a pas encore été validé";
				}
				if(count($errors)==0 && $_SESSION['organisateur']==0){
					unset($_POST);
					header("Location:pilote\pilote-dashboard.php",true,303);
				}elseif(count($errors)==0 && $_SESSION['organisateur']==1){
					unset($_POST);
					header("Location:organisation\mygp.php",true,303);
				}

			}else{
				$errors[]="Mot de passe incorrect" ;
			}
		}else{
			$errors[]="Ce GT/PSN n'existe pas";
		}
	}


}



//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('view/_head.php');
// include('../view/_navbar.php');
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
					<h1 class="underline-anim">Bienvenue</h1>
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
											<label for="pseudo">GT/PSN</label>
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
							<hr>
							<div class="row">
								<div class="col">
									<a href="pilote/registration.php" class="card-link">S'inscrire</a>
								</div>
								<div class="col-auto  text-right">
									<a href="#" class="card-link">Mot de passe oublié</a>
								</div>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>



	<!-- ./container -->
</div>

<?php
require 'view/_footer.php';
?>
© 2020 GitHub, Inc.