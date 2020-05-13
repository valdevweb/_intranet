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
require('../../Class/OccMsgManager.php');
require('../../Class/UserHelpers.php');


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
$msgManager=new OccMsgManager($pdoBt);
$listMsg=$msgManager->getListMsg(['statut=0']);

// echo "<pre>";
// print_r($listMsg);
// echo '</pre>';

foreach ($listMsg as $key => $msg) {

	$galec= UserHelpers::getMagInfo($pdoUser, $pdoMag, $msg['id_web_user'],'deno_sca');
	// echo "<pre>";
	// print_r($galec);
	// echo '</pre>';

	# code...
}




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


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
			<h2 class="text-main-blue"> Demandes magasins en cours</h2>
		</div>
	</div>
	<div class="row">
		<div class="col">

			<table class="table">
				<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Magasin</th>
						<th>Objet</th>
						<th>Date</th>
						<th>Nb de messages</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col">
			Images Disponibles :<br>
		</div>
	</div>
	<div class="row">
		<div class="col" id="msg">

		</div>
	</div>
	<div class="row">
		<div class="col">
			<input type="file" name="image" id="img" >
		</div>
	</div>
	<div class="row" id="galerie">
	</div>
	<div class="row">
		<div class="col">
			<button onClick="execCmd('bold')"><i class="fas fa-bold"></i></button>
			<button onClick="execCmd('italic')"><i class="fas fa-italic"></i></button>
			<button onClick="execCmd('underline')"><i class="fas fa-underline"></i></button>

			<button onClick="execCmd('justifyLeft')"><i class="fas fa-align-left"></i></button>
			<button onClick="execCmd('justifyCenter')"><i class="fas fa-align-center"></i></button>
			<button onClick="execCmd('justifyRight')"><i class="fas fa-align-right"></i></button>
			<button onClick="execCmd('justifyFull')"><i class="fas fa-align-justify"></i></button>
			<button onClick="execCmd('indent')"><i class="fas fa-indent"></i></button>
			<button onClick="execCmd('oudent')"><i class="fas fa-outdent"></i></button>
			<button onClick="execCmd('undo')"><i class="fas fa-undo"></i></button>
			<button onClick="execCmd('redo')"><i class="fas fa-redo"></i></button>
			<button onClick="execCmd('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
			<button onClick="execCmd('insertOrderedList')"><i class="fas fa-list-ol"></i></button>

			<select onchange="execCommandWithArg('formatBlock',this.value);">
				<option value="H1">Titre 1</option>
				<option value="H2">Titre 2</option>
				<option value="H3">Titre 3</option>
				<option value="H4">Titre 4</option>
				<option value="H5">Titre 5</option>
				<option value="H6">Titre 6</option>
			</select>
			<button onClick="execCmd('insertHorizontalRule')">HR</button>
			<button onClick="execCommandWithArg('createLink',prompt('Copiez l'adresse de l'image précédemment sélectionnée','' ))"><i class="fas fa-link"></i></button>
			<button onClick="execCmd('unlink')"><i class="fas fa-unlink"></i></button>
			<button onClick="toggleSource()"><i class="fas fa-code"></i></button>
			<button onClick="toggleEdit()">Tester</button>
			<select onchange="execCommandWithArg('fontSize',this.value);">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">Défaut</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select>
			Texte : <input type="color" name="" onchange="execCommandWithArg('foreColor',this.value);">
			Fond <input type="color" name="" onchange="execCommandWithArg('hiliteColor',this.value);">


			<button onClick="execCommandWithArg('insertImage',prompt('Entrer l adresse de l\'image','http://'))"><i class="fas fa-file-image"></i></button>
			<button onClick="execCmd('selectAll')">Select all</button>


		</div>
	</div>

	<div class="row">
		<div class="col">
			<iframe name="richText" style="width: 1000px; height:500px"></iframe>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<button class="btn btn-orange" name="save" id="save">Save</button>
		</div>
	</div>

	<div class="hide"></div>

	<!-- ./container -->
</div>

<script type="text/javascript">
	var showingSourceCode=false;
	var isInEditMode=true;
	var iframeCopy=document.getElementById('iframe-copy');
	window.onload = function() {
		richText.document.designMode='on';

	};
	function execCmd(command){
		richText.document.execCommand(command, false, null);
	}
	function execCommandWithArg(command, arg){
		richText.document.execCommand(command, false, arg);
	}
	function toggleSource(){
		if(showingSourceCode){
			richText.document.getElementsByTagName('body')[0].innerHTML=richText.document.getElementsByTagName('body')[0].textContent;
			showingSourceCode=false;
		}else{
			richText.document.getElementsByTagName('body')[0].textContent=richText.document.getElementsByTagName('body')[0].innerHTML;

			showingSourceCode=true;
		}
	}
	function toggleEdit(){
		if(isInEditMode){
			richText.document.designMode='off';
			isInEditMode=false;
		}else{
			richText.document.designMode='on';

			isInEditMode=true;

		}
	}









	$(document).ready(function(){


		// if ($('#galerie').text().length == 0 ) {
		// 	$('#msg').append("<div class='alert alert-danger'>Vous n'avez ajouté aucune image</div>");
		// }else{
		// 	$('#msg').append("<div class='alert alert-danger'>Vous n'avez ajouté aucune image</div>");

		// }


		$( "#galerie" ).click(function( event ) {

			imageAdress=event.target.src;
			var dummy = $('<input>').val(imageAdress).appendTo('.hide').select()
			document.execCommand("copy");
        	// $( "#log" ).html( "clicked: " + event.target.nodeName );
        });


		$(document).on("change", "#img", function(){
			var filename = document.getElementById("img").files[0].name;
			var file=document.getElementById("img").files[0];
			var fd = new FormData();
			var ext = filename.split('.').pop().toLowerCase();
			if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
			{
				alert("Type de fichier non supporté");
			}

			var oFReader = new FileReader();
			oFReader.readAsDataURL(file);

			var fsize = file.size||file.fileSize;
			if(fsize > 2000000){
				alert("Fichier trop lourd");
			}
			else
			{

				fd.append("file", file);

				fd.append("submit", true);

				$.ajax({
					url:"ajax-upload-img.php",
					type:"POST",
					data: fd,
					contentType: false,
					processData: false,
					dataType:"JSON",
        				// beforeSend:function(data){
        				// 	$('#galerie').html("<label class='text-success'>Upload en cours...</label>");
        				// },
        				success:function(data)
        				{
        					console.log(data);
        					if(data.success){
                            //Successfully uploaded
                            var img = document.createElement("img");
                            console.log(data);
                            img.setAttribute("src", data.path);
                            console.log(data.path);


                            $("#galerie").append(img);
                        }
                    }
                });
			}
		});


		$('#save').on('click',function(){

			var iframeContent = richText.document.getElementsByTagName('body')[0].innerHTML;
			console.log(iframeContent);
			if(iframeContent){
				$.ajax({
					type:'POST',
					url:'ajax-saveas-html.php',
					data:'iframe='+iframeContent,
					success:function(html){
					// $('#mag').append(html);
				}
			});
			}

		});


	});


</script>

<?php
require '../view/_footer-bt.php';
?>