
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="public/css/index.css">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="vendor/materialize/css/materialize.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/w3c/w3c.css">
	<title>Connexion - portail Btlec Est</title>
</head>
<body>
	<div id="main">
	</div>
<!-- MODAL FORM  CONNEXION-->
<div class="modal" id="modal1">
	<div class="modal-content">
		<h4>Connexion</h4>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>"  method="post">
			<div class="modal-form-row">
				<div class="input-field">
					<input id="login" name="login" placeholder="identifiant" type="text" class="validate" autofocus >
					<label for="login"></label>
				</div>
			</div>
			<div class="modal-form-row">
				<div class="input-field">
					<input id="pwd" name="pwd" placeholder="mot de passe" type="password">
					<label for="pwd"></label>
				</div>
			</div>
			<input type='hidden' name='goto' value='<?php if(!empty($gotoMsg)){echo $gotoMsg;} ?>'>
			<div class=".modal-form-row">
				<button class="btn waves-effect waves-light light-blue darken-3" type="submit" name="connexion">Connexion
				</button>
			</div>
		</form>
		<!-- <p><a class="send-mail-to" href="#"> Réinitialiser votre mot de passe</a></p> -->
		 <p><a class="send-mail-to" href="pwd.php">Demander mes identifiants</a></p>
		 <!-- <p><a class="send-mail-to" href="help.php">Contacter le service technique</a></p> -->

	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
	</div>
</div>
<!-- MODAL INFO (doit apparaître avant connexion) -->
<!-- <div class="modal" id="modal-new">
	<div class="modal-content">
		<div class="row">
			<div class="col s2 m2 l2">
			<img src="public/img/icons/new-orange-sm.png">
			</div>
			<div class="col s10 m10 l10">
				<br>
						<h4 class="orange-text text-darken-3 center">Nouveau sur votre portail !</h4>
					</div>
		</div>

		 <p>Mise à disposition d'une PLV pour la livraison 24/48h : <a href="public/infos/plv-livraison-24-48h.pdf" class="blue-link">télécharger</a></p>
		 <p>Ajout d'une rubrique "documents" où vous trouverez : </p>
	 	 <ul class="browser-default">
		 	<li>les listing des ODR,</li>
		 	<li>les tickets et BRII</li>
		 	<li>le point stock MDD</li>
		 	<li>les résultats GFK</li>
		 </ul>
	</div>
	<div class="modal-footer">
		<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
	</div>
</div> -->



<!-- 13/06/2018 : suppression modal nouveauté -->
<div class="modal" id="modal-new">
	<div class="modal-content">
		<!-- <div class="row">
			<div class="col s2 m2 l2">
				<img src="public/img/icons/new-blue-sm.png">
			</div>
			<div class="col s10 m10 l10">
				<br>
				<h4 class="blue-text text-darken-3 center">Commmandes 48h</h4>
			</div>
		</div>
		<p>La période étant propice aux vacances, merci de communiquer à vos équipes réception et accueil de l'arrivée de vos commandes 48 h afin que celles-ci ne soient pas refusée</p> -->

		<div class="row">
			<div class="col s2 m2 l2">
				<img src="public/img/icons/new-orange-sm.png">
			</div>
			<div class="col s10 m10 l10">
				<br>
				<h4 class="orange-text text-darken-3 center">Modification du Réservable Téléviseur</h4>
			</div>
		</div>
		<p>A compter des catalogues du 4 septembre, les téléviseurs en réservable sont à partir des tailles supérieures ou égales à 46"</p>
		<!-- <p class="center-text"><center><img src="public/img/screenshot/navnew.png"></center></p> -->
		<!-- <br> -->
		<div class="modal-footer">
			<a href="#!" class="btn-flat modal-action modal-close waves-effect waves-default">fermer</a>
		</div>
	</div>
</div>

<!--  Scripts-->
<script src="vendor/jquery/jquery-3.2.1.js"></script>
<script src="vendor/materialize/js/materialize.js"></script>
<script type="text/javascript">

	// DETECTION IE 9
	 function msieversion() {
	 	var ua = window.navigator.userAgent;
	 	var msie = ua.indexOf("MSIE ");
		 if (msie > -1 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // Si c'est Internet Explorer, affiche le numéro de version
		 	// (parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
		 	if(parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)))=="9"){
		 		alert ("Votre navigateur est trop ancien.\n Merci d'utiliser Chrome ou Firefox")
		 	}
		 else
		 	// alert("C'est un autre navigateur");
		 return false;
		}
		msieversion();

	$(document).ready(function(){
		// menu hamburger
		$(".button-collapse").sideNav();
		// ouverture fenetre modal en auto

		$('#modal1').modal();
		$('#modal1').modal('open');
		$('#modal-new').modal();
		$('#modal-new').modal('open');

		$(".dropdown-button").dropdown();
		//infos bulles (navbar)
		 $('.tooltipped').tooltip({delay: 50});
		 var rev=$('#test').length
		 if(rev != 0){
		 	console.log(rev +"oui");
		 }else{
		 	console.log(rev + "non");
		 }

	});
</script>


</body>
</html>