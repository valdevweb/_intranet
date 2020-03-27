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
require_once '../../vendor/autoload.php';
require_once '../../Class/MagDbHelper.php';
require_once '../../Class/Mag.php';
//---------------------------------------
//	STRUCTURE PAGE HTML
//---------------------------------------
/*





 */


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




// $searchResults=false;
function convertArray($data, $field,$separator){
	if(!empty($data)){
		$rValue='';
		foreach ($data as $key => $value) {
			$rValue.=$value[$field].$separator;
		}
		return $rValue;
	}
	return '';
}

function updateSca($pdoMag){
	$req=$pdoMag->prepare("UPDATE sca3 SET galec_sca= :galec_sca, deno_sca= :deno_sca, ad1_sca= :ad1_sca, ad2_sca= :ad2_sca, ad3= :ad3_sca, cp_sca= :cp_sca, ville_sca= :ville_sca, tel_sca= :tel_sca, fax_sca= :fax_sca, adherent_sca= :adh_sca, centrale_sca= :centrale_sca, centrale_doris= :centrale_doris, centrale_smiley= :centrale_smiley, surface_sca= :surface_sca, sorti= :sorti, date_ouverture= :date_ouverture, date_adhesion= :date_adhesion, date_fermeture= :date_fermeture, date_resiliation= :date_resiliation, date_sortie= :date_sortie, pole_sav_sca= :pole_sav_sca, nom_gesap= :gesap, affilie= :affilie, date_update= :date_update WHERE btlec_sca= :btlec_sca");
	$req->execute([
		':btlec_sca'		=>$_GET['id'],
		':galec_sca'		=>$_POST['galec_sca'],
		':deno_sca'		=>$_POST['deno_sca'],
		':ad1_sca'		=>$_POST['ad1_sca'],
		':ad2_sca'		=>$_POST['ad2_sca'],
		':ad3_sca'		=>$_POST['ad3_sca'],
		':cp_sca'		=>$_POST['cp_sca'],
		':ville_sca'		=>$_POST['ville_sca'],
		':tel_sca'		=>$_POST['tel_sca'],
		':fax_sca'		=>$_POST['fax_sca'],
		':adh_sca'		=>$_POST['adh_sca'],
		':centrale_sca'		=>(empty($_POST['centrale_sca']))? NULL:$_POST['centrale_sca'] ,
		':centrale_doris'		=>(empty($_POST['centrale_doris']))? NULL :$_POST['centrale_doris'],
		':centrale_smiley'		=>(empty($_POST['centrale_smiley']))? NULL :$_POST['centrale_smiley'],
		':surface_sca'		=>$_POST['surface_sca'],
		':sorti'		=>$_POST['sorti'],
		':date_ouverture'		=>(empty($_POST['date_ouverture']))? NULL:(new DateTime($_POST['date_ouverture']))->format('Y-m-d'),
		':date_adhesion'		=>(empty($_POST['date_adhesion']))? NULL:(new DateTime($_POST['date_adhesion']))->format('Y-m-d'),
		':date_fermeture'		=>(empty($_POST['date_fermeture']))? NULL:(new DateTime($_POST['date_fermeture']))->format('Y-m-d'),
		':date_resiliation'		=>(empty($_POST['date_resiliation']))? NULL:(new DateTime($_POST['date_resiliation']))->format('Y-m-d'),
		':date_sortie'		=>(empty($_POST['date_sortie']))? NULL:(new DateTime($_POST['date_sortie']))->format('Y-m-d'),
		':pole_sav_sca'		=>(empty($_POST['pole_sav_sca']))? NULL:$_POST['pole_sav_sca'],
		':gesap'		=>$_POST['gesap'],
		':affilie'		=>$_POST['affilie'],
		':date_update'	=>date('Y-m-d H:i:s')
	]);
	return $req->rowCount();
}


if(isset($_POST['clear_form'])){
	$_POST=[];
	header("Location: ".$_SERVER['PHP_SELF']);

}
if (isset($_GET['id'])){
	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_GET['id']);
	$histo= $magDbHelper->getHisto($mag->getGalec());
	$listCentralesSca=$magDbHelper->getDistinctCentraleSca();
	$webusers=$magDbHelper->getWebUser($mag->getGalec());
	$centreRei=$magDbHelper->centreReiToString($mag->getCentreRei());




	// ld
	$ldRbt=$magDbHelper-> getMagLd($mag->getGalec(),'-RBT');
	$ldRbtName=(!empty($ldRbt))? $ldRbt[0]['ld_full']:  "Liste RBT :";
	$ldRbt=convertArray($ldRbt,'email','<br>');
	$ldRbt=(!empty($ldRbt))? $ldRbt:  "Aucune adresse RBT";
	$ldDir=$magDbHelper-> getMagLd($mag->getGalec(),'-DIR');
	$ldDirName=(!empty($ldDir))? $ldDir[0]['ld_full']:  "Liste directeur :";
	$ldDir=convertArray($ldDir,'email','<br>');
	$ldDir=(!empty($ldDir))? $ldDir : "Aucune adresse directeur";

	$ldAdh=$magDbHelper-> getMagLd($mag->getGalec(),'-ADH');
	$ldAdhName=(!empty($ldAdh))? $ldAdh[0]['ld_full']:  "Liste adhérent :";
	$ldAdh=convertArray($ldAdh,'email','<br>');
	$ldAdh=(!empty($ldAdh))? $ldDir: "Aucune adresse adhérent";



	if(!empty($mag->getCentrale())){
		// $centraleGessica=$mag->getCentrale();
		$centraleGessica=$magDbHelper->centraleToString($mag->getCentrale());
	}else{
		$centraleGessica="Pas de centrale renseignée";
	}


	$ad2=!empty($mag->getAd2()) ? $mag->getAd2().'<br>' :'';


}

