
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/w3c/w3c.css">
	<title>Connexion - portail Btlec Est</title>
	<style type="text/css">

		#containervm{
			width: 800px;
			margin: auto;
			box-shadow: 3px 2px 5px #d6d6db;
		}
		.w3-input{
			width: 400px;
		}
		.w3-theme-l5 {color:#000 !important; background-color:#f5f7f8 !important}
		.w3-theme-l4 {color:#000 !important; background-color:#dfe5e8 !important}
		.w3-theme-l3 {color:#000 !important; background-color:#becbd2 !important}
		.w3-theme-l2 {color:#000 !important; background-color:#9eb1bb !important}
		.w3-theme-l1 {color:#fff !important; background-color:#7d97a5 !important}
		.w3-theme-d1 {color:#fff !important; background-color:#57707d !important}
		.w3-theme-d2 {color:#fff !important; background-color:#4d636f !important}
		.w3-theme-d3 {color:#fff !important; background-color:#435761 !important; text-align: center !important;}
		.w3-theme-d4 {color:#fff !important; background-color:#3a4b53 !important}
		.w3-theme-d5 {color:#fff !important; background-color:#303e45 !important}

		.w3-theme-light {color:#000 !important; background-color:#f5f7f8 !important}
		.w3-theme-dark {color:#fff !important; background-color:#303e45 !important}
		.w3-theme-action {color:#fff !important; background-color:#303e45 !important}

		.w3-theme {color:#fff !important; background-color:#607d8b !important}
		.w3-text-theme {color:#607d8b !important}
		.w3-border-theme {border-color:#607d8b !important}

		.w3-hover-theme:hover {color:#fff !important; background-color:#607d8b !important}
		.w3-hover-text-theme:hover {color:#607d8b !important}
		.w3-hover-border-theme:hover {border-color:#607d8b !important}
		.w3-container{
			margin: auto;
			width: 450px;
		}
		.red{
			color: red;
			font-weight: bold;
		}
	</style>
	<title>contact service technique</title>
</head>
<body>
	<div id="containervm">
	<h1 class="red" >En cours de construction</h1>
	<p class="red">Merci d'envoyer votre demande  à btlecest.portailweb.exploitation@btlec.fr</p>
	<h1 class="w3-theme-d3">Contacter le service technique</h1>
<br><br>
<form class="w3-container" action="help.php" method="post">
    <div>
        <label for="nom">Nom :</label>
        <input type="text" class="w3-input w3-border-theme w3-light-grey" id="nom" name="nom" />
    </div>
    <div>
        <label for="courriel">mail :</label>
        <input type="email" class="w3-input w3-border-theme w3-light-grey" id="mail" name="mail"/>
    </div>
    <div>
        <label for="message" class="w3-text-theme">Message :</label>
        <textarea id="message" class="w3-input w3-border-theme w3-light-grey" name="message"></textarea>
    </div>
    <div>
    	<p><button class="w3-btn w3-blue-grey" name="help" >Envoyer</button></p>
    </div>
    <p class="w3-right-align"><a href="index.php">retour</a></p>
</form>


<br><br>
</div>
</body>
</html>