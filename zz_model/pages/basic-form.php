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
// $descr="page pour réouvrir une demande";
// $page=basename(__file__);
// $action="consultation";
// $code=101;
// addRecord($pdoStat,$page,$action, $descr,$code);


//----------------------------------------------
// css dynamique
//----------------------------------------------
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile="../css/".$pageCss.".css";

//----------------------------------------------
//  		FUNCTIONS
//----------------------------------------------
function insert($pdoSav)
{
	$req=$pdoSav->prepare("INSERT INTO table() VALUES()");
	$req->execute(array(

	));
	return $pdoSav->lastInsertId();
	// return $req->errorInfo();
	// return	$req->rowCount();
}


function copyDossiersNonClos($pdoSav)
{
	$req=$pdoSav->prepare("INSERT INTO temp_relance_nonclos (`id`, `id_mail2`, `id_mail2reponse`, `id_uploadinfos`, `date_dde`, `login`, `id_web_dd`, `mail`, `dem_name`, `orgfile`, `date_rep`, `login_rep`, `id_web_rep`, `repfile`, `etat`, `date_dde_clean`, `date_rep_clean`, `id_dossier`) SELECT `id`, `id_mail2`, `id_mail2reponse`, `id_uploadinfos`, `date_dde`, `login`, `id_web_dd`, `mail`, `dem_name`, `orgfile`, `date_rep`, `login_rep`, `id_web_rep`, `repfile`, `etat`, `date_dde_clean`, `date_rep_clean`, `id_dossier` FROM temp_relance WHERE etat=0");
	$req->execute();
	$row=$req->rowCount();
	return	$row;

}


function delete($pdoSav){
	$req=$pdoSav->prepare("DELETE FROM table WHERE");
	$req->execute();
	$row=$req->rowCount();
	return	$row;
}

function select($pdoSav){
	$req=$pdoSav->prepare("SELECT fields FROM table WHERE ");
	$req->execute(array(
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return	$req->errorInfo();
}

function nbMagCentrale($pdoBt)
{
  $req=$pdoBt->prepare("SELECT count(galec) as nb,centrale FROM (SELECT DISTINCT salon_2019.galec, centrale FROM salon_2019 LEFT JOIN sca3 ON salon_2019.galec=sca3.galec WHERE salon_2019.galec !='') sousreq GROUP BY centrale");
  $req->execute();
  return $req->fetchAll(PDO::FETCH_ASSOC);

}




function update($pdoSav)
{
	$req=$pdoSav->prepare("UPDATE table_dest SET champ=valeur WHERE ");
	$req->execute();
	return	$req->rowCount();
}


function updateFromTable($pdoSav)
{
	$req=$pdoSav->prepare("UPDATE table_dest INNER JOIN table_info ON table_dest.champ=table_info.champ SET table_dest.champ2=table_info.champ2 WHERE ");
	$req->execute();
	$row=$req->rowCount();
	return	$row;
}


include('../view/_head.php');
include('../view/_navbar.php');
?>

<div class="container py-5">
	<!-- main title -->
	<div class="row">
		<div class="col">
			<h1 class="text-center underline-anim mt-5"></h1>
		</div>
	</div>
	<!-- ./main title -->
	<!-- form container -->
	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<!-- form -->
			 <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data" id="">
			 	<!-- input text -->
		 		<div class="form-group">
					<label for=""></label>
					<input class="form-control" placeholder="" name="" id="" type="text"  required="require" value="<?=isset($objet)? $objet: false?>">
				</div>
				<!-- ./input text -->
			 	<!-- textarea -->
			 	<div class="form-group">
			 		<label for=""></label>
			 		<textarea class="form-control" placeholder="" name="" id="" required="require"  ><?=isset($msg)? $msg: false?></textarea>
			 	</div>
			 	<!-- ./textarea  -->
			 	<!-- upload -->
			 	<div id="file-upload">
			 		<fieldset>
			 			<p class="pt-2">Pièces jointes :</p>
			 			<div class="form-group">
			 				<p><input type="file" name="file_1" class='form-control-file'></p>
			 				<p id="p-add-more"><a  id="add_more" href="#file-upload"><i class="fa fa-plus-circle pr-3" aria-hidden="true"></i>Ajouter un fichier</a></p>
			 			</div>
			 		</fieldset>
			 	</div>
			 	<!-- ./upload -->
			 	<!-- checkbox -->
			 	<div class="form-group form-check pt-3">
			 		<input type="checkbox" class="form-check-input"  name="" id="" checked="checked">
			 		<label class="form-check-label" for="">Cloturer</label>
			 	</div>
			 	<!-- ./checkbox -->
			 	<!-- submit -->
			 	<p class="pt-5 text-right"><button class="btn btn-primary" type="submit" id="" name="">Envoyer</button></p>
			 	<!-- ./submit -->
			 </form>
			 <!-- ./form -->
		</div>
		<div class="col-lg-1 col-xxl-2"></div>

	</div>
	 <!--./form container  -->




	<!-- fin container -->
</div>


<?php
// include('../view/_flash.php');



include('../view/_footer.php');



 ?>