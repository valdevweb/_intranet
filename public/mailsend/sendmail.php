<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require_once  '../../vendor/autoload.php';
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/UserHelpers.php';
require '../../Class/MailDao.php';
require '../../Class/UserDao.php';
require '../../Class/CrudDao.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoMag=$db->getPdo('magasin');
$pdoBtlec=$db->getPdo('btlec');

$mailDao=new MailDao($pdoMag);
$userDao=new UserDao($pdoUser);
$btlecCrud=new CrudDao($pdoBtlec);

$user=UserHelpers::getUserByIdWebuser($pdoUser,$_SESSION['id_web_user']);


if(isset($_POST['send'])){
	if(empty($_POST['to-email'])){
		$errors[]="Vous n'avez pas sélectionné de destinataire";
	}
	if(empty($_POST['from_email'])){
		$errors[]="Vous n'avez pas saisie d'adresse d'expédition";
	}

	if(empty($_POST['from_name'])){
		$errors[]="Vous n'avez pas saisie de nom d'expéditeur";
	}

	if(str_contains($_POST['from_email'],"\\")){
		$domain=explode("@",$_POST['from_email']);
		if($domain[1]!="btlec.fr"){
			$errors[]="Attention, vous avez choisi d'envoyer un mail via le serveur lotus, l'adresse d'expédition doit être sous la forme expediteur<b>@btlec.fr</b>";
		}
		if(empty($errors)){
			$from=str_replace("\\","", $_POST['from_email']);
			include "sendmail/01-send-lotus.php";
		}

	}else{
		$from=$_POST['from_email'];
		include "sendmail/01-send-relai.php";

	}

	if(empty($errors)){
		$mailData=[
			'from_email'=>$from,
			'from_name'=>$_POST['from_name'],
			'dest_email'=>$_POST['to-email'],
			'dest_name'=>$_POST['to-name'],
			'cc_email'=>$_POST['cc-email'],
			'cc_name'=>$_POST['cc-name'],
			'objet'=>$_POST['objet'],
			'message'=>$_POST['mail'],
			'by_insert'=>$_SESSION['id_web_user'],
			'date_insert'=>date('Y-m-d H:i:s')
		];
		$idEmail=$btlecCrud->insert('emailsend', $mailData);

		if(isset($_POST['file_file'])){
			for ($i=0; $i < count($_POST['file_file']) ; $i++) {
				$btlecCrud->insert('emailsend_files', ['filename'=>$_POST['file_file'][$i], 'id_emailsend' =>$idEmail]);

			}
		}


	}


}

if(isset($_GET['success'])){
	$arrSuccess=[
		'send'=>'Mail envoyé avec succès',
	];
	$success[]=$arrSuccess[$_GET['success']];
}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-3">
		<div class="col">
			<h1 class="text-main-blue">Envoi de mail</h1>
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
	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">

		<div class="row mb-2">
			<div class="col bg-very-light-grey rounded mx-2 pt-2">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="from_email">Mail de l'expéditeur:</label>
							<input type="text" class="form-control" name="from_email" id="from_email" value="<?=$user['email']?>" required>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="from_name">Nom de l'expéditeur :</label>
							<input type="text" class="form-control" name="from_name" id="from_name" value="<?=$user['fullname']?>" required>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col alert-to-link rounded mx-2 pt-2 mb-2">
				<div class="form-group">
					<div id="tolist-wrapper">A :<span id="tolist"></span></div>
					<input type="text" class="form-control"  id="to" placeholder="&#xf002;" style="font-family:'Font Awesome 5 Free'; font-weight:900">
				</div>
				<input type="hidden" class="form-control" required name="to-name" id="to-name" value="<?=$_POST['to-name']??''?>">
				<input type="hidden" class="form-control"   required name="to-email" id="to-email" value="<?=$_POST['to-email']??''?>">
			</div>
		</div>
		<div id="to-results">
			<div class="row">
				<div class="col">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col alert-cc-link rounded mx-2 pt-2 mb-2">
				<div class="form-group">
					<div id="cclist-wrapper">Cc :<span id="cclist"></span></div>
					<input type="text" class="form-control" name="cc" id="cc" placeholder="&#xf002;" style="font-family:'Font Awesome 5 Free'; font-weight:900">
				</div>
				<input type="hidden" class="form-control"  name="cc-name" id="cc-name" value="<?=$_POST['cc-name']??''?>">
				<input type="hidden" class="form-control"  name="cc-email" id="cc-email" value="<?=$_POST['cc-email']??''?>">

			</div>
		</div>
		<div id="cc-results">
			<div class="row">
				<div class="col">

				</div>
			</div>
		</div>
		<div class="row">
			<div class="col bg-very-light-grey rounded mx-2 pt-2">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="objet">Objet :</label>
							<input type="text" class="form-control" name="objet" id="objet" value="<?=$_POST['objet']??''?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<!-- <label for="mail"></label> -->
							<textarea class="form-control" name="mail" id="mail" row="3" style="height:300px;"><?=$_POST['email']??''?></textarea>
						</div>
					</div>
				</div>
				<div class="row pb-3">
					<div class="col" id="file">
						<input type="file" name="file" class="dragndropfile" multiple="multiple">
						<div class="upload-area uploadfile">
							<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
						</div>
						<div class="filename"></div>
						<div class="readablename"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row pb-5">
			<div class="col text-right">
				<button class="btn btn-primary" name="send">Envoyer</button>
			</div>
		</div>
	</form>


