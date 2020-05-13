<?php
//----------------------------------
require '../../config/autoload.php';
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
//	css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require('../../Class/MsgManager.php');

$msgManager=new MsgManager();


function search($pdoMag){
	$req=$pdoMag->prepare("SELECT * FROM mag WHERE concat(deno, id, galec, ville) LIKE :search ORDER BY deno");
	$req->execute([
		':search' =>'%'.$_POST['search_strg'] .'%'
	]);
	$datas=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($datas)){
		return false;
	}
	return $datas;
}

function histoMagFn($pdoBt){
	$req=$pdoBt->prepare("SELECT msg.id as id_detail, date_msg, real_service, etat, msg, objet,deno FROM msg LEFT JOIN web_users.mag on msg.id_galec=web_users.mag.galec LEFT JOIN  services on msg.id_service=services.id WHERE msg.id_galec= :mag ORDER BY date_msg DESC");
	$req->execute(array(
		':mag'		=>$_GET['galec']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function histoMagServiceFn($pdoBt){
	$req=$pdoBt->prepare("SELECT msg.id as id_detail, date_msg, real_service, etat, msg,objet FROM msg LEFT JOIN sca3 on msg.id_galec=sca3.galec INNER JOIN  services on msg.id_service=services.id WHERE msg.id_galec= :mag AND msg.id_service= :id_service ORDER BY date_msg DESC");
	$req->execute(array(
		':mag'		=>$_POST['mag'],
		':id_service'	=>$_SESSION['id_service']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function getMyService($pdoBt){
	$req=$pdoBt->prepare("SELECT full_name FROM services WHERE id= :id_service ");
	$req->execute(array(
		':id_service'	=>$_SESSION['id_service']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

if(isset($_GET['galec'])){

	$histoMag=$msgManager->getListDemandeByGalec($pdoBt,$_GET['galec']);


}

if(isset($_POST['search'])){
	$magList=search($pdoMag);


}


include('../view/_head-bt.php');
include('../view/_navbar.php');

?>
<div class="container">
	<h1 class="text-main-blue py-5 ">Historique des demandes d'un magasin</h1>

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
		<div class="col pb-5">
			<p>Chercher un magasin :</p>

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" class="form-inline">
				<div class="form-group">
					<input class="form-control mr-5 pr-5" placeholder="deno, ville, panonceau galec, code btlec" name="search_strg" id="" type="text"  value="<?=isset($search_strg)? $search_strg: false?>">
				</div>
				<button class="btn btn-primary mr-5" type="submit" id="" name="search"><i class="fas fa-search pr-2"></i>Rechercher</button>
			</form>
		</div>
	</div>

	<?php if (isset($magList)): ?>
		<div class="row">
			<div class="col">Les résultats de votre recherche : <?= isset($_POST['search_strg'])?$_POST['search_strg']:''?></div>
		</div>
		<div class="row">
			<div class="col">
				<ul>
					<?php foreach ($magList as $mag): ?>
						<li><a href="search.php?galec=<?=$mag['galec']?>"><?=$mag['galec'].'/'.$mag['id'] .' - '.$mag['deno'] .' ('.$mag['ville']?>)</a></li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>

	<?php endif ?>

	<?php if (isset($histoMag) && !empty($histoMag)): ?>

	<div class="row">
		<div class="col">
			<div class="int-padding text-center pb-3 text-main-blue">
				<h5 class="d-inline">Demandes du magasin de <?=$histoMag[0]['deno']?></h5>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th>Objet de la demande</th>
						<th>Date</th>
						<th>Service</th>
						<th>état</th>
						<th>afficher</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($histoMag as $msg) : ?>
						<tr>
							<td><?=$msg['objet']?></td>
							<td><?= date('d-m-Y',strtotime($msg['date_msg']))?></td>
							<td><?= $msg['real_service']?></td>
							<td><?= $msg['etat']?></td>
							<td><a href="answer.php?msg=<?=$msg['idMsg']?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>

	</div>
	<?php elseif(isset($histoMag) && empty($histoMag)): ?>
	<div class="row">
		<div class="col">
			<div class="alert alert-info">Ce magasin n'a fait aucune demande</div>
		</div>
	</div>


<?php endif ?>






</div>
<?php require '../view/_footer-bt.php'; ?>