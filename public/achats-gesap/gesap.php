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
require '../../Class/GesapDao.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gesapDao=new GesapDao($pdoDAchat);

$listGesap=$gesapDao->getListGesap();
$listFiles=$gesapDao->getListFiles();






//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Les Gesap</h1>
		</div>
	</div>
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
				<div class="alert alert-primary"><i class="fas fa-lightbulb pr-3"></i>Vous pouvez filtrer le tableau en utilisant les flèches qui sont sur l'entête du tableau. Les filtres sont cumulables<br>
				<i class="fas fa-lightbulb pr-3"></i>Pour afficher le guide d'achat, cliquez sur le lien qui est dans la colonne guide d'achat
				</div>
			</div>
		</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($listGesap)): ?>

				<table class="table table-sm" id="table-gesap">
					<thead class="thead-dark">
						<tr>
							<th>Nom de l'opération</th>
							<th>Salon</th>
							<th>Catalogue</th>
							<th>Code op</th>
							<th>Date de remontée</th>
							<th>Guide d'achat</th>
							<th>Commentaire</th>
							<th>Fichiers</th>

						</tr>
					</thead>
					<tbody>



						<?php foreach ($listGesap as $key => $gesap): ?>
							<tr>
								<td><?=$gesap['op']?></td>
								<td><?=$gesap['salon']?></td>
								<td><?=$gesap['cata']?></td>
								<td><?=$gesap['code_op']?></td>
								<td><?=date('d-m-Y', strtotime($gesap['date_remonte']))?></td>
								<td><a href="<?=URL_UPLOAD.'gesap/'.$gesap['ga_file']?>"><?=$gesap['ga_num']?></a></td>
								<td><?=$gesap['cmt']?></td>

								<?php if (!empty($listFiles) && isset($listFiles[$gesap['id']])): ?>
								<td>

									<?php for($i=0;$i<count($listFiles[$gesap['id']]);$i++): ?>
										<a href="<?=URL_UPLOAD.'gesap/'.$listFiles[$gesap['id']][$i]['file']?>"><?=empty($listFiles[$gesap['id']][$i]['filename'])?'<i class="fas fa-file pb-3"></i>':$listFiles[$gesap['id']][$i]['filename']?></a><br>
									<?php endfor ?>
								</td>
								<?php else: ?>
									<td></td>
								<?php endif ?>

							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				<?php else: ?>
					<div class="alert alert-primary">
						Pas de GESAP à afficher
					</div>
				<?php endif ?>

			</div>
		</div>

		<div class="row  py-5">
			<div class="col-auto">
				<h6 class="text-main-blue ">Exporter les Gesap au format excel :</h6>
			</div>
			<div class="col pr-2">
				<a href="xl-gesap.php" class="btn bg-green">Export Excel</a>
			</div>
		</div>





	</div>
	<script src="../js/excel-filter.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#table-gesap').excelTableFilter();
		});

	</script>
	<?php
	require '../view/_footer-bt.php';
	?>