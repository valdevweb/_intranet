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
require '../../Class/OdrDao.php';
require '../../Class/FormHelpers.php';
require '../../Class/FournisseursHelpers.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoFou=$db->getPdo('fournisseurs');



$odrDao=new OdrDao($pdoDAchat);
$listGt=FournisseursHelpers::getGts($pdoFou, "GT","id");


$listOdr=$odrDao->getOdrEncours();
$listEan=$odrDao->getOdrEanEncours();
$listFiles=$odrDao->getOdrFilesEncours();




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Les ODR</h1>
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
			<table class="table table-sm shadow-sm" id="odr">
				<thead class="thead-dark">
					<tr>
						<th>date de début</th>
						<th>date de fin</th>
						<th>GT</th>
						<th>Famille</th>
						<th>Marque</th>
						<th>EAN</th>
						<th>Fichiers</th>

					</tr>
				</thead>
				<tbody>
					<?php foreach ($listOdr as $key => $odr): ?>
						<tr>
							<td class="nowrap"><?=date('d-m-Y', strtotime($odr['date_start']))?></td>
							<td class="nowrap"><?=date('d-m-Y', strtotime($odr['date_end']))?></td>
							<td><?=($listGt[$odr['gt']])??""?></td>
							<td><?=$odr['famille']?></td>
							<td><?=$odr['marque']?></td>
							<td>
								<?php if (isset($listEan[$odr['id']])): ?>
									<?php for ($i=0; $i < count($listEan[$odr['id']]); $i++): ?>
										<?php if (!empty($listEan[$odr['id']][$i]['ean_file'])): ?>
											<a href="<?=URL_UPLOAD.'odr/'.$listEan[$odr['id']][$i]['ean_file']?>">liste des EAN</a><br>
										<?php endif ?>
										<?php if (!empty($listEan[$odr['id']][$i]['ean'])): ?>
											<?=$listEan[$odr['id']][$i]['ean']?><br>
										<?php endif ?>
									<?php endfor ?>
								<?php endif ?>
							</td>
							<td>
								<?php if (isset($listFiles[$odr['id']])): ?>
									<?php for ($i=0; $i < count($listFiles[$odr['id']]); $i++): ?>
										<a href="<?=URL_UPLOAD.'odr/'.$listFiles[$odr['id']][$i]['file']?>"><?=(empty($listFiles[$odr['id']][$i]['filename']))?'<i class="fas fa-file-alt pr-3"></i>':$listFiles[$odr['id']][$i]['filename']?></a>

									<?php endfor ?>
								<?php endif ?>

							</td>


						</tr>
					<?php endforeach ?>

				</tbody>
			</table>
		</div>
	</div>


</div>
<script src="../js/excel-filter.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#odr').excelTableFilter();
		});

	</script>
<?php
require '../view/_footer-bt.php';
?>