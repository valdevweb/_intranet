<?php
//----------------------------------
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";


function listMagFn($pdoBt){
	$req=$pdoBt->query("SELECT * FROM msg LEFT JOIN sca3 on msg.id_galec=sca3.galec GROUP BY sca3.mag ORDER BY sca3.mag");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$listMag=listMagFn($pdoBt);


function histoMagFn($pdoBt){
	$req=$pdoBt->prepare("SELECT msg.id as id_detail, date_msg, real_service, etat, msg FROM msg LEFT JOIN sca3 on msg.id_galec=sca3.galec INNER JOIN  services on msg.id_service=services.id WHERE msg.id_galec= :mag ORDER BY date_msg DESC");
	$req->execute(array(
		':mag'		=>$_POST['mag']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function histoMagServiceFn($pdoBt){
	$req=$pdoBt->prepare("SELECT msg.id as id_detail, date_msg, real_service, etat, msg FROM msg LEFT JOIN sca3 on msg.id_galec=sca3.galec INNER JOIN  services on msg.id_service=services.id WHERE msg.id_galec= :mag AND msg.id_service= :id_service ORDER BY date_msg DESC");
	$req->execute(array(
		':mag'		=>$_POST['mag'],
		':id_service'	=>$_SESSION['id_service']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}






//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------

include('../view/_head.php');
include('../view/_navbar.php');



if(isset($_POST['search']))
{
	if($d_exploit)
	{
			$histoMag=histoMagFn($pdoBt);
	}
	else
	{
		$histoMag=histoMagServiceFn($pdoBt);

	}
}



?>
<div class="container">

	<h1 class="blue-text text-darken-4">Historique des demandes par magasin</h1>

	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Sélectionner le magasin</h4>
			<hr>
			<br><br>
			<div class="col m4">
				<form method="post">
				<div class="form-group">
					<!-- <label for="mag">Sélectionner le magasin :</label> -->
					<select class="browser-default" id="mag" name="mag">
						<option value="">Sélectionner</option>
						<?php foreach ($listMag as $mag) : ?>
							<option value="<?= $mag['id_galec']?>"><?= $mag['mag'] ?></option>
						<?php endforeach ?>

					</select>
				</div>
				<p class="right-align"><button type="submit" id="search" class="btn btn-primary" name="search">Rechercher</button></p>
				<p>&nbsp;</p>
			</form>
			</div>
			<div class="col m8"></div>

		</div>
	</div>
	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="modalite-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Résultats</h4>
			<hr>
			<br><br>
			<table class="striped responsive-table">
				<tr>
					<th>DEMANDE</th>
					<th>DATE</th>
					<th>SERVICE</th>
					<th>ETAT</th>
					<th>DETAIL</th>
				</tr>

				<?php if(isset($histoMag)): ?>
				<?php foreach ($histoMag as $msg) : ?>
					<tr>
						<td><?=$msg['msg']?></td>
						<td><?= date('d-m-Y',strtotime($msg['date_msg']))?></td>
						<td><?= $msg['real_service']?></td>
						<td><?= $msg['etat']?></td>
						<td><a href="answer.php?msg=<?=$msg['id_detail']?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
					</tr>
				<?php endforeach ?>
				<?php else: ?>
					<tr>
						<td colspan="5">Aucun magasin sélectionné</td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
	</div>





</div>

<?php


?>





<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
include('../view/_footer.php');