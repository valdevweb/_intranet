<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------



//header et nav bar
include ('../view/_head.php');
include ('../view/_navbar.php');

?>

<div class="container">

	<h1 class="red-text" >En cours de construction</h1>
	<div class="row">
		<div class="col l5">
			<p class="red">Merci d'envoyer votre demande  à btlecest.portailweb.exploitation@btlec.fr</p>
		</div>
	</div>
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
</form>
</div>

<br><br>
<?php
include('../view/_footer.php');
 ?>