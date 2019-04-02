<?php

 // require('../../config/pdo_connect.php');
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
//			FONCTION
//------------------------------------------------------

// info produit
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.article,details.tarif,details.qte_cde, details.qte_litige,details.dossier_gessica,details.descr,details.fournisseur,details.pj,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec,
		etat.etat
		FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN etat ON etat_dossier=etat.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}



function getFirstDial($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM `dial` WHERE id_dossier=:id ORDER BY id ASC LIMIT 1");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

	for ($i=0; $i < count($filelist); $i++)
	{
		if($filelist[$i] !="")
		{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'" class="link-main-blue"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

		}
	}
	return $rValue;
	// return $filelist;
}

function getInfos($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT transporteur.transporteur, affrete.affrete, transit.transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, DATE_FORMAT(date_prepa,'%d-%m-%Y') as dateprepa, ctrl_ok FROM dossiers
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		WHERE  dossiers.id= :id ");

	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$infos=getInfos($pdoLitige);


function getAnalyse($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT gt, imputation, typo, etat, analyse, conclusion FROM dossiers
		LEFT JOIN gt ON id_gt=gt.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}
$analyse=getAnalyse($pdoLitige);

function getAction($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT libelle, action.id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr, concat(prenom, ' ', nom) as name FROM action LEFT JOIN btlec.btlec ON action.id_web_user=btlec.btlec.id_webuser WHERE action.id_dossier= :id ORDER BY date_action");
	$req->execute(array(
		':id'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$actionList=getAction($pdoLitige);
function getDialog($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT id_dossier,DATE_FORMAT(date_saisie, '%d-%m-%Y') as dateFr,msg,id_web_user,filename,mag FROM dial WHERE id_dossier= :id ORDER BY date_saisie");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}
$dials=getDialog($pdoLitige);
function getBtName($pdoBt, $idwebuser)
{
	$req=$pdoBt->prepare("SELECT CONCAT (prenom, ' ', nom) as name FROM btlec WHERE id_webuser= :id_web_user");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getMagName($pdoUser, $idwebuser)
{
	$req=$pdoUser->prepare("SELECT btlec.sca3.mag FROM users LEFT JOIN btlec.sca3 ON users.galec=btlec.sca3.galec WHERE users.id= :id_web_user ");
	$req->execute(array(
		':id_web_user'	=>$idwebuser
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}


$coutTotal=$infos['mt_transp']+$infos['mt_assur']+$infos['mt_fourn']+$infos['mt_mag'];
if($infos['ctrl_ok']==0)
{
	$ctrl="non contrôlé";
}
else{
	$ctrl="fait";
}

if($coutTotal!=0){
	$coutTotal=number_format((float)$coutTotal,2,'.','');
}


$fLitige=getLitige($pdoLitige);


$firstDial=getFirstDial($pdoLitige);


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
	<div class="row pt-5 pb-3 align-items-center">

		<div class="col">
			<h1 class="text-main-blue ">
				Dossier N° <?= $fLitige[0]['dossier']?>
			</h1>
		</div>

		<div class="col">
			<p class="text-right text-main-blue bigger my-auto">
				déclaration du <?=$fLitige[0]['datecrea'] ?>
			</p>
		</div>
		<div class="col-auto">
			<?php
			if($fLitige[0]['vingtquatre']==1)
			{
				$vingtquatre='<img src="../img/litiges/2448_40.png">';

			}
			else
			{
				$vingtquatre="";
			}
			echo $vingtquatre;
			?>
		</div>
	</div>

	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- info mag -->
	<div class="row mb-3">
		<div class="col-lg-2"></div>
		<div class="col">
			<div class="row bg-alert-primary border light-shadow no-gutters">
				<div class="col-auto my-auto">
					<div class="align-middle"><img src="../img/litiges/mag-sm.jpg"></div>
				</div>
				<div class="col pl-5">
					<div class="row">
						<div class="col">
							<h4 class="khand pt-2"><?= $fLitige[0]['mag'] .' - '.$fLitige[0]['btlec'].' ('.$fLitige[0]['galec'].')' ?></h4>
						</div>
						<div class="col">
							<h4 class="khand pt-2 text-right pr-3"><?=$fLitige[0]['centrale']?></h4>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<span class="heavy">Interlocuteur : </span><?= $fLitige[0]['nom'] ?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<span class="heavy">Commentaire : </span><?= $firstDial['msg'] ?>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="col-lg-2"></div>
	</div>
	<div class="bg-separation"></div>

	<div class="row mt-3">
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="khand text-main-blue pb-3">Intervenir sur le dossier :</h5>
				</div>
			</div>
			<div class="row">
				<!-- <div class="col"></div> -->
				<div class="col-auto">
					<p class="text-right"><a href="bt-analyse.php?id=<?=$_GET['id']?>" class="btn btn-primary"><i class="fas fa-chart-area pr-3"></i>Analyser litige</a></p>
				</div>

				<div class="col-auto">
					<p class="text-right"><a href="bt-action-add.php?id=<?=$_GET['id']?>" class="btn btn-red"><i class="fas fa-plus-square pr-3"></i>Ajouter une action</a></p>
				</div>
				<div class="col-auto">
					<p class="text-right"><a href="bt-contact.php?id=<?=$_GET['id']?>" class="btn btn-kaki"><i class="fas fa-comment pr-3"></i>Contacter le magasin</a></p>
				</div>
				<div class="col-auto">
					<p class="text-right"><a href="bt-info-litige.php?id=<?=$_GET['id']?>" class="btn btn-yellow"><i class="fas fa-highlighter pr-3"></i>Ajouter des informations</a></p>
				</div>
				<div class="col-auto">
					<p class="text-right"><a href="bt-generate-fiche.php?id=<?=$_GET['id']?>" class="btn btn-black"><i class="fas fa-print pr-3"></i>Imprimer</a></p>
				</div>

				<!-- <div class="col"></div> -->
			</div>
		</div>

	</div>
	<div class="bg-separation"></div>
	<!-- infos produit -->
	<div class="row mt-3 mb-3">
		<div class="col">
			<div class="row">
				<div class="col">
					<h5 class="khand text-main-blue pb-3">Produit(s) :</h5>

					<table class="table light-shadow">
						<thead class="thead-dark">
							<tr>
								<th class="align-top">Article</th>
								<th class="align-top">Dossier</th>
								<th class="align-top">Désignation</th>
								<th class="align-top">Fournisseur</th>
								<th class="align-top">Réclamation</th>
								<th class="align-top">Quantité <br>litige</th>
								<!-- <th class="align-top text-right">Date déclaration</th> -->
								<th class="align-top text-right">Valo</th>
								<th class="align-top">Pièces jointes</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sumValo=0;
							foreach ($fLitige as $prod)
							{
								$valo=round(($prod['tarif'] / $prod['qte_cde'])*$prod['qte_litige'],2);
								$pj='';

								if($prod['pj']!='')
								{
									$pj=createFileLink($prod['pj']);
										// $pj="jkoezfaji"	;

								}
								echo '<tr>';
								echo'<td>'.$prod['article'].'</td>';
								echo'<td>'.$prod['dossier_gessica'].'</td>';
								echo'<td>'.$prod['descr'].'</td>';
								echo'<td>'.$prod['fournisseur'].'</td>';
								echo'<td>'.$prod['reclamation'].'</td>';
								echo'<td class="text-right">'.$prod['qte_litige'].'</td>';
								echo'<td class="text-right">'.number_format((float)$valo,2,'.','').'&euro;</td>';
								echo'<td class="text-right">'.$pj.'</td>';
								echo '</tr>';
								if($prod['inversion'] !="")
								{
									$valoInv=round( $prod['qte_cde']*$prod['inv_tarif'],2);
									echo '<tr class="text-center bg-reddish text-white"><td colspan="8">Produit reçu à la place de la référence ci-dessus :</td></tr>';
									echo '<tr>';
									echo'<td>'.$prod['inv_article'].'</td>';
									echo'<td></td>';
									echo'<td>'.$prod['inv_descr'].'</td>';
									echo'<td>'.$prod['inv_fournisseur'].'</td>';
									echo'<td></td>';
									echo'<td class="text-right">'.$prod['qte_litige'].'</td>';
									echo'<td class="text-right">'.number_format((float)$valoInv,2,'.','').'&euro;</td>';
									echo'<td class="text-right"></td>';
									echo '</tr>';
									$sumValo=$sumValo+$valo-$valoInv;
								}
								else
								{
									$sumValo=$sumValo + $valo;

								}
							}
							?>
						</tbody>
					</table>
					<p class="text-right heavy bigger mb-3 text-main-blue pr-3">Valorisation magasin : <?= number_format((float)$sumValo,2,'.','')?> &euro;</p>
				</div>
			</div>

		</div>
	</div>
	<div class="bg-separation"></div>

	<!-- analyse service, imputation, typo, etat, analyse, conclusion -->
	<div class="row mt-3">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Analyse :</h5>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col">
			<!-- start table -->
			<table class="table light-shadow">
				<thead class="thead-dark">
					<tr>
						<th>Nature</th>
						<th>Imputation</th>
						<th>Typologie</th>
						<th>Etat</th>
						<th>Analyse</th>
						<th>Réponse</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?=$analyse['gt']?></td>
						<td><?=$analyse['imputation']?></td>
						<td><?=$analyse['typo']?></td>
						<td><?=$analyse['etat']?></td>
						<td><?=$analyse['analyse']?></td>
						<td><?=$analyse['conclusion']?></td>
					</tr>
				</tbody>
			</table>
			<!-- ./table -->
		</div>
	</div>

	<div class="bg-separation"></div>

	<!-- infos -->
	<div class="row mt-3">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Informations :</h5>

		</div>
	</div>


	<div class="row">
		<div class="col bg-alert mr-3 border-kaki">
			<div class="row">
				<div class="col text-center"><img src="../img/litiges/ico-entrepot.png"></div>

			</div>
			<div class="row">
				<div class="col-5 text-kaki">Préparateur :</div>
				<div class="col "><?=$infos['fullprepa']?></div>
			</div>
			<div class="row">
				<div class="col-5 text-kaki">Date prépa :</div>
				<div class="col "><?=$infos['dateprepa']?></div>
			</div>
			<div class="row">
				<div class="col-5 text-kaki">Contrôleur :</div>
				<div class="col "><?=$infos['fullctrl']?></div>
			</div>
			<div class="row">
				<div class="col-5 text-kaki">Chargeur :</div>
				<div class="col "><?=$infos['fullchg']?></div>
			</div>
			<div class="row">
				<div class="col-5 text-kaki">Contrôle stock : </div>
				<div class="col "><?=$ctrl?></div>
			</div>
		</div>
		<div class="col bg-alert mr-3  border-yellow">
			<div class="row">
				<div class="col text-center"><img src="../img/litiges/ico-transp.png"></div>
			</div>
			<div class="row">
				<div class="col text-yellow">Transporteur :</div>
				<div class="col"><?=$infos['transporteur']?></div>
			</div>
			<div class="row">
				<div class="col text-yellow">Affreteur :</div>
				<div class="col"><?=$infos['affrete']?></div>
			</div>
			<div class="row">
				<div class="col text-yellow">Transité par :</div>
				<div class="col"><?=$infos['transit']?></div>
			</div>
		</div>
		<div class="col bg-alert border-reddish">
			<div class="row">
				<div class="col text-center"><img src="../img/litiges/ico-fact.png"></div>

			</div>
			<div class="row">
				<div class="col-8 text-red">Réglement transporteur :</div>
				<div class="col text-right"><?=number_format((float)$infos['mt_transp'],2,'.','')?>&euro;</div>
			</div>
			<div class="row">
				<div class="col-8 text-red">Réglement assurance :</div>
				<div class="col text-right"><?= number_format((float)$infos['mt_assur'],2,'.','')?>&euro;</div>
			</div>
			<div class="row">
				<div class="col-8 text-red">Réglement fournisseur :</div>
				<div class="col text-right"><?= number_format((float)$infos['mt_fourn'],2,'.','')?>&euro;</div>
			</div>
			<div class="row">
				<div class="col-8 text-red">Avoir magasin :</div>
				<div class="col text-right"><?= number_format((float)$infos['mt_mag'],2,'.','')?>&euro;</div>
			</div>
			<div class="row">
				<div class="col-8 text-red">Coût du litige :</div>
				<div class="col text-right"><?= number_format((float)$coutTotal,2,'.','') ?>&euro;</div>
			</div>

		</div>

	</div>
	<div class="bg-separation"></div>

	<div class="row mt-3">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Actions :</h5>

		</div>
	</div>
	<div class="row">
		<div class="col">
			<table class="table light-shadow">
				<thead class="thead-dark">
					<tr>
						<th>date</th>
						<th>Par</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(isset($actionList) && count($actionList)>0)
					{
						foreach ($actionList as $action)
						{

							echo '<tr>';
							echo'<td>'.$action['dateFr'].'</td>';
							echo'<td>'.$action['name'].'</td>';

							echo'<td>'.$action['libelle'].'</td>';
							echo '</tr>';
						}

					}
					else
					{
						echo '<tr><td colspan="3">Aucune Action</td></tr>';
					}

					?>

				</tbody>
			</table>
		</div>

	</div>
	<div class="bg-separation"></div>

	<div class="row mt-3">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Echange avec le magasin</h5>

		</div>
	</div>

	<div class="row">
		<div class="col">
					<table class="table light-shadow">
						<thead class="thead-dark">
							<tr>
								<th>Date</th>
								<th>Interlocuteur</th>
								<th>Message</th>
								<th>PJ</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(isset($dials) && count($dials)>0)
							{
								foreach ($dials as $dial)
								{
									if(!empty($dial['msg']))
									{
										if($dial['mag']==1)
										{
											$name=getMagName($pdoUser, $dial['id_web_user']);
											$name=$name['mag'];
											$type='bg-kaki-light';

										}
										else
										{
											$name=getBtName($pdoBt, $dial['id_web_user']);
											$name=$name['name'];
											$type='bg-alert-primary';
										}
										if($dial['filename']!='')
										{
											$pj=createFileLink($dial['filename']);
										}
										else
										{
											$pj='';
										}
										echo '<tr class="'.$type.'">';
										echo '<td>'.$dial['dateFr'].'</td>';
										echo '<td>'.$name.'</td>';
										echo '<td>'.$dial['msg'].'</td>';
										echo '<td>'.$pj.'</td>';
										echo '</tr>';
									}
								}

							}
							?>
						</tbody>
					</table>

		</div>
	</div>







	<!-- start row -->
	<div class="row mt-3 mb-5">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<p>&nbsp;</p>
			<p class="text-center"><a href="bt-litige-encours.php" class="btn btn-primary">Retour</a></p>
			<p>&nbsp;</p>

		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<!-- ./row -->



	<!-- ./row -->
	<!-- ./row -->



</div>
<?php

require '../view/_footer-bt.php';

?>