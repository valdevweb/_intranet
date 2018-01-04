<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
  echo "pas de variable session";
  header('Location:'. ROOT_PATH.'/index.php');
}

require "../../functions/gazette.fn.php";
require "../../functions/stats.fn.php";



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------

$descr="page historique et recherche des gazettes " ;
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);
//----------------------------------------


include('../view/_head.php');
include('../view/_navbar.php');

//upload des gazette renvoi sur cette page
if (!empty($_GET['type'])) {
    if ($_GET['type'] === 'success') {
        $message = 'La gazette a été uploadé avec succés';
    }
}




//recherche gazette
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
	<div class="row">

		<h1 class="light-blue-text text-darken-2">La Gazette</h1>
		<?php
	// affichage des erreurs
	//	 if (!empty($message)) echo '<p>'.$message.'</p>';
		?>
		<div class="row center">
			<p><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;&nbsp;&nbsp;Attention page encore en construction</p>
		</div>
		<div class="row">
			<h4 class="light-blue-text text-darken-2">Historique des gazettes</h4>
			<!-- <p>Rechercher une gazette </p> -->

			<form method="post" action="gazette.php#result" class="w3-container">
				<div class="col l2"></div>
				<div class="col l4">

					<label class="w3-text-grey" for="date">Selectionnez la date à partir du 1er décembre 2017</label>
					<input type="date" class="w3-input w3-border" name="date" id="date" >
				</div>

				<div class="col l4 align-left">

					<br>

					<button class="btn waves-effect waves-light orange darken-3 align-right" type="submit" name="submit" >Rechercher</button>
				</div>
				<div class="col l2"></div>


			</form>
		</div>
		<div class="row" id="result">

			<?php if(isset($link)){echo "<p> Votre lien : ". $link ."</p>";} ?>

		</div>





	<h4 class="light-blue-text text-darken-2">BIENTOT : Les gazettes par semaine</h4>

	<ul class="collapsible" data-collapsible="accordion">
		<li>
			<div class="collapsible-header">
				<i class="fa fa-newspaper-o" aria-hidden="true"></i>
				Semaine 50
				<span class="new badge blue" data-badge-caption="nouvelles">4</span>

			</div>
			<div class="collapsible-body">
				<ul class="browser-default">
					<!-- <li>lien 1</li>
					<li>lien 2</li>
					<li>lien 3</li>
					<li>lien 4</li> -->
				</ul>
			</div>
		</li>
		<li>
			<div class="collapsible-header">
				<i class="fa fa-newspaper-o" aria-hidden="true"></i>
				Semaine 49
				<span class="badge">5</span></div>
				<div class="collapsible-body">
					<ul class="browser-default">
						<li class="browser-default">
 						</li>
					</ul>
				</div>
			</li>
		</ul>


 </div> <!--row-->









</div> <!--container-->



<?php
include('../view/_footer.php');

?>