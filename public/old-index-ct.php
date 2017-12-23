<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Connexion - portail Btlec Est</title>
</head>
<body>

</body>
</html>




<!-- CONTAINER ACCUEIL => btlec est et bienvenue -->
<div class="down"></div>
<div class="container">

	<h1 class="header center 	light-blue-text text-darken-2">BTlec Est</h1>
	<div class="row center">
		<h5 class="header col s12  m12 l12 light">Bienvenue sur le portail de votre <br><span class="orange-text"> - Centrale d'achat Bazar Technique E.Leclerc - </span></h5>
	</div>




	<div class="row">
		<div class="col m2 l2">&nbsp;</div>
		<div class="col s12 m8 l8">
			<div class="card">
				<div class="card-image">
					<img src="public/img/bt-front-office-optimized.jpg">
				</div>
			</div>
		</div>
		<div class="col m2 l2">&nbsp;</div>
	</div>
	<div class="row center">
			<!-- Modal trigger : btn pour connexion -->
			<button id="login-mag" class="btn waves-effect waves-default light-blue darken-3 modal-trigger" data-target="modal1">Accès Magasin</button>
			<button id="login-bt" class="btn waves-effect waves-default light-blue darken-3 modal-trigger" data-target="modal2">Accès BTlec</button>

	</div>


<!-- </div> -->

</div> <!--fin de container -->
<div class="down"></div>
<div class="down"></div>


<!-- MODAL FORM  MAG-->
<div class="modal" id="modal1">
	<div class="modal-content">
		<h4>Connexion Magasin</h4>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus, laborum.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus eaque ex sed optio dicta maiores vel facilis nisi fugit quibusdam.</p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
	</div>
</div>

<!-- MODAL  FORM BT -->
<div class="modal" id="modal2">
	<div class="modal-content">
		<h4 class="grey-text text-darken-2">Connexion BTLec</h4>
			<form action="<?php echo $_SERVER['PHP_SELF'];?>"  method="post">

			<div class="modal-form-row">
				<div class="input-field">
					<input id="userBt" name="userBt" placeholder="identifiant" type="text" class="validate">
					<label for="userBt"></label>
				</div>
			</div>
			<div class="modal-form-row">
				<div class="input-field">
					<input id="pwdBt" name="pwdBt" placeholder="mot de passe" type="password">
					<label for="pwdBt"></label>
				</div>
			</div>
			<div class=".modal-form-row">
				<button class="btn waves-effect waves-light light-blue darken-3" type="submit" name="connexionBt">Connexion
				</button>
			</div>
		</form>


	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default grey-text text-darken-2">fermer</a>
	</div>
</div>



<!--  Scripts-->
<script src="vendor/jquery/jquery-3.2.1.js"></script>
<script src="vendor/materialize/js/materialize.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// menu hamburger
		$(".button-collapse").sideNav();
		// ouverture fenetre modal
		$('.modal').modal();
		$(".dropdown-button").dropdown();
		//infos bulles (navbar)
		 $('.tooltipped').tooltip({delay: 50});
	});
</script>

