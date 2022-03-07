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
require '../../Class/achats/CdesDao.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/achats/CdesRelancesDao.php';

require '../../Class/FournisseursHelpers.php';
require '../../Class/UserDao.php';
require '../../Class/FouDao.php';
require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoFou=$db->getPdo('fournisseurs');
$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);
$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$cdesRelancesDao=new CdesRelancesDao($pdoDAchat);

$userDao= new UserDao($pdoUser);
$fouDao=new FouDao($pdoFou);

$listGt=FournisseursHelpers::getGts($pdoFou, "GT","id");

$userGts=$userDao->getUserGts($_SESSION['id_web_user']);

$paramService=' id_service= 1 OR id_service=2 ';

$userAchat=$userDao->getUserParam($paramService);

foreach ($userAchat as $user) {
	$userGts=$userDao->getUserGts($user['id_web_user']);

	if(!empty($userGts)){
		$param='AND (gt='.join(' OR gt=',$userGts). ')';
		$notifs=$cdesDao->getDateLivToday($param);
	}else{
		$notifs="";
	}
	if(isset($notifs) && !empty($notifs)){
		$ligne="";
		foreach ($notifs as $key => $prod) {
			$ligne.="<tr><td>".$prod['gt']."</td><td>" .$prod['fournisseur']."</td><td>" . $prod['article'] ."</td><td>" . $prod['dossier'] ."</td><td>" . $prod['ref'] ."</td><td>" . $prod['libelle_art'] ."</td><td>" . date('d-m-Y',strtotime($prod['date_cde'])) ."</td><td>" . $prod['qte_cde'] ."</td><td>"  . $prod['cond_carton']  ."</td><td>" . $prod['qte_uv_cde'] ."</td></tr>";
		}
			// echo "<pre>";
			// print_r($notifs);
			// echo '</pre>';

		$dest=[];
		if(VERSION=="_"){
			$dest=[MYMAIL];
			$strMail=$user['email'];
			$hidden=[];
		}else{
			$dest[]=$user['email'];
			$strMail="";
		}
		$transport = (new Swift_SmtpTransport(SMTP_ADDRESS, 25));
		$mailer = new Swift_Mailer($transport);

		$htmlMail = file_get_contents('../mail/achats-notif-perm.html');
		$htmlMail=str_replace('{LIGNE}',$ligne,$htmlMail);
		$htmlMail=str_replace('{DEST}',$strMail,$htmlMail);
		$subject='Portail BTLec - relances commmandes permanent date de livraison prévisionnelle au '.date('d-m-Y');
		$message = (new Swift_Message($subject))
		->setBody($htmlMail, 'text/html')
		->setFrom(EMAIL_NEPASREPONDRE)
		->setTo($dest);

		if (!$mailer->send($message, $failures)){
			print_r($failures);
		}else{
			$success[]="mail envoyé avec succés";
		}


	}

}


if (isset($_POST['filter_gt'])) {
	if(empty($_POST['gt'][0])){
		$errors[]="Vous n'avez pas sélectionné de GT";

	}else{
		$param='AND (';
		$param.=join(' OR ',array_map(
			function($value){return "gt='".$value."'";},
			$_POST['gt']));
		$param.=' )';

	}


}elseif(!empty($userGts)){
	$param='AND (gt='.join(' OR gt=',$userGts). ')';


}
	// echo "<pre>";
	// print_r($notifs);
	// echo '</pre>';


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Notif Permanent</h1>
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


	<div class="row mb-5">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col-lg-4"></div>

					<div class="col text-center">
						<div class="text-main-blue">Sélectionnez un ou plusieurs GT :</div>

					</div>
					<div class="col-lg-4"></div>

				</div>
				<div class="row">
					<div class="col-lg-4"></div>
					<div class="col cols-four border rounded">
						<?php foreach ($listGt as $keyGt => $value): ?>

							<div class="form-check">

								<input class="form-check-input" type="checkbox" value="<?=$keyGt?>" id="<?=$listGt[$keyGt]?>" name="gt[]">
								<label class="form-check-label" for="<?=$listGt[$keyGt]?>"><?=ucfirst(strtolower($listGt[$keyGt]))?></label>

							</div>

						<?php endforeach ?>
					</div>
					<div class="col-lg-4"></div>

				</div>

				<div class="row mb-3">
					<div class="col-lg-4"></div>
					<div class="col text-right">
						<button class="btn btn-primary" name="filter_gt">Valider</button>
					</div>
					<div class="col-lg-4"></div>

				</div>
			</form>
		</div>
	</div>



	<div class="row">
		<div class="col">
			<?php if (!empty($notifs)): ?>
				<table class="table table-sm">
					<thead class="thead-dark">
						<tr>
							<th>GT</th>
							<th>Fournisseur</th>
							<th>Article</th>
							<th>Dossier</th>
							<th>Référence</th>
							<th>Libelle</th>
							<th>date cde</th>
							<th>Qte cde</th>
							<th>PCB</th>
							<th>Qte UV</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($notifs as $key => $r): ?>
							<tr>
								<td><?=$r['gt']?></td>
								<td><?=$r['fournisseur']?></td>
								<td><?=$r['article']?></td>
								<td><?=$r['dossier']?></td>
								<td><?=$r['ref']?></td>
								<td><?=$r['libelle_art']?></td>
								<td><?=date('d-m-Y',strtotime($r['date_cde']))?></td>
								<td><?=$r['qte_cde']?></td>
								<td><?=$r['cond_carton']?></td>
								<td><?=$r['qte_uv_cde']?></td>
							</tr>
						<?php endforeach ?>

					</tbody>
				</table>
			<?php endif ?>

		</div>
	</div>

</div>


<?php
require '../view/_footer-bt.php';
?>