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


require_once  '../../vendor/autoload.php';


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
function getThisLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT galec, dossiers.dossier,
		id_reclamation, article, descr, ean, fournisseur, qte_litige, pj, inv_palette, palette, inv_article, inv_qte, inv_descr, inv_tarif, inv_fournisseur, inversion,
		reclamation
		FROM dossiers
		LEFT JOIN details ON dossiers.id= details.id_dossier
		LEFT JOIN reclamation ON id_reclamation=reclamation.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
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
}

function getDial($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM dial WHERE id_dossier= :id");
	$req->execute(array(
		':id'		=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

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
		':mag'		=>	1,

	));
	return $req->rowCount();
	// return $req->errorInfo();
}

//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$thisLitige=getThisLitige($pdoLitige);
$dials=getDial($pdoLitige);

$errors=[];
$success=[];


// redirige su un mag essaye de regarde un litige qui n'est ^pas le sien
if($thisLitige[0]['galec'] !=$_SESSION['id_galec'])
{
	header('Location:notyours.php');
}

$uploadDir= '..\..\..\upload\litiges\\';


if(isset($_POST['submit']))
{


	if(empty($_FILES['form_file']['name'][0]))
	{
		$filelist="";
	}
	else
	{
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
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					$filename_without_ext = basename($filename, '.'.$ext);
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
			if($newMsg!=1)
			{
				$errors[]="impossible d'ajouter le message dans la base de donnée";
			}
		}
		if(count($errors)==0)
		{
			// ---------------------------------------
			if(VERSION =='_')
			{
				$mailBt=array('valerie.montusclat@btlec.fr');
			}
			else
			{
				if($_SESSION['code_bt']!='4201')
				{
					$mailBt=array('litigelivraison@btlec.fr');
				}
				else
				{
					$mailBt=array('valerie.montusclat@btlec.fr');
				}
			}

			$btTemplate = file_get_contents('mail-bt-msgmag.php');
			$btTemplate=str_replace('{DOSSIER}',$thisLitige[0]['dossier'],$btTemplate);
			$btTemplate=str_replace('{MAG}',$_SESSION['nom'],$btTemplate);
			$subject='Portail BTLec Est  - nouveau message sur le dossier litige ' . $thisLitige[0]['dossier'];
			// ---------------------------------------
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($btTemplate, 'text/html')
			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BTLec'))
			->setTo($mailBt)
			->addBcc('valerie.montusclat@btlec.fr');
			$delivered=$mailer->send($message);
			if($delivered >0)
			{
				$loc='Location:'.htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id'].'&success=ok';
				header($loc);
			}
			else
			{
				$errors[]='Le mail n\'a pas pu être envoyé à notre service livraison';
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
	<h1 class="text-main-blue py-5 ">Dossier litige n°<?=$thisLitige[0]['dossier']?></h1>

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
			<?php
		// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette
			if($thisLitige[0]['id_reclamation']==7)
			{
				include('dt-mag-palette.php');
			}
			else
			{
				include('dt-mag-prod.php');
			}
			?>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row mt-3">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Contacter le service litige : </h5>
		</div>
	</div>
	<div class="row my-3">
		<div class="col">
			<div class="row  bg-alert-primary rounded">
				<div class="col p-3">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="action" class="heavy">Votre message :</label>
									<textarea type="text" class="form-control" row="6" name="msg" placeholder="Message" id="msg" required></textarea>
								</div>

							</div>
						</div>
						<div class="row align-items-end">
							<div class="col">
								<div id="file-upload">
									<fieldset>
										<p class="heavy pt-2">Pièces jointes :</p>
										<div class="form-group">
											<p><input type="file" name="form_file[]" class='form-control-file' multiple=""></p>
										</div>
									</fieldset>
								</div>
								<div id="filelist"></div>
							</div>
							<div class="col-auto">
								<p class="text-right "><button type="submit" id="submit_t" class="btn btn-primary" name="submit"><i class="fas fa-envelope pr-3"></i>Envoyer</button></p>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="bg-separation"></div>

	<div class="row mt-5">
		<div class="col">
			<h5 class="khand text-main-blue pb-3">Echanges avec BTLec : </h5>
		</div>
	</div>
	<div class="row">
		<div class="col">



			<?php
			if(empty($dials))
			{
				echo '<p class="text-center">Aucun message n\'a été échangé avec BTLec</p>';
			}
			else
			{
				foreach($dials as $dial)
				{
					if($dial['mag']==1)
					{
						$bgColor='alert-primary';
					}
					else
					{
						$bgColor='alert-warning';

					}
					$pj='';
					if($dial['filename']!='')
					{
						$pj=createFileLink($dial['filename']);
					}
					echo '<div class="row alert '.$bgColor.'">';
					echo '<div class="col">';
					echo '<div class="text-right"><i class="far fa-calendar-alt pr-3"></i>'.$dial['date_saisie'] .'</div>';
					echo $dial['msg'];
					echo '<div class="text-right">'.$pj .'</div>';

					echo '</div>';
					echo '</div>';
				}

			}
			?>


		</div>
	</div>

	<!-- ./container -->
</div>
<script type="text/javascript">

	$(document).ready(function (){

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