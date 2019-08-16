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


//----------------------------------------------
// css dynamique
//----------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";





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
	$linkSearch="";
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
	if($result=$req->fetchAll(PDO::FETCH_ASSOC))
	{
		foreach ($result as $value)
		{

			$file=$value['file'];
			$linkSearch.="<p><a href='http://172.30.92.53/".$version ."upload/gazette/" . $file . "'>" .$file ."</a></p>";
		}
		// $file=$result['file'];

		// $linkSearch= "<a href='http://172.30.92.53/".$version ."upload/gazette/" . $file . "'>" .$file ."</a>";
	}
		else
		{
			echo "pas de resultat";
		}
		unset( $_POST);

}


?>
<div class="container"  id="up">
	<div class="myrow">
		<img class="w3-grayscale gazette" src="../img/gazette/newspaper.jpg">
	</div>
	<div class="row">
		<div class="col l1 m1"></div>
		<div class="col l2 m2 s4 mini-nav">
			<p><a href="#h2019"><i class="fa fa-newspaper-o" aria-hidden="true"></i>la gazette</a></p>
			<p><a href="#h2019"><i class="fa fa-angle-double-right" aria-hidden="true"></i>2019</a></p>
			<p><a href="#h2018"><i class="fa fa-angle-double-right" aria-hidden="true"></i>2018</a></p>
			<p><a href="#h2017"><i class="fa fa-angle-double-right" aria-hidden="true"></i>2017</a></p>


		</div>
		<div class="col l2 m2 s4 mini-nav">
			<p><a href="#a2018"><i class="fa fa-newspaper-o" aria-hidden="true"></i>la gazette suivi livraison Catalogue</a></p>
		</div>
		<div class="col l2 m2 s4 mini-nav">
			<p><a href="#opp"><i class="fa fa-newspaper-o" aria-hidden="true"></i>la gazette alerte promo</a></p>

		</div>
		<div class="col l2 m2 mini-nav">
			<p><a href="#spe"><i class="fa fa-newspaper-o" aria-hidden="true"></i>la gazette spéciale</a></p>

		</div>

		<div class="col l2 m2 s4 mini-nav">
			<p><a href="#search"><i class="fa fa-search" aria-hidden="true"></i>Rechercher</a></p>
		</div>
		<div class="col l1 m1"></div>

	</div>

		<h1 class="light-blue-text text-darken-2">les Gazettes</h1>


	<div class="row">
		<h4 class="light-blue-text text-darken-2" id="h2019">Listing des gazettes de 2019</h4>
	</div>
	<div class="row">
		<ul class="collapsible" data-collapsible="accordion">
		<?php
		//semaine à partir de laquelle on va afficher l'historique des gazettes
		//l'année 2018 commence semaine 0 donc on retire 1 à la semaine actuelle
			$year=2019;
			$nbWeek=new DateTime();
			$nbWeek=$nbWeek->format('W');
			$nbWeek=(int)$nbWeek-1;
			// tricherie pour le 31/12/2018 qui tombe la 1ere semaine de 2019.....
			$rustine=0;

			$category="gazette";
				for ($week=$nbWeek; $week >=0 ; $week--)
				{
					if($week==0 && $rustine==0)
					{
						$histo=histoGaz($pdoBt,52,2018,$category);
						$link="http://172.30.92.53/".$version."upload/gazette/";
						$rustine=1;
					}
					else{
						$histo=histoGaz($pdoBt,$week,$year,$category);
						$link="http://172.30.92.53/".$version."upload/gazette/";


					}

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
							echo "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gazette['file']."'>" .$gazette['file'] ."</a></li>";
							}
						?>
					</ul>
				</div>
			</li>
		<?php
		}//fin du for qui parcours les semaines
		?>
		</ul>
 	<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>

 	</div> <!--row accordeon 2018 -->

	<div class="row">
		<h4 class="light-blue-text text-darken-2" id="h2018">Listing des gazettes hebdo de 2018</h4>
	</div>

	<div class="row">
		<ul class="collapsible" data-collapsible="accordion">
		<?php
		//l'hiisto des gazettes en 2017 commence à la semaine 48
			$year=2018;
			$nbWeek2018=getIsoWeeksInYear(2018);
			$nbWeek2018= $nbWeek2018 -1;
			$category="gazette";
				for ($week=$nbWeek2018; $week >=0 ; $week--)
				{
					$histo=histoGaz($pdoBt,$week,$year, $category);
					$link="http://172.30.92.53/".$version."upload/gazette/";
				?>
			<!--un bloc semaine-->
			<li>
				<div class="collapsible-header"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Semaine <?=$week+1 ?><span class="new badge blue" data-badge-caption="gazette(s)"><?=count($histo)?></span></div>
				<div class="collapsible-body">
					<ul class="browser-default">
						<?php
							foreach ($histo as $gazette)
							{
							echo "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gazette['file']."'>" .$gazette['file'] ."</a></li>";
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
 		<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>
 	</div>
 	<div class="row">
		<h4 class="light-blue-text text-darken-2" id="h2017">Listing des gazettes hebdo de 2017</h4>
	</div>

	<div class="row">
		<ul class="collapsible" data-collapsible="accordion">
		<?php
		//l'hiisto des gazettes en 2017 commence à la semaine 48
			$year=2017;
			$nbWeek2017=getIsoWeeksInYear(2017);
			$nbWeek2017= $nbWeek2017 -1;
			$category="gazette";
				for ($week=$nbWeek2017; $week >=48 ; $week--)
				{
					$histo=histoGaz($pdoBt,$week,$year, $category);
					$link="http://172.30.92.53/".$version."upload/gazette/";
				?>
			<!--un bloc semaine-->
			<li>
				<div class="collapsible-header"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Semaine <?=$week+1 ?><span class="new badge blue" data-badge-caption="gazette(s)"><?=count($histo)?></span></div>
				<div class="collapsible-body">
					<ul class="browser-default">
						<?php
							foreach ($histo as $gazette)
							{
							echo "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gazette['file']."'>" .$gazette['file'] ."</a></li>";
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
 		<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>
 	</div>






 	<div class="row ">
		<h4 class="light-blue-text text-darken-2" id="opp">Les alertes promo</h4>
	</div>
	<div class="row bg-white small-padding">
	<ul class="browser-default"><li><a class='stat-link' data-user-session="<?=$_SESSION['user']?>" href="http://172.30.92.53/OPPORTUNITES/index.html" target="_blank">les opportunités du jour</a></li></ul>
	<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>
	</div>
 <!--row accordeon 2017 -->

<!--
*
*		gazette appros
*
 -->

<div class="row">
		<h4 class="light-blue-text text-darken-2" id="a2018">Suivi livraison Catalogue :</h4>
	</div>
	<div class="row bg-white">
		<ul class="browser-default">
		<?php
			$histo=showLastGazettesAppros($pdoBt);
			$link="http://172.30.92.53/".$version."upload/gazette/";
			foreach ($histo as $gazette)
			{
					//modif du 20/06
				if(!empty($gazette['title']))
				{
					$detail=" : <ul class='browser-default'><li>";
					$detail.=str_replace("<br />","</li><li>",$gazette['title']);
					$detail.= "</li></ul>";
				}
				else
				{
					$detail="";
				}
				$filename=$gazette['file'];
				$filename=explode(".",$filename);
				$approFilename=$filename[0];
				echo "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$gazette['file']."'><strong>" .$approFilename ."</strong> operations du " .$gazette['deb']. " au ".$gazette['fin'] .$detail ."</a> </li>";
			}
		?>
		</ul>
 	<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>

 	</div>
	<div class="row">
		<h4 class="light-blue-text text-darken-2" id="spe">Les gazettes spéciales</h4>
	</div>

	<div class="row bg-white">
		<ul class="browser-default">
			<?php
			$gSpe=showAllSpe($pdoBt);
			foreach ($gSpe as $spe)
			{
				echo "<li><a class='simple-link stat-link' data-user-session='".$_SESSION['user']."' href='".$link.$spe['file']."'>" .$spe['title'] ."</a> </li>";
			}
			?>
		</ul>
	</div>





<!-- formulaire de recherche -->
	<div class="row">
		<h4 class="light-blue-text text-darken-2" id="search">Chercher une gazette par date</h4>
		<form method="post" action="gazette.php#result" class="w3-container bg-white">
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
 	<p class="uptonav"><a href="#up" class="uptonav">retour au menu</a></p>

	</div>
	<div class="row" id="result">
		<?php if(isset($linkSearch)){echo "<p>Resultat(s) : </p>". $linkSearch ;} ?>
	</div>
<!-- END formulaire de recherche -->
</div><!-- END container -->




<?php
include('../view/_footer.php');

?>