<?php
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
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
include   "../../functions/form.fn.php";
 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------

function addMsg($pdoBt){
	$req=$pdoBt->prepare("INSERT INTO occ_msg (objet, msg, name, email, id_web_user, date_insert) VALUES (:objet, :msg, :name, :email, :id_web_user, :date_insert)");
	$req->execute([
		':objet'	=>$_POST['objet'],
		':msg'	=>$_POST['msg'],
		':name'	=>$_POST['name'],
		':email'	=>$_POST['email'],
		':id_web_user'	=>$_SESSION['id_web_user'],
		':date_insert' =>date('Y-m-d H:i:s')

	]);
	return $pdoBt->lastInsertId();
}


$errors=[];
$success=[];

$uploadDir=UPLOAD_DIR.'\mag\\';


if(isset($_POST['submit'])){
	if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['objet']) || empty($_POST['msg'])){
		$errors[]="Veuillez remplir tous les champs";
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errors[]="l'adresse mail saisie n'est pas valide";
	}
	if(count($errors)==0){
		$lastInsertId=addMsg($pdoBt);
		echo $lastInsertId;
	}

	for($i=0;$i<count($_FILES['files']['name']) ;$i++){
		if($_FILES['files']['name'][$i]!=""){
			$filename=$_FILES['file']['name'][$i];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$filenameNoExt = basename($filename, '.'.$ext);
			$filenameNoExt=str_replace(" ","_",$filenameNoExt);
			$filenameNoExt=str_replace(";","",$filenameNoExt);

			$filenameNew=$filenameNoExt.'-'.date('YmdHis').'.'.$ext;
			if($fileList==""){
				$fileList= $filenameNew;

			}else{
				$fileList= $fileList.'; '.$filenameNew;
			}
			$uploaded=move_uploaded_file($_FILES['files']['tmp_name'][$i],$uploadDir.$filenameNew );
			if($uploaded==false){
				$errors[]="Impossible d'ajouter la pièce jointe";
			}
		}
	}



}

//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);





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
	<h1 class="text-main-blue py-5 ">GT Occasion - accueil</h1>

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
			<h2 class="text-main-blue">Vos demandes en cours</h2>
		</div>
	</div>



	<div class="row border my-5 p-3 rounded shadow">

		<div class="col">
			<div class="row">
				<div class="col">
					<h2 class="text-main-blue text-center">Contacter le GT occasion</h2>
					<p class="font-italic yanone">les champs <span class="text-danger"> *</span> sont obligatoires</p>
				</div>
			</div>
			<form  action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="objet">Objet de votre demande : </label><span class="text-danger"> *</span>
							<input type="text" class="form-control" name="objet" id="objet" value="<?=isset($_POST['objet']) ? $_POST['objet'] : ''?>" required>
							<div id="validate-objet"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Votre nom :</label><span class="text-danger"> *</span>
							<input type="text" class="form-control" name="name" id="name" value="<?=isset($_POST['name']) ? $_POST['name'] : ''?>" required>
							<div id="validate-name"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="email">Votre adresse mail :</label><span class="text-danger"> *</span>
							<input type="email" class="form-control" name="email" id="email" value="<?=isset($_POST['email']) ? $_POST['email'] : ''?>" required>
							<div id="validate-email"></div>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="msg">Votre demande</label><span class="text-danger"> *</span>
							<textarea class="form-control" id="msg" name="msg" rows="3" required><?=isset($_POST['msg']) ? $_POST['msg'] : ''?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label for='files'>Ajouter une pièce jointe : </label>
						<input type='file' class='form-control-file' id='files' name='files[]' multiple="" >

					</div>
				</div>
				<div class="row mt-3">
					<div class="col text-right">
						<button class="btn btn-orange " name="submit" id="submit">Valider</button>
						<div id="wait-msg"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col"></div>
	</div>

	<!-- ./container -->
</div>
<script type="text/javascript">


	$(document).ready(function(){
		$("#email").keyup(function(){
			var email = $("#email").val();
			var filter = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!filter.test(email)) {
             //alert('Please provide a valid email address');
             $("#validate-email").text(email+" n'est pas une adresse mail valide");
             $("#validate-email").addClass('text-danger');
             email.focus;
         } else {
         	$('#validate-email').removeClass('text-danger');
         	if(!$('#validate-email').hasClass('text-success')){
         		$('#validate-email').addClass('text-success');
         		$('#validate-email').text('');
         		$('#validate-email').append('<i class="fas fa-check pr-3"></i>adresse mail valide');
         	}

         }
     });

		function lengthValidate(minLength, maxLength, id){
			var msgId='#validate-'+id;

			$('#'+id).on('keydown keyup change', function(){
				var char = $(this).val();
				var charLength = $(this).val().length;
				if(charLength < minLength){
					$(msgId).addClass('text-danger');
					$(msgId).text('le texte est trop court, minimum '+minLength+' caractères.');
				}else if(charLength > maxLength){
					$(msgId).addClass('text-danger');
					$(msgId).text('le texte est trop long, maximum '+maxLength+' caractères.');
					$(this).val(char.substring(0, maxLength));
				}else{
					$(msgId).removeClass('text-danger');
					if(!$(msgId).hasClass('text-success')){
						$(msgId).addClass('text-success');
						$(msgId).text('');
						$(msgId).append('<i class="fas fa-check"></i>');

					}
				// $('#name-length').addClass('text-success');

			}
		});
		}
		$('#name').focus(lengthValidate(2,200,'name'));
		$('#objet').focus(lengthValidate(5,200,'objet'));

		$("#submit").submit(function( event )
		{
			$("#wait-msg" ).append("<i class='fas fa-spinner fa-spin'></i><span class='pl-3'>Merci de patienter pendant l'envoi</span>")
		});


	});
</script>
<?php
require '../view/_footer-bt.php';
?>