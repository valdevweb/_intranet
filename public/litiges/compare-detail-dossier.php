<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	echo "pas de variable session";

}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];



//------------------------------------------------------
//			FONCTION

function getDetail($pdoLitige,$dateStart, $dateEnd){
	$req=$pdoLitige->prepare("SELECT details.id, id_dossier, dossiers.dossier, valo_line, valo FROM `details`Left join dossiers on id_dossier=dossiers.id WHERE date_crea BETWEEN :date_start AND :date_end order by id_dossier, details.id");
	$req->execute([
		':date_start'		=>$dateStart,
		':date_end'			=>$dateEnd
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$dateStart="2020-01-01 00:00:00";
$dateEnd="2020-12-31 23:59:00";
$list=getDetail($pdoLitige, $dateStart, $dateEnd);
$prevValo=0;
$dossierEncours=0;
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
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<table class="table table-sm">
		<thead class="thead-dark">
			<tr>
				<th>id_dossier</th>
				<th>dossier</th>
				<th>id_detail</th>
				<th>valo detail</th>
				<th>valo somme</th>
				<th>dossier</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $key => $value): ?>

				<?php if ($dossierEncours!=$value['id_dossier']): ?>
					<tr class="bg-grey">
						<td colspan="4"></td>
						<td><?=$prevValo?></td>
						<td><?=$value['valo']?></td>
					</tr>
				<?php endif ?>

				<?php
				if($dossierEncours!=$value['id_dossier']){
					$prevValo=$value['valo_line'];
				}else{
					$prevValo=$value['valo_line'] + $prevValo;
				}
				?>
				<tr>
					<td><?=$value['id_dossier']?></td>
					<td><?=$value['dossier']?></td>
					<td><?=$value['id']?></td>
					<td><?=$value['valo_line']?></td>
					<td><?=$prevValo?></td>
					<td></td>
				</tr>
				<?php
				$dossierEncours=$value['id_dossier'];
				?>
			<?php endforeach ?>

		</tbody>
	</table>
	<?php
	echo "<pre>";
	print_r($list);
	echo '</pre>';

	?>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>