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
		$linkSearch="merci de saisir une date valide";
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

		$linkSearch= "<a href='http://172.30.92.53/".$version ."upload/gazette/" . $file . "'>" .$file ."</a>";
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
	</div>

	<div class="row">
		<h4 class="light-blue-text text-darken-2">Listing des gazettes de 2018</h4>
	</div>
	<div class="row">
		<ul class="collapsible" data-collapsible="accordion">
		<?php
		//semaine à partir de laquelle on va afficher l'historique des gazettes
		//l'année 2018 commence semaine 0 donc on retire 1 à la semaine actuelle
			$year=2018;
			$nbWeek=new DateTime();
			$nbWeek=$nbWeek->format('W');
			$nbWeek=(int)$nbWeek-1;

				for ($week=$nbWeek; $week >=0 ; $week--)
				{
					$histo=histoGaz($pdoBt,$week,$year);
					$link="http://172.30.92.53/".$version."upload/gazette/";

		?>
			<!--un bloc semaine-->
			<li>
				<!-- on rajoute 1 au numéro de semaine pour l'affichage -->
				<div class="collapsible-header"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Semaine <?=$week+1 ?><span class="new badge blue" data-badge-caption="gazette(s)"><?=count($histo)?></span></div>
				<div class="collapsible-body">
					<ul class="browser-default">
						<?php
							foreach ($histo as $gazette)
							{
							echo "<li><a href='".$link.$gazette['file']."'>" .$gazette['file'] ."</a></li>";
							}
						?>
					</ul>
				</div>
			</li>
		<?php
		}//fin du for qui parcours les semaines
		?>
		</ul>
 	</div> <!--row accordeon 2018 -->

	<div class="row">
		<h4 class="light-blue-text text-darken-2">Listing des gazettes de 2017</h4>
	</div>

	<div class="row">
		<ul class="collapsible" data-collapsible="accordion">
		<?php
		//l'hiisto des gazettes en 2017 commence à la semaine 48
			$year=2017;
			$nbWeek2017=getIsoWeeksInYear(2017);
				for ($week=52; $week >=48 ; $week--)
				{
					$histo=histoGaz($pdoBt,$week,$year);
					$link="http://172.30.92.53/".$version."upload/gazette/";
				?>
			<!--un bloc semaine-->
			<li>
				<div class="collapsible-header"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Semaine <?=$week ?><span class="new badge blue" data-badge-caption="gazette(s)"><?=count($histo)?></span></div>
				<div class="collapsible-body">
					<ul class="browser-default">
						<?php
							foreach ($histo as $gazette)
							{
							echo "<li><a href='".$link.$gazette['file']."'>" .$gazette['file'] ."</a></li>";

							}
						?>
					</ul>
				</div>
			</li>
			<?php
			//fin du for qui parcours les semaines
				}
				?>
			</ul>
 </div> <!--row accordeon 2017 -->


<!-- formulaire de recherche -->
	<div class="row">
		<h4 class="light-blue-text text-darken-2">Chercher une gazette par date</h4>
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
		<?php if(isset($linkSearch)){echo "<p> Votre lien : ". $linkSearch ."</p>";} ?>
	</div>
<!-- END formulaire de recherche -->
</div><!-- END container -->




<?php
include('../view/_footer.php');

?>