</div>
<script src="../js/dragndrop.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$('#to-results').hide();
		$('#cc-results').hide();

		$(document).keyup(function(e) {
			if (e.key === "Escape") {
				$('#to-results').hide();
				$('#cc-results').hide();

			}
		});




		$("html").on("dragover", function(e) {
			e.preventDefault();
			e.stopPropagation();
		});
		$("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

		var idName="#file";
		var  readable=false;
		var order=false;

		$(idName +' .upload-area').on('dragenter', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idName + " .upload-area p").text("Déposez");
		});

		$(idName +' .upload-area').on('dragover', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idName +" .upload-area p").text("Déposez");
		});

		$(idName +' .upload-area').on('drop', function (e) {
			console.log("drop" +idName);

			e.stopPropagation();
			e.preventDefault();

			var files = e.originalEvent.dataTransfer.files;
			var fd = new FormData();
			for (var i = 0; i < files.length; i++) {
				fd.append('file[]', files[i]);
			}
			var data=uploadData(fd, 'sendmail/ajax-drop.php', idName, readable, order);
			console.log(data)

		});

		$(idName +" .uploadfile").click(function(){
			$(idName +" .dragndropfile").click();
		});

		$(idName +" .dragndropfile").change(function(){
			console.log("select" +idName);

			var fd = new FormData();
			var nbFiles=($(idName +' .dragndropfile')[0].files).length;
			for (var i = 0; i < nbFiles; i++) {
				var file = $(idName +' .dragndropfile')[0].files[i];
				fd.append('file[]', file);
			}
			uploadData(fd, 'sendmail/ajax-drop.php', idName,  readable, order);
		});





		$(document).mouseup(function(e){
			var containerTo = $("#to-results");
			var containerCc = $("#cc-results");

			// if the target of the click isn't the containerTo nor a descendant of the containerTo
			if (!containerTo.is(e.target) && containerTo.has(e.target).length === 0)  {
				containerTo.hide();
			}
			if (!containerCc.is(e.target) && containerCc.has(e.target).length === 0)  {
				containerCc.hide();
			}
		});

		$('#to').blur(function(){
			$('#to').val('');
		});
		$('#cc').blur(function(){
			$('#cc').val('');
		});

		$('#close-to-link').click(function(){
			console.log("clic");
			$('#to-results').hide();
		});
		$('#close-cc-link').click(function(){
			$('#cc-results').hide();
		});

		$(document).on('click','#close-to-link',function(e){
			$('#to-results').hide();

		});
		$(document).on('click','#close-cc-link',function(e){
			$('#cc-results').hide();

		});

		$('#to').keyup(function(){
			var to=$('#to').val();
			$('#to-results').show();
			$.ajax({
				url:"sendmail/ajax-search-ld.php",
				method:"POST",
				data:{search:to,link_name:"to-link"},
				success:function(data){
					$('#to-results').empty();
					$('#to-results').html(data);
				}
			});
		});
		$('#to').on("focus",function(){
			console.log("click");
			$('#to-results').hide();

		});

		$('#cc').keyup(function(){
			var cc=$('#cc').val();
			$('#cc-results').show();
			$.ajax({
				url:"sendmail/ajax-search-ld.php",
				method:"POST",
				data:{search:cc,link_name:"cc-link"},
				success:function(data){
					// console.log(data);

					$('#cc-results').empty();
					$('#cc-results').html(data);
				}
			});
		});
		$(document).on('click','.to-link',function(e){

			// var name=$(this).data("id-result");
			var name=$(this).data("name");
			var email=$(this).data("email");
			email=email.replace(/<[^>]*>?/gm, '');
			name=name.replace(/<[^>]*>?/gm, '');
			console.log(name);
			var styleldname="<a class='selected-element' data-name='"+name+"'>"+name+"</a>"
			$('#tolist').append(styleldname);
			var listName=$('#to-name').val();
			listName+=name+";";
			$('#to-name').val(listName);

			var listEmail=$('#to-email').val();
			listEmail+=email+";";
			$('#to-email').val(listEmail);
		});
		$(document).on('click','.selected-element',function(e){
			var listName=$('#to-name').val();
			var listEmail=$('#to-email').val();

			var name=$(this).data("name");

			$(this).remove();
			var arrayName=listName.split(";");
			var arrayEmail=listEmail.split(";");

			var index = arrayName.indexOf(name.toString());
			if (index !== -1) {
				arrayName.splice(index, 1);
				arrayEmail.splice(index, 1);
			}
			listName=arrayName.join(";")
			listEmail=arrayEmail.join(";")

			$('#to-name').val(listName);
			$('#to-email').val(listEmail);
		});

		$(document).on('click','.cc-link',function(e){
			// var name=$(this).data("id-result");
			var name=$(this).data("name");
			var email=$(this).data("email");
			var name=$(this).data("name");
			email=email.replace(/<[^>]*>?/gm, '');
			name=name.replace(/<[^>]*>?/gm, '');
			var styleldname="<a class='selected-element' data-name='"+name+"'>"+name+"</a>"
			$('#cclist').append(styleldname);
			var listName=$('#cc-name').val();
			listName+=name+";";
			$('#cc-name').val(listName);

			var listEmail=$('#cc-email').val();
			listEmail+=email+";";
			$('#cc-email').val(listEmail);
		});
		$(document).on('click','.selected-element',function(e){
			var listName=$('#cc-name').val();
			var listEmail=$('#cc-email').val();



			$(this).remove();
			var arrayName=listName.split(";");
			var arrayEmail=listEmail.split(";");

			var index = arrayName.indexOf(name.toString());
			if (index !== -1) {
				arrayName.splice(index, 1);
				arrayEmail.splice(index, 1);
			}
			listName=arrayName.join(";")
			listEmail=arrayEmail.join(";")

			$('#cc-name').val(listName);
			$('#cc-email').val(listEmail);
		});




	});

</script>

<?php
require '../view/_footer-bt.php';
?>