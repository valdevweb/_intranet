<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  echo "pas de variable session";
  header('Location:'. ROOT_PATH.'/index.php');
}
include('../view/_head.php');
include('../view/_navbar.php');
// affichage du message après upload
//
if (isset($_POST['submit']))
{
	if ( !preg_match ( "/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/" , $_POST['date'] ) )
	{
		$link="merci de saisir une date valide";
		die;
	}
	$date=$_POST['date'];
	$req=$pdoBt->prepare("SELECT * FROM gazette where date= :date");
	$req->execute(array(
		':date'		=>$date
	));
	if($result=$req->fetch())
	{
		$file=$result['file'];

		$link= "<a href='http://172.30.92.53/".$version ."upload/gazette/" . $file . "'>" .$file ."</a>";
	}
		else
		{
			echo "pas de resultat";
		}

}

?>


<div class="container">
	<h1 class="header center grey-text text-darken-2">Historique des gazettes</h1>
	<div class="row">
		<h3 class="grey-text text-darken-2">Rechercher une gazette par date</h3>
	</div>
	<div class="row">
		<form method="post" action="histo-gazette.php" class="w3-container">
			<div class="row">
				<div class="col l2"></div>
				<div class="col l4">

					<!-- <label class="w3-text-grey" for="date">Saisir une date</label><br> -->
					<input type="date" class="w3-input w3-border" name="date" id="date" >
				</div>

				<div class="col l4 align-left">
					<button class="btn waves-effect waves-light orange darken-3" type="submit" name="submit" >Rechercher</button>
				</div>
				<div class="col l2"></div>

			</div>
		</form>
	</div>
	<div class="row">
		<p> Votre lien : </p>
		<?php if(isset($link)){echo $link;} ?>

	</div>
</div>


<?php


include('../view/_footer.php');

?>