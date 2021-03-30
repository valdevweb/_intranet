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
require '../../Class/InfoLivDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/DateHelpers.php';
require '../../Class/FormHelpers.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoFou=$db->getPdo('fournisseurs');

$infoLivDao=new infoLivDao($pdoDAchat);

$listOpAVenir=$infoLivDao->getOpAVenir();

$opToDisplay=$infoLivDao->getOpAVenir();


$listGt=FournisseursHelpers::getGts($pdoFou, "libelle","id");
$gt="";

include 'suivi-livraison-commun/01-filter-op.php';



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Le suivi livraison</h1>
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
			<?php if (!empty($listOpAVenir)): ?>
				<?php include 'suivi-livraison-commun/11-select-info-liv.php' ?>
				<div class="row mt-5">
					<div class="col"></div>
					<div class="col-lg-4">
						<div class="font-weight-boldless text-main-blue">
							Recherche un article :
						</div>
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="search_form">
							<div class="form-group text-center">
								<input type="text" class="form-control" name="str" id="str"  type="text" placeholder="Exemple : 'phil', '31510' ">
							</div>
						</form>
					</div>
					<div class="col"></div>

				</div>
				<?php include 'suivi-livraison-commun/12-table-info-liv.php' ?>
				<?php else: ?>
					<div class="alert alert-primary">Aucune information livraison n'a été saisie pour les opérations à venir</div>
				<?php endif ?>
			</div>
		</div>
		<div class="row  pb-5">
			<div class="col-auto">
				<h6 class="text-main-blue ">Exporter les opérations en cours au format excel :</h6>
			</div>
			<div class="col pr-2">
				<a href="xl-info-liv.php" class="btn bg-green">Export Excel</a>
			</div>
		</div>

	</div>


	<script type="text/javascript">


		var emplacement = null;

		function findString(str) {
			if (parseInt(navigator.appVersion) < 4) return;
			var strFound;
			if (window.find) {
				strFound = self.find(str);
				if (strFound && self.getSelection && !self.getSelection().anchorNode) {
					strFound = self.find(str)
				}
				if (!strFound) {
					strFound = self.find(str, 0, 1)
					while (self.find(str, 0, 1)) continue
				}
		}else if(navigator.appName.indexOf("Microsoft") != -1) {
			if (emplacement != null) {
				emplacement.collapse(false)
				strFound = emplacement.findText(str)
				if (strFound) emplacement.select()
			}
		if (emplacement == null || strFound == 0) {
			emplacement = self.document.body.createTextRange()
			strFound = emplacement.findText(str)
			if (strFound) emplacement.select()
		}
}
if (!strFound) alert("String '" + str + "' non trouvé!")
	return;
};

document.getElementById('search_form').onsubmit = function() {
	findString(this.str.value);
	return false;
};
</script>
<?php
require '../view/_footer-bt.php';
?>