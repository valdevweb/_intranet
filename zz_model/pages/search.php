<?php
function search($pdoSav)
{
$req=$pdoSav->prepare("SELECT ddes_mag.id as num_dde,DATE_FORMAT(date_msg,'%d/%m/%Y') as datefr, objet,name,sav, btlec.sca3.mag, btlec.sca3.galec,btlec.sca3.btlec   FROM ddes_mag  LEFT JOIN btlec.sca3 ON ddes_mag.galec=btlec.sca3.galec WHERE concat(btlec.sca3.mag,btlec.sca3.galec,btlec.sca3.btlec) LIKE :search AND etat<>'clos'");
$req->execute(array(
	':search' =>'%'.$_POST['search_strg'] .'%'
));
return $req->fetchAll(PDO::FETCH_ASSOC);
 // return $req->errorInfo();
}
// $search=search($pdoSav);

if(isset($_POST['search_form']))
{
	$allMsg=search($pdoSav);

}

else
{
	$allMsg=getMagMsg($pdoSav);

}

if(isset($_POST['clear_form'])){
	$_POST=[];
	$allMsg=getMagMsg($pdoSav);

}

 ?>


<!-- formulaire de recherche -->
	<div class="row mt-5">
		<div class="col-2"></div>
		<div class="col border shadow py-5">
			<p class="text-orange">Rechercher une demande magasin :</p>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="form-inline">
				<div class="form-group">
					<input class="form-control mr-5 pr-5" placeholder="nom de magasin, ville, panonceau galec" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
				</div>
				<button class="btn btn-primary mr-5" type="submit" id="" name="search_form"><i class="fas fa-search pr-2"></i>Rechercher</button>
				<button class="btn btn-blue" type="submit" id="" name="clear_form"><i class="fas fa-eraser pr-2"></i>Effacer</button>
			</form>
		</div>
		<div class="col-2"></div>
	</div>
	<!-- ./formulaire de recherche-->