if(isset($_GET['id'])){
	$magDbHelper=new MagDbHelper($pdoMag);
	$mag=$magDbHelper->getMagBt($_GET['id']);

}



if(isset($_GET['success'])){
	$arrSuccess=[
		'maj'=>'Magasin mis à jour avec succès',
	];
	$success[]=$arrSuccess[$_GET['success']];
}
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<?php if (!isset($mag)): ?>
		<div class="row  pb-3 ">
			<div class="col">
				<h1 class="text-main-blue pt-5">F</span>iche magasin</h1>
			</div>
			<?php
			include('search-form.php')
			?>
			<!-- <div class="col-lg-1"></div> -->
		</div>
		<?php else: ?>
			<div class="row  pb-3 ">
				<div class="col">
					<h1 class="text-main-blue pt-5" data-id="<?= $mag->getId()?>">
						<?= (isset($mag))? 'Leclerc '.$mag->getDeno(): "Fiche magasin" ?>
					</h1>
					<h5 class="yanone">Code BTLec : <span class="text-orange" ><?= $mag->getId() .'</span><span class="pl-5">Panonceau Galec : <span class="text-orange">'.$mag->getGalec().'</span>'?></h5>

				</div>
				<?php
				include('search-form.php')
				?>
				<!-- <div class="col-lg-1"></div> -->
			</div>
			<?php
			// include('fiche-mag-commun.php');
			if($d_exploit){
				include('fiche-mag-exploit.php');
			}
			echo "<pre>";
			print_r($_POST);
			echo '</pre>';

			?>

		<?php endif ?>


	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#search_term').keyup(function(){
				var path = window.location.pathname;
				var page = path.split("/").pop();

				var query = $(this).val()+"#"+page;
				if(query != '')
				{
					$.ajax({
						url:"ajax-search-mag.php",
						method:"POST",
						data:{query:query},
						success:function(data)
						{
							$('#magList').fadeIn();
							$('#magList').html(data);
						}
					});
				}
			});
			$(document).on('click', 'li', function(){
				$('#search_term').val($(this).text());
				$('#magList').fadeOut();
			});
			// https://github.com/igorescobar/jQuery-Mask-Plugin
			$('#date_ouverture').mask('00/00/0000');
			$('#date_fermeture').mask('00/00/0000');
			$('#date_adhesion').mask('00/00/0000');
			$('#date_resiliation').mask('00/00/0000');
			$('#date_sortie').mask('00/00/0000');
			$('#tel_sca').mask('00 00 00 00 00');





		});

		function submitForm(inputName){

			console.log("clic");
			var form_data = $('#form-mag').serialize();
			var page_y = $( document ).scrollTop();
			var id=$('h1').data("id");
				console.log( "fiche-mag-copy.php?field="+inputName + "&page_y="+page_y+"&id="+id)

			// console.log(form_data);
			$.ajax({
				url : "fiche-mag-copy.php?id="+id+"&field="+inputName + "&page_y="+page_y,
				type: 'post',
				data : form_data,

			});
		}


		// 	function refreshPage () {
		// 		var page_y = $( document ).scrollTop();
		// 		window.location.href = window.location.href + '?page_y=' + page_y ;
		// 		console.log("hello");
		// 		console.log(page_y);
		// 	// window.location.href = window.location.href + '?page_y=' + page_y + '&';
		// }

		// $('.fa-sign-out-alt').on('click',  function(e){
		// 	console.log("hello");

			// $('.fa-sign-out-alt').on('click',  function(e){
				// $('form#form-mag').on('submit', function (e) {

				//  	// $('form#form-mag').submit;
				//  	e.preventDefault();


				//  	var form_data = $('#form-mag').serialize();
				//  	console.log(form_data);
				//  	$.ajax({
				//  		url : "fiche-mag-copy.php",
				//  		type: 'post',
				//  		data : form_data
				//  	}).done(function(response){
				//  		$("#res").html(response);
				//  	});


				//  });

		// function refreshPage () {
		// 	var page_y = $( document ).scrollTop();
		// 	window.location.href = window.location.href + '?page_y=' + page_y +'&';
		// 	// window.location.href = window.location.href + '?page_y=' + page_y '&';
		// }
		// window.onload = function () {
		// 	setTimeout(refreshPage, 35000);
		// 	if ( window.location.href.indexOf('page_y') != -1 ) {
		// 		var match = window.location.href.split('?')[1].split("&")[0].split("=");
		// 		$('html, body').scrollTop( match[1] );
		// 	}
		// }



	</script>

	<?php
	require '../view/_footer-bt.php';
	?>