<?php

if(isset($_POST['add-doc'])){
	$listFilename=[];
	if(isset($_FILES['files_doc']['tmp_name'][0]) &&  !empty($_FILES['files_doc']['tmp_name'][0])){
		for ($i=0; $i <count($_FILES['files_doc']['tmp_name']) ; $i++) {
			if(empty($_POST['filename'][$i])){
				$warning=true;
			}
			if(!$warning){
				$orginalFilename=$_FILES['files_doc']['name'][$i];
				// code permettant de récupérer l'extension du fichier
				$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);
				$filenameNoExt = basename($orginalFilename, '.'.$ext);


				$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;
				$uploaded=move_uploaded_file($_FILES['files_doc']['tmp_name'][$i],UPLOAD_DIR_EVO.$filename );
				if($uploaded==false){
					$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée 2";
				}else{
					$listFilename[]=$filename;
				}
			}

		}
	}
	if($warning){
		$errors[]="Merci de donner un nom à vos fichiers";
	}
	if(!empty($listFilename) && empty($errors)){
		for ($i=0; $i < count($listFilename); $i++) {
			echo $listFilename[$i];
			echo  $_POST['filename'][$i];
			$docDao->insertDoc($_GET['id'], $listFilename[$i], $_POST['filename'][$i]);

		}
	}
	$successQ='?success=doc&id='.$_GET['id'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<div class="row">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post"  enctype="multipart/form-data">
				<div class="row">
					<div class="col">
						<div class="row">
							<div class="col mb-3 text-orange text-center sub-title font-weight-bold ">
								Ajout de documents  :
							</div>
						</div>
						<div class="row">
							<div class="col  bg-dark-input rounded pt-2">
								<div class="form-group text-right">
									<label class="btn btn-upload-grey btn-file text-center">
										<input type="file" name="files_doc[]" class='form-control-file' multiple id="files-doc">
										Sélectionner
									</label>
								</div>
								<div class="row mt-3">
									<div class="col" id="form-zone"></div>
								</div>
								<div class="row mt-3">
									<div class="col" id="warning-zone"></div>
								</div>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col text-right">
								<button class="btn btn-dark" name="add-doc">Ajouter</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- déjà inclu -->
	<script src="vendor/jquery/jquery-3.2.1.js"></script>
	<!-- à inclure et modifier -->
	<script src="public/js/upload-helpers.js"></script>
	<script  type="text/javascript">
		$(document).ready(function() {
			$('#files-doc').change(function(){
				multipleWithName('files-doc','warning-zone', 'form-zone')
			});
		});
	</script>

</body>
</html>