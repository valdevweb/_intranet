<?php

include('../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. AUTHENTIFICATION);
}
//----------------------------------------------------------------
//		INCLUDES
//----------------------------------------------------------------

require "../functions/stats.fn.php";
$descr="historique culturel - mag";
$page=basename(__file__);
$action="consultation";
$code=101;
addRecord($pdoStat,$page,$action, $descr,$code);


//----------------------------------------------
// css dynamique
//----------------------------------------------
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile="../css/".$pageCss.".css";

//----------------------------------------------
//  		FUNCTIONS
//----------------------------------------------
function nbDossiers($pdoSav){
	$req=$pdoSav->prepare("SELECT count(id) as nb FROM culturel_dossiers WHERE id_web_user= :id_web_user AND etat ='clos'");
	$req->execute(array(
		':id_web_user'		=>$_SESSION['id_web_user'],

	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$nb=nbDossiers($pdoSav);
$nbResult=$nb['nb'];

function getDossiers($pdoSav,$limit,$debut){
	$req=$pdoSav->prepare("SELECT id, DATE_FORMAT(date_envoi, '%d/%m/%Y') as dateenvoi,nom FROM culturel_dossiers WHERE id_web_user= :id_web_user AND etat ='clos' ORDER BY date_envoi DESC LIMIT $limit OFFSET $debut");
	$req->execute(array(
		':id_web_user'		=>$_SESSION['id_web_user']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return	$req->errorInfo();
}


//8 résultat par page
$limit=10;
//calcul de l'offset (debut)
$page = (!empty($_GET['page']) ? $_GET['page'] : 1);
$debut = ($page - 1) * $limit;

$nbPages=ceil($nbResult/$limit);
$dossiers=getDossiers($pdoSav,$limit,$debut);


include('../view/_head.php');
include('../view/_navbar.php');
?>

<div class="container py-5">
	<!-- main title -->
	<div class="row">
		<div class="col-2">
			<time datetime="" class="icon"><em>&nbsp;</em><strong>&nbsp;</strong><span><i class="fas fa-history"></i></span></time>
		</div>
		<div class="col">
			<h1 class="text-center underline-anim mt-5">Historique de vos demandes d'enlèvement culturel</h1>
		</div>
		<div class="col-2"></div>
	</div>
	<!-- ./main title-->

	<!-- tableau histo -->
	<div class="row mt-4">
		<div class="col-2"></div>
			<div class="col">
				<table class="table shadow">
					<thead class="thead-dark">
						<tr>
							<th scope="col">N° de dossier</th>
							<th scope="col">Date d'envoi</th>
							<th scope="col">Demandeur</th>
							<th class='text-right' scope="col">Détails</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($dossiers as $dossier)
							{
								echo '<tr><td>'.$dossier['id'] .'</td>';
								echo '<td>'.$dossier['dateenvoi'] .'</td>';
								echo '<td>'.$dossier['nom'] .'</td>';
								echo '<td class="text-right"><a href="#" class="more" id="'.$dossier['id'].'">voir</a></td></tr>';
							}

						 ?>
					</tbody>
					</table>
				</div>
		<div class="col-2"></div>
	</div>


<div class="row">
	<div class="col">
		<div id="details">

		</div>
	</div>
</div>
<!-- start row -->
	<div class="row ">
		<div class="col-lg-1 col-xl-2"></div>
		<div class="col">
			<p class="text-center blue-link">
				<?php
				$prev=$page-1;
				$next=$page +1;
				if($nbPages>=10)
				{
					$groupPage=ceil($nbPages/10);
					// echo $groupPage;
				}
				else{
					$groupPage=1;

				}

				if ($page > 1)
				{
					echo '<a href="?page='. $prev .'"><i class="fas fa-backward pr-1"></i></a>  ';
				}
				else
				{
					echo '<span  class="not-active"><i class="fas fa-backward pr-1"></i></span>';
				}
				// détermine dans quel group de page la page se situe :  de 1 à 10 (group 1) ou de 11 à 20 (group 2), etc
				// pour n'afficher que ce group
				$group=ceil($nbPages/10);
				$nowGroup=floor($page/10)+1;
				// si on arrive au dernière groupe de page, on vérifie assigne end à ce nb de page et au group de pages fois 10
				// ca eviter les numéros de pages inutilecar plus de résultat
				if($nowGroup==$group)
				{

					$end=$nowGroup*10;
					$start=$end-9;
					$end=$nbPages;

				}
				else
				{
					$end=$nowGroup*10;
					$start=$end-9;

				}
				// echo $nowGroup;

				for ($i =$start ; $i <=$end ; $i++)
				{
					if($i==$page)
					{
						echo '<a href="?page='.$i.'" class="not-active pr-1">'.$i.'</a>';
					}
					else
					{
						echo '<a href="?page='.$i.'" class="pr-1">'.$i.'</a>';

					}

				}



				if ($page < $nbPages)
				{

					echo '  <a href="?page='. $next .'"><i class="fas fa-forward"></i></a>';
				}
				else
				{
					echo '<span  class="not-active"><i class="fas fa-forward"></i></span>';
				}


				?>
			</p>
		</div>
		<div class="col-lg-1 col-xl-2"></div>
	</div>
<!-- ./row -->


	<!-- fin container -->
</div>
<script type="text/javascript">
		$(document).ready(function(){
			$('.more').on('click',function(){
				var id=this.id;

				if(id){
					$.ajax({
						type:'POST',
						url:'ajax-culturel-histo.php',
						data:'id='+id,
						success:function(html){
							$('#details').append(html);
						}
					});
				}
				else
				{
					$('#details').html('');
				}

			});

			$('#details').on('click', function(){
					$('#details').empty();
			});
		});
</script>
<?php
// include('../view/_flash.php');



include('../view/_footer.php');



 ?>