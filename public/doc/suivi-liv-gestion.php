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
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');


if (isset($_POST['choose'])) {
	$monday=date('Y-m-d',strtotime('2021-W10'));
	$sunday=(new DateTime($monday))->modify("+ 6 days")->format('Y-m-d');
	$query="SELECT * FROM cata_op WHERE date_start BETWEEN $monday AND $sunday";
	echo $query;
	$req=$pdoQlik->query("SELECT * FROM cata_op WHERE (date_start BETWEEN '$monday' AND '$sunday') AND (origine='B' OR origine='G')");
	$data=$req->fetchAll();
		echo "<pre>";
		print_r($data);
		echo '</pre>';




}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Main title</h1>
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
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="form-group">
					<label for="week"></label>
					<input type="week" class="form-control" name="week" id="week">
				</div>
				<div class="row">
					<div class="col text-right">
						<button class="btn btn-primary" name="choose">Valider</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
require '../view/_footer-bt.php';
?>