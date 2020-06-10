<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';

require "../../Class/EvoManager.php";
require "../../Class/EvoHelpers.php";

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function insertEvo($pdoEvo){
	$arrOutilsRespId=EvoHelpers::arrayOutilsRespId($pdoEvo);

	$req=$pdoEvo->prepare("INSERT INTO evos (id_from, id_resp, objet, evo, id_etat, date_dde, id_prio, id_plateforme, id_outils, id_module)
		VALUES (:id_from, :id_resp, :objet, :evo, :id_etat, :date_dde, :id_prio, :id_plateforme, :id_outils, :id_module)");
	$req->execute([
		':id_from'		=>$_SESSION['id_web_user'],
		':id_resp'		=>$arrOutilsRespId[$_POST['outils']],
		':objet'		=>$_POST['objet'],
		':evo'		=>$_POST['evo'],
		':id_etat'		=>0,
		':date_dde'		=>date('Y-m-d H:i:s'),
		':id_prio'		=>$_POST['prio'],
		':id_plateforme'		=>$_POST['pf'],
		':id_outils'		=>$_POST['outils'],
		':id_module'		=>empty($_POST['module'])? 0: $_POST['module']

	]);
	$err=$req->errorInfo();
	if(empty($err[2])){
		return false;
	}else{
		return $err[2];
	}

}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$evoMgr=new EvoManager($pdoEvo);
$listPF=$evoMgr->getListPlateforme();

if(isset($_POST['submit'])){
	$err=insertEvo($pdoEvo);
	if(!$err){
		$success[]="Votre demande a été envoyée et est en attente de validation";
	}

	else{
		$errors[]=$err;
	}
}





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
	<h1 class="text-main-blue py-5 ">Demande d'évo</h1>

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

				<div class="row">
					<div class="col-auto">
						<img src="../img/evo/code-ico.jpg" alt="code" class="polaroid">
					</div>
					<div class="col">
						<div class="row">
							<div class="col-4 text-main-blue">
								Sélectionnez une plateforme :
							</div>
							<div class="col">
								<?php foreach ($listPF as $key => $pf): ?>

									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" value="<?=$pf['id']?>" id="pf" name="pf">
										<label class="form-check-label pr-5" for="pf"><?=$pf['plateforme']?></label>
									</div>

								<?php endforeach ?>
							</div>
						</div>


						<div class="row ">
							<div class="col-md-4 mt-3 pt-2 text-main-blue">
								Sélectionnez un outil :
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="outils"></label>
									<select class="form-control" name="outils" id="outils">
										<option value="">Sélectionner</option>
									</select>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-md-4 mt-3 pt-2 text-main-blue">
								Sélectionnez un module :
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="module"></label>
									<select class="form-control" name="module" id="module">
										<option value="">Sélectionner</option>
									</select>
								</div>

							</div>
						</div>

						<div class="row mb-3">
							<div class="col-4 text-main-blue">
								Définissez une priorité :
							</div>
							<div class="col">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="1" id="urgent" name="prio">
									<label class="form-check-label pr-5 text-red" for="urgent"><b>urgent</b></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="2" id="normal" name="prio">
									<label class="form-check-label pr-5 text-main-blue" for="normal"><b>normal</b></label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" value="3" id="faible" name="prio">
									<label class="form-check-label pr-5 text-green" for="faible"><b>faible</b></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="objet" class="text-main-blue">Objet de votre demande</label>
							<input type="text" class="form-control" name="objet" id="objet">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="evo" class="text-main-blue">Votre demande :</label>
							<textarea name="evo" id="" cols="30" rows="5" class="form-control"></textarea>
						</div>
					</div>
				</div>

				<button class="btn btn-black" name="submit">Valider</button>
			</form>
		</div>
	</div>


	<!-- ./container -->
</div>
<script type="text/javascript">
	$(document).ready(function() {
// http://www.lisenme.com/dynamic-dependent-select-box-using-jquery-ajax-php/
//
$("input:radio[name='pf']").click(function () {
			// $("#outil").empty();


			var plateforme=$('input[name="pf"]:checked').val();
			console.log("plateforme" + plateforme);

			$.ajax({
				type:'POST',
				url:'ajax-get-outil.php',
				data:{id_plateforme:plateforme},
				success: function(html){
					$("#outils").html(html)
				}
			});
		});



$('#outils').on("change",function(){
	var outils=$('#outils').val();
	console.log("outils" + outils);
	$.ajax({
		type:'POST',
		url:'ajax-get-outil.php',
		data:{id_outils:outils},
		success: function(html){
			$("#module").html(html)
		}
	});
});

});


</script>
<?php
require '../view/_footer-bt.php';
?>