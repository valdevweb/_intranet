<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// $descr="saisie déclaration mag hors qlik" ;
// $page=basename(__file__);
// $action="";
// // addRecord($pdoStat,$page,$action, $descr,$code=null,$detail=null)
// addRecord($pdoStat,$page,$action, $descr, 208);

// require_once '../../vendor/autoload.php';



//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getNbDeclarationByMonth($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT date_format(date_crea, '%Y-%m'),count(id) as nb, date_format(date_crea, '%m-%Y') as dateFr FROM dossiers group by date_format(date_crea, '%Y-%m') order by date_crea");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getStats($pdoStat)
{
	$req=$pdoStat->prepare("SELECT *  FROM `stats_logs`
		LEFT JOIN web_users.users on stats_logs.id_web_user=web_users.users.id
		LEFT JOIN magasin.mag ON web_users.users.galec=magasin.mag.galec
		WHERE `type_log` LIKE 'prod' AND type='mag' AND (`page` LIKE '%litige%' OR `page` LIKE '%declaration%')
		ORDER BY date_format(`stats_logs`.date_heure, '%Y-%m-%d'), magasin.mag.deno");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$statsReq=getStats($pdoStat);


$firstDay=new DateTime($statsReq[0]['date_heure']);
$lastDay=new DateTime(date('Y-m-d'));

$nbDays=$firstDay->diff($lastDay);
$nbDays=$nbDays->format('%a');


// on réorganise le résultat de la requete en tableau de date
$statByDay=[];
foreach ($statsReq as $stat)
{
	$statDate=new DateTime($stat['date_heure']);
	$statDate=$statDate->format('Y-m-d');
	if(!isset($statByDay[$statDate])){
		$statByDay[$statDate][]=$stat['deno'];
		$witnessMag=$stat['deno'];

	}
	else{
		if($stat['deno'] !=$witnessMag){
			$statByDay[$statDate][]=$stat['deno'];
			$witnessMag=$stat['deno'];

		}

	}
}

$litigeByMonth=getNbDeclarationByMonth($pdoLitige);
$jour=['dimanche','lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">

	<div class="row py-3">
		<div class="col">
			<p class="text-right"><a href="stat-litige-mag.php" class="btn btn-primary">Retour</a></p>
		</div>
	</div>
	<h1 class="text-main-blue ">Quelques chiffres</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

<div class="row">
				<div class="col">
					<h5 class="text-main-blue">Nb de déclarations par mois</h5>
					<p></p>
				</div>
			</div>
			<div class="row">
				<?php
				$col=0;
				foreach($litigeByMonth as $nbLitiges)
				{
					if($col<12)
					{
						echo '<div class="col">';
						echo $nbLitiges['dateFr'] .'<br>'.$nbLitiges['nb'];
						echo '</div>';
						$col++;
					}
					else
					{
						echo '</div>';
						echo '<div class="row">';
						echo '<div class="col">';
						echo $nbLitiges['dateFr'] .'<br>'.$nbLitiges['nb'];
						echo '</div>';
						$col=0;

					}

				}


				?>

			</div>

			<div class="row mt-5">
				<div class="col">
					<h5 class="text-main-blue">Nb de magasins ayant consulté les litiges </h5>
					<p>Cliquez sur la date pour afficher la liste des magasins</p>
				</div>
			</div>

			<div class="row">
				<div class="col" id="accordion">
					<?php
					for($i=0;$i<=$nbDays;$i++)
					{
						$day=(clone $firstDay)->modify("+ $i days");


						$dayFr=$day->format('d-m-Y');
						$dayGb=$day->format('Y-m-d');

					// echo "<pre>";
					// print_r($statByDay[$day]);
					// echo '</pre>';


						if(isset($statByDay[$dayGb]))
						{
							echo '<div class="text-main-blue accordion-toggle">'.$jour[intval($day->format('w'))].' '.$dayFr .' : ' .count($statByDay[$dayGb]) .' magasins</div>';

					// $magThisDay=$statByDay[$day];
							echo '<div class="hide">';
							for($j=0; $j<count($statByDay[$dayGb]);$j++){
								echo $statByDay[$dayGb][$j];
								echo '<br>';
							}
							echo '</div>';
						}

					}
					?>
				</div>
			</div>

			<!-- ./container -->
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				$('#accordion').find('.accordion-toggle').click(function(){


					$(this).next().toggleClass('show');

      //Hide the other panels
      // $(".accordion-content").not($(this).next()).slideUp('fast');

  });
			});
		</script>
		<?php
		require '../view/_footer-bt.php';
		?>