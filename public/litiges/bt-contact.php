
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

function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dossiers WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

$fLitige=getLitige($pdoLitige);



function getPreTxt($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dial_help ORDER BY nom,pretxt");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$allPreTxt=getPreTxt($pdoLitige);


function addMsg($pdoLitige, $filelist)
{
	$msg=strip_tags($_POST['msg']);
	$msg=nl2br($msg);
	$req=$pdoLitige->prepare("INSERT INTO dial(id_dossier,date_saisie,msg,id_web_user,filename,mag) VALUES (:id_dossier,:date_saisie,:msg,:id_web_user,:filename,:mag)");
	$req->execute(array(
		':id_dossier'		=>$_GET['id'],
		':date_saisie'		=>date('Y-m-d H:i:s'),
		':msg'				=>$msg,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':filename'		=>	$filelist,
		':mag'		=>	0,

	));
	return $req->rowCount();
	// return $req->errorInfo();
}

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

function createFileLink($filelist)
{
	$rValue='';
	$filelist=explode(';',$filelist);

		for ($i=0; $i < count($filelist); $i++)
		{
			if($filelist[$i] !="")
			{
			$rValue.='<a href="'.UPLOAD_DIR.'/litiges/'.$filelist[$i].'"><span class="pr-3"><i class="fas fa-link"></i></span></a>';

			}
		}
	return $rValue;
}

$errors=[];
$success=[];
$defaultTxt='Bonjour,&#13;&#10;&#13;&#10;&#13;&#10;Cordialement,&#13;&#10;'.$_SESSION['nom_bt'];
$uploadDir= '..\..\..\upload\litiges\\';

if(isset($_POST['submit']))
{


	if(empty($_FILES['form_file']['name'][0]))
	{
	// pas de fichier
		$filelist="";
	}
	else
	{
	//présence de fichier
		$filelist="";
		$nbFiles=count($_FILES['form_file']['name']);
		for ($f=0; $f <$nbFiles ; $f++)
		{
			$filename=$_FILES['form_file']['name'][$f];
				$maxFileSize = 5 * 1024 * 1024; //5MB

				if($_FILES['form_file']['size'][$f] > $maxFileSize)
				{
					$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 5 Mo';
				}
				else
				{
					// cryptage nom fichier
			 		// Get the fileextension
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
    				  // Get filename without extesion
					$filename_without_ext = basename($filename, '.'.$ext);
  					// Generate new filename => ajout d'un timestamp au nom du fichier
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
					$uploaded=move_uploaded_file($_FILES['form_file']['tmp_name'][$f],$uploadDir.$filename );
				}
				if($uploaded==false)
				{
					$errors[]="impossible de télécharger le fichier";
				}
				else
				{
					$filelist.=$filename .';';
				}
			}
		}
		// fin présence fichier

		if(count($errors)==0)
		{
			$newMsg=addMsg($pdoLitige, $filelist);
			if($newMsg>0)
			{
				$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&success=ok';
				header($loc);
				// reload
			}
			else
			{
				$errors[]="impossible d'ajouter le message dans la base de donnée";
			}
		}

	}

if(isset($_GET['success']))
	 {
		$success[]="message envoyé avec succés";

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
	<h1 class="text-main-blue py-5 ">Dossier N° <?= $fLitige['dossier']?></h1>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>
	<div class="row">
		<div class="col">
			<h2 class="khand text-main-blue">Echanges avec le magasin</h2>
		</div>
	</div>
	<div class="row no-gutters mb-5">
		<div class="col">
			<div class="row no-gutters">
				<div class="col">
					<table class="table table-bordered">
						<thead class="bg-kaki">
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
		</div>
	</div>






	<div class="row">
		<div class="col">
			<h2 class="khand text-main-blue">Envoyer un nouveau message</h2>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="row">
				<div class="col bg-kaki-light rounded p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
						<p class="heavy">Réponses préparées :</p>
						<div class="form-group">
							<select name="pretxt" id="pretxt" class="form-control">
								<option value="">Sélectionnez une réponse préparée</option>
								<?php
								foreach($allPreTxt as $pretxt)
								{
									echo '<option value="'.$pretxt['id'].'">'.$pretxt['nom'].' ('. $pretxt['pretxt'].'</option>';

								}


								?>
							</select>

						</div>
						<div class="form-group">
							<label for="action" class="heavy">Votre message :</label>
							<textarea type="text" class="form-control" row="6" name="msg" placeholder="Message" id="msg" required><?=$defaultTxt?></textarea>
						</div>
						<div id="file-upload">
							<fieldset>
								<p class="heavy pt-2">Pièces jointes :</p>
								<div class="form-group">
									<p><input type="file" name="form_file[]" class='form-control-file' multiple=""></p>
								</div>
							</fieldset>
						</div>
							<div id="filelist"></div>

						<p class="text-right"><button type="submit" id="submit_t" class="btn btn-kaki" name="submit"><i class="fas fa-plus-square pr-3"></i>Ajouter</button></p>

					</form>
				</div>
			</div>
		</div>
	</div>


	<!-- RETOUR -->
	<div class="row my-5">
		<div class="col-lg-1 col-xxl-2"></div>
		<div class="col">
			<p class="text-center"><a href="bt-detail-litige.php?id=<?=$_GET['id']?>" class="btn btn-primary">Retour</a></p>


		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


</div>
<script type="text/javascript">

	$(document).ready(function (){

		$('#pretxt').on('change',function(){
			var txt=$('#pretxt option:selected').text();
			var pretxt=txt.split(' (');
			var bjr="Bonjour,\n\n";
			var cdlt="\n\n"+"Cordialement,\n";
			var name='<?php echo $_SESSION['nom_bt'];?>';
 						// console.log(name);
 						$('#msg').val(bjr + pretxt[1] + cdlt + name);
 					});
		var fileName='';
		var fileList='';
		$('input[type="file"]').change(function(e){
			var nbFiles=e.target.files.length;
			for (var i = 0; i < nbFiles; i++)
			{
            fileName=e.target.files[i].name;
            fileList += fileName + ' - ';
        }
        titre='<p><span class="heavy">Fichier(s) : </span>';
        end='</p>';
        all=titre+fileList+end;
        $('#filelist').append(all);
        fileList="";
    });


	});

</script>





<?php

require '../view/_footer-bt.php';

?>