<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}



$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
require '../../Class/Db.php';
require '../../Class/evo/EvoDao.php';
require '../../Class/evo/PlanningDao.php';
require '../../Class/evo/EvoHelpers.php';

$errors=[];
$success=[];
$db=new Db();

$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');

$evoDao=new EvoDao($pdoEvo);
$planningDao=new PlanningDao($pdoEvo);


$listEtat=EvoHelpers::arrayEtat($pdoEvo);


$listEvo=$evoDao->getListEvoUser($_SESSION['id_web_user']);

$listPlanning=$planningDao->getPlanningEvoUserByEvo($_SESSION['id_web_user']);


$errors=[];
$success=[];





include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<h1 class="text-main-blue py-5 ">Suivi de vos demandes d'évolutions</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row pb-5">
		<div class="col ">
			<table class="table table-sm shadow" id="list-evo">
				<thead class="thead-light">
					<tr>
						<th class="text-right pr-5">#</th>
						<th>Application / module</th>
						<th>Objet</th>
						<th>Etat</th>
						<th>Planification</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($listEvo as $key => $evo): ?>

						<tr>
							<td class="text-right"><?=$evo['id']?></td>
							<td><?=$evo['appli'] ?><?= (!empty($evo['module']))?"/".$evo['module']:""?></td>
							<td><a href="evo-detail.php?id=<?=$evo['id']?>"><?=$evo['objet']?></a></td>
							<td><?=$listEtat[$evo['id_etat']]?></td>
							<td>
								<?php if (isset($listPlanning[$evo['id']])): ?>
									<?php foreach ($listPlanning[$evo['id']] as $key => $planning): ?>
										du <?=date('d/m/Y', strtotime($planning['date_start']))?> au <?=date('d/m/Y', strtotime($planning['date_end']))?> <br>
									<?php endforeach ?>
								<?php endif ?>
							</td>
						</tr>
					<?php endforeach ?>

				</tbody>
			</table>
		</div>
	</div>

	<!-- ./container -->
</div>
<script src="../js/datatables.min.js"></script>
<!-- si besoin filter colonne date -->
<script src="../js/datatables-dates.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		$('#list-evo').DataTable({
			language: {
				processing:     "Traitement en cours...",
				search:         "Rechercher&nbsp;:",
				lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
				info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
				infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				infoPostFix:    "",
				loadingRecords: "Chargement en cours...",
				zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
				emptyTable:     "Aucune donnée disponible dans le tableau",
				paginate: {
					first:      "Premier",
					previous:   "Pr&eacute;c&eacute;dent",
					next:       "Suivant",
					last:       "Dernier"
				},
				aria: {
					sortAscending:  ": activer pour trier la colonne par ordre croissant",
					sortDescending: ": activer pour trier la colonne par ordre décroissant"
				}
			},
			"pageLength": 25,

		});
	});
</script>
<?php
require '../view/_footer-bt.php';
?>