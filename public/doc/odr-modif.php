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
require '../../Class/OdrDao.php';
require '../../Class/FormHelpers.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');



$odrDao=new OdrDao($pdoDAchat);

$listOdr=$odrDao->getOdrEncours();
$thisOdr="";

if(isset($_POST['modif_odr'])){

	if(isset($_FILES['odr_files']['tmp_name'][0]) && !empty($_FILES['odr_files']['tmp_name'][0])){

		for ($i=0; $i <count($_FILES['odr_files']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['odr_files']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;

			$uploaded=move_uploaded_file($_FILES['odr_files']['tmp_name'][$i],DIR_UPLOAD.'odr\\'.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, impossible de l'uploader vers le serveur";
			}else{
				$odrFilenames[]=$filename;
			}
		}
	}

	if(isset($_FILES['ean_file']['tmp_name']) && !empty($_FILES['ean_file']['tmp_name'])){
		$orginalFilename=$_FILES['ean_file']['name'];
		$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			// $filenameNoExt = basename($orginalFilename, '.'.$ext);
		$eanFilename = 'liste_des_ean_' . time() . '.' . $ext;
		$uploaded=move_uploaded_file($_FILES['ean_file']['tmp_name'],DIR_UPLOAD.'odr\\'.$eanFilename );
		if($uploaded==false){
			$errors[]="Nous avons rencontré un problème avec votre fichier, impossible de l'uploader vers le serveur";
		}else{
		}
	}
	if(empty($errors)){
		if(!empty($odrFilenames)){
			for ($i=0; $i <count($odrFilenames) ; $i++) {
				$do=$odrDao->addOdrFile($_GET['id'], $odrFilenames[$i]);
			}
		}
	}
	if(empty($errors)){
		if(!empty($eanFilename)){
			$do=$odrDao->updateEanFile($_POST['id_ean_file'], $eanFilename);
		}
	}
	if(empty($errors)){
		if(isset($_POST['ean']) && !empty($_POST['ean'])){
			$odrDao->deleteEan($_GET['id']);
			$listEan=explode(', ',$_POST['ean']);
			for ($i=0; $i < count($listEan); $i++) {

				$do=$odrDao->addEan($_GET['id'],$listEan[$i]);
			}
		}
	}
	if(empty($errors)){
		$odrDao->updateOdr($_GET['id']);
		$successQ='?id='.$_GET['id'].'&success=rec';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}

}
if(isset($_POST['save_name_odr'])){
	foreach($_POST['name'] as $keyIdFile =>$value){
		$odrDao->updateNameOdrFile($keyIdFile,$_POST['name'][$keyIdFile]);
	}
}

if(isset($_GET['id'])){
	$oneOdr=$odrDao->getOdrById($_GET['id']);
	$oneOdrEan=$odrDao->getOdrEan($_GET['id']);
	$oneOdrFiles=$odrDao->getOdrFiles($_GET['id']);
}
if(isset($_GET['success'])){
	$arrSuccess=[
		'rec'=>'Modifications enregistrées',
	];
	$success[]=$arrSuccess[$_GET['success']];
}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue pt-5 ">Modification d'odr</h1>
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
			<h5 class="border-bottom text-main-blue pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Modifier l'ODR</h5>
		</div>
	</div>
	<?php if (isset($_GET['id']) && !empty($oneOdr)): ?>

	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col text-center text-main-blue font-weight-bold pb-5 sub-title">
						Produit et date de validité :
					</div>
				</div>
				<div class="row">
					<div class="col-lg-2">
						<div class="form-group">
							<label for="date_start">Date de début :</label>
							<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=$oneOdr['date_start']?>">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="form-group">
							<label for="date_end">Date de fin :</label>
							<input type="date" class="form-control form-primary" name="date_end" id="date_end" value="<?=$oneOdr['date_end']?>">
						</div>
					</div>
					<div class="col-lg-1">
						<div class="form-group">
							<label for="gt">GT :</label>
							<input type="text" class="form-control form-primary" name="gt" id="gt" value="<?=$oneOdr['gt']?>" title="Veuillez saisir le numéro de GT" id="gt" pattern="^(?:[1-9]|0[1-9]|10)$">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="famille">Famille :</label>
							<input type="text" class="form-control form-primary" name="famille" id="famille" value="<?=$oneOdr['famille']?>">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="marque">Marque :</label>
							<input type="text"  class="form-control form-primary" name="marque" id="marque" value="<?=$oneOdr['marque']?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col border m-3 p-3">
						<div class="row">
							<div class="col text-center text-main-blue font-weight-bold sub-title pb-3">
								Liste des EANS  :
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="font-italic"><i class="fas fa-exclamation-circle pr-2"></i>Vous pouvez soit saisir la liste soit la joindre via un fichier</div>
							</div>
						</div>
						<?php if (isset($oneOdrEan) && !empty($oneOdrEan[0]['ean'])): ?>

						<?php
						$eans=implode(', ',array_map(function($value){ return $value['ean'];}, $oneOdrEan));
						?>
						<div class="row">


							<div class="col">
								<div class="form-group">
									<label for="ean"><i class="fas fa-angle-double-right pr-3"></i>Ajouter des eans à la liste actuelle :</label>
									<textarea class="form-control form-primary" name="ean" id="ean" row="3" pattern = "^[0-9]+(, [0-9]+)*$" ><?=$eans?></textarea>
								</div>
							</div>
						</div>

					<?php endif ?>
					<?php if (isset($oneOdrEan) && !empty($oneOdrEan[0]['ean_file'])): ?>

					<div class="row">
						<div class="col">
							<div class="pb-3 text-main-blue"><i class="fas fa-angle-double-right pr-3"></i>Remplacer le fichier EANS :</div>
						</div>
					</div>
					<div class="row ml-1">
						<div class="col-8 bg-blue-input rounded pt-2" id="filenames">
							<p><span class="text-main-blue font-weight-bold">Fichier sélectionné : <br></span></p>
						</div>
						<div class="col-4">
							<div class="form-group">
								<label class="btn btn-upload-primary btn-file text-center">
									<input type="file" name="ean_file" class='form-control-file'>
									Sélectionner
								</label>
							</div>
						</div>
					</div>
					<input type="hidden" class="form-control form-primary" name="id_ean_file" id="id_ean_file">
				<?php endif ?>

				<div class="row">
					<div class="col" id="file-msg"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col border m-3 p-3">
				<div class="row">
					<div class="col text-center text-main-blue sub-title font-weight-bold pb-3">
						Fichiers de l'ODR :
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour sélectionner plusieurs fichiers, maintenez la touche <strong>ctrl</strong> appuyée lors de la sélection</div>
					</div>
				</div>
				<div class="row ">
					<div class="col-8">
						<div class="row bg-blue-input rounded mx-1 pt-2">
							<div class="col" id="filenames-odr">
								<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span></p>
							</div>
						</div>
						<div class="row">
							<div class="col" id="file-msg-odr"></div>
						</div>
					</div>
					<div class="col-4 pt-2">
						<div class="form-group">
							<label class="btn btn-upload-primary btn-file text-center">
								<input type="file" name="odr_files[]" class='form-control-file' multiple>
								Sélectionner
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row pb-5">
			<div class="col text-right">
				<button type="submit" class="btn btn-primary" name="modif_odr">Enregistrer</button>
			</div>
		</div>
	</form>
</div>
</div>
<?php endif ?>


<div class="bg-separation"></div>
<?php if (!empty($oneOdrFiles)): ?>
	<div class="row mb-3">
		<div class="col">
			<h5 class="border-bottom text-main-blue pb-3 my-3" id="fileodr"><i class="fas fa-pencil-alt pr-3"></i>Supprimer les fichiers  ODR / nommer les liens </h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<form method="post" class="form-inline" action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">
				<table class="table w-auto table-sm">
					<thead class="bg-grey-table">
						<tr>
							<th class="px-5 text-center">Nom du fichier</th>
							<th class="px-5 text-center">Intitulé du lien</th>
							<th class="px-5 text-center ">Suppression</th>

						</tr>
					</thead>
					<tbody>
						<?php foreach ($oneOdrFiles as $key => $odrFile): ?>
							<tr>
								<td class="px-5 text-center">
									<a href="<?=URL_UPLOAD.'odr/'.$odrFile['file']?>"><?= $odrFile['file']?></a><br>
								</td>
								<td>

									<div class="form-group">
										<label for="name"></label>
										<input type="text" class="form-control wider" name="name[<?=$odrFile['id']?>]" id="name" value="<?=(!empty($odrFile['filename'])) ? $odrFile['filename']:''?>">
									</div>
								</td>

								<td class="text-right">
									<a href="odr-delete-file.php?id=<?=$odrFile['id'].'&id_odr='.$_GET['id']?>" class="btn btn-orange" >Supprimer</a>
								</td>

							</tr>

						<?php endforeach ?>
						<tr>
							<td colspan="2"></td>
							<td class="text-right">
								<button class="btn btn-primary" name="save_name_odr"><i class="fas fa-save pr-3"></i>Enregistrer</button>
							</td>
						</tr>

					</tbody>
				</table>

			</form>
		</div>
	</div>
<?php endif ?>



</div>

<?php
require '../view/_footer-bt.php';
?>