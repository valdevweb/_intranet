

<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------------------------
require "../../functions/stats.fn.php";
$descr="page pour réouvrir une demande";
$page=basename(__file__);
$action="consultation";
$code=101;
// addRecord($pdoStat,$page,$action, $descr,$code);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
// $page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//header et nav bar
include ('../view/_head-mig-bis.php');
include ('../view/_navbar.php');
include('../../functions/utilities.fn.php');
//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------


$dorisDir="D:\btlec\doris";


function getDoris($pdoBt)
{
	$req=$pdoBt->query("SELECT filename, date_extraction,centrale,MONTH(date_extraction) as mois,DATE_FORMAT(date_extraction, '%Y')as year FROM doris ORDER BY date_extraction DESC, centrale");
	$req->execute();
	return	$req-> fetchAll(PDO::FETCH_ASSOC);
}

$listDoris=getDoris($pdoBt);

$startYear="";
$startMonth="";

?>
<div class="container">
	<div class="row">
		<div class="col">
			<div class="shadow-sm bg-white rounded border p-2">
				<h1 class="blue-text text-darken-4 text-center">Les analyses doris<br><span class="sub-h1"><i class="fa fa-area-chart" aria-hidden="true"></i></span></h1>
				<!-- <div class="year-slide">
					<div class="rect"><p>2018</p></div><div class="triangle"></div><div class="triangle-bdr"></div>
					<div class="month-rect-1 text-center">&nbsp; Janv.</div>
					<div class="month-rect month-rect-2"></div>
					<div class="month-rect month-rect-3"></div>
					<div class="month-rect month-rect-4"></div>
					<div class="month-rect month-rect-5"></div>
					<div class="month-rect month-rect-6"></div>
					<div class="month-rect month-rect-7"></div>
					<div class="month-rect month-rect-8"></div>
					<div class="month-rect month-rect-9"></div>
					<div class="month-rect month-rect-10"></div>
					<div class="month-rect month-rect-11"></div>
					<div class="month-rect month-rect-12"></div>
				</div> -->
				<?php

				foreach ($listDoris as $doris)
				{
					if($doris['year']!=$startYear)
					{
						echo'<div class=""><time datetime="" class="icon"><em>&nbsp;</em><strong>&nbsp;</strong><span>'.$doris['year'].'</span></time></div>';


						echo "<br>";
						echo "<br>";
						echo '<div class="text-center month pb-4">___ '.ucfirst($monthsStr[$doris['mois']]).' ___</div>';
						echo '<p class="text-center"><a href="http://172.30.92.53/doris/'.$doris['filename'].'" class="simple">'.$doris['filename'].'</a></p>';
						$startYear=$doris['year'];
						$startMonth=$doris['mois'];
					}
					else
					{
						if($startMonth!=$doris['mois'])
						{
							echo "<br>";
							echo '<div class="text-center month pb-4">___ '.ucfirst($monthsStr[$doris['mois']]).' ___</div>';
							echo '<p class="text-center"><a href="http://172.30.92.53/doris/'.$doris['filename'].'"class="simple">'.$doris['filename'].'</a></p>';
							$startMonth=$doris['mois'];

						}
						else
						{
						echo '<p class="text-center"><a href="http://172.30.92.53/doris/'.$doris['filename'].'" class="simple">'.$doris['filename'].'</a></p>';

						}

					}
				}
				?>



			</div>


		</div>
	</div>


</div>




<?php


//contenu
// include('news-alert.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer-mig-bis.php');
?>

