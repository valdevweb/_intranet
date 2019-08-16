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


function addFlash($pdoBt, $vignette,$flashfile,$link){
	$content=strip_tags($_POST['content-form']);
	$content=nl2br($content);
	$req=$pdoBt->prepare("INSERT INTO flash (title, content, vignette, pj, date_start, date_end, created_by, lien) VALUES (:title, :content, :vignette, :pj, :date_start, :date_end, :created_by, :lien)");
	$req->execute(array(
		':title'	=>$_POST['title-form'],
		':content'	=>$content,
		':vignette'	=>$vignette,
		':pj'	=>$flashfile,
		':date_start'	=>$_POST['date-start-form'],
		':date_end'	=>$_POST['date-end-form'],
		':created_by'	=>$_SESSION['id_web_user'],
		':lien'	=>$link
	));
	return $req->rowCount();
}




//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$uploadDir= '..\..\..\upload\flash\\';

$extVignette=[
	'jpg',
	'jpeg',
	'png',
	'gif',
];

$extFile=[
	'pdf'
];

if(isset($_POST['submit']))
{
	// upload des fichiers avant insertion en db
	if(!empty($_FILES['vignette-form']['name']))
	{
		$maxFileSize = 3 * 1024 * 1024;
		if($_FILES['vignette-form']['size'] > $maxFileSize)
		{
			$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 3 Mo';
		}
		else
		{
			$vignette=$_FILES['vignette-form']['name'];
			$ext = pathinfo($vignette, PATHINFO_EXTENSION);
			if(!in_array($ext,$extVignette))
			{
				$errors[]='Les fichiers de type "'.$ext .'"" ne sont pas autorisés. Veuillez joindre un fichier image ( jpg, gif, png)';
			}
			else
			{
				$vignette_without_ext = basename($vignette, '.'.$ext);
				$vignette =  'vignette-' . date('Ymd-his') . '.' . $ext;

				// Generate new vignette => ajout d'un timestamp au nom du fichier
				$uploaded=move_uploaded_file($_FILES['vignette-form']['tmp_name'],$uploadDir.$vignette );
				if($uploaded==false)
				{
					$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée";
				}
			}
		}
	}
	else
	{
		$vignette='';
	}
	if(!empty($_FILES['pj-form']['name']))
	{
		$maxFileSize = 3 * 1024 * 1024;
		if($_FILES['pj-form']['size'] > $maxFileSize)
		{
			$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 3 Mo';
		}
		else
		{
			$flashfile=$_FILES['pj-form']['name'];
			$ext = pathinfo($flashfile, PATHINFO_EXTENSION);
			if(!in_array($ext,$extFile))
			{
				$errors[]='Les fichiers de type "'.$ext .'"" ne sont pas autorisés. Veuillez joindre un fichier pdf';
			}
			else
			{
				$flashfile_without_ext = basename($flashfile, '.'.$ext);
				$flashfile =  'flash-' . date('Ymd-his') . '.' . $ext;

				// Generate new flashfile => ajout d'un timestamp au nom du fichier
				$uploaded=move_uploaded_file($_FILES['pj-form']['tmp_name'],$uploadDir.$flashfile );
				if($uploaded==false)
				{
					$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée";
				}
			}
		}
	}
	else
	{
		$flashfile="";
	}
	// si pas d'erreur d'upload, on ajoute les infos
	if(count($errors)==0)
	{
		if(empty($_POST['lien-form']))
		{
			$link='';
		}
		else
		{
			$link=$_POST['lien_form'];

		}
		$row=addFlash($pdoBt, $vignette,$flashfile,$link);
		if($row==1)
		{
			$dest=['valerie.montusclat@btlec.fr', 'stephane.wendling@btlec.fr'];
// ---------------------------------------


// ---------------------------------------
// gestion du template
			$htmlMail = file_get_contents('flash-mail-new.php');
			$subject='Portail BTLec - nouveau flash info ';

// ---------------------------------------
// initialisation de swift
			$transport = (new Swift_SmtpTransport('217.0.222.26', 25));
			$mailer = new Swift_Mailer($transport);
			$message = (new Swift_Message($subject))
			->setBody($htmlMail, 'text/html')

			->setFrom(array('ne_pas_repondre@btlec.fr' => 'Portail BT'))
// ->setTo(array('valerie.montusclat@btlec.fr', 'valerie.montusclat@btlec.fr' => 'val'))
			->setTo($dest);
// ->addCc($copySender['email'])
			// ->addBcc('valerie.montusclat@btlec.fr');
		// ->attach($attachmentPdf)
		// ->attach(Swift_Attachment::fromPath('demande-culturel.xlsx'));

// echec => renvoie 0
			$delivered=$mailer->send($message);
			if($delivered >0)
			{
				$success[]='Votre info flash a bien été enregistrée, vous receverez un mail vous indiquant si elle a été validée ou non';
			}

		}
		else{
			$errors[]='Impossible d\'enregistrer votre info flash, veuillez avertir la personne concernée';
		}
	}



}
// 981

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
	<h1 class="text-main-blue py-5 ">Ajouter une info flash</h1>

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
		<div class="col-lg-1"></div>

		<div class="col"><p>Afin d'assurer un affichage correct de votre flash info, si vous ajoutez une vignette, celle-ci doit être le plus proche possible du format  <br>
			<span class="heavy text-red">150x150 pixels</span> <br><br>
			Exemple de rendu d'une info flash avec vignette et pdf :
		</p>

			<div class="text-center"><img src="../img/documents/exemple.jpg"></div>
		</div>
		<div class="col-lg-1"></div>

	</div>
	<h3 class="mt-5 text-center text-main-blue">Votre info flash</h3>
	<div class="row mt-3">
		<div class="col-lg-1"></div>
		<div class="col">

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data" id="">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label class="text-main-blue">Titre :</label>
							<input type="text" class="form-control" name="title-form" required></input>
						</div>
						<div class="form-group">
							<label class="text-main-blue">Texte accompagnant l'information :</label>
							<textarea class="form-control" name="content-form" rows="3" required></textarea>
						</div>
					</div>
				</div>

				<p class="text-center mt-3 text-main-blue">Veuillez spécifier préciser la période de parution : </p>
				<div class="row mt-3">
					<div class="col">
						<div class="form-group">
							<label class="text-main-blue">Date de début :</label>
							<input type="date" class="form-control" name="date-start-form" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label class="text-main-blue">Date de fin :</label>
							<input type="date" class="form-control" name="date-end-form" required>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col">
						<p class="text-main-blue">Informations optionnelles :</p>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<div class="text-main-blue"><i class="far fa-image pr-3"></i><label for='vignette'> Ajouter une vignette : </label></div>
							<input type='file' class='form-control-file' id='vignette' name='vignette-form'>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<div class="text-reddish"><i class="far fa-file-pdf pr-3"></i><label for='pj'>Ajouter un document : </label></div>
							<input type='file' class='form-control-file' id='pj' name='pj-form'>
						</div>
					</div>
				</div>
				<?php
				ob_start()
				 ?>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label class="text-main-blue">Lien :</label>
							<input type="text" class="form-control" name="lien-form">
						</div>
					</div>
					<div class="col"></div>
				</div>
				<?php
				$admin=ob_get_contents();
				ob_end_clean();
				if($_SESSION['id_web_user']==981){
					echo $admin;
				}

				 ?>

				<!-- submit -->
				<p class="pt-5 text-right"><button class="btn btn-primary" type="submit" id="" name="submit">Envoyer</button></p>
				<!-- ./submit -->
			</form>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>