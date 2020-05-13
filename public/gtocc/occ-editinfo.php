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
	<h1 class="text-main-blue py-5 ">Leclerc Occasion - saisie info magasin</h1>

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
			<h2 class="text-main-blue">Module de saisie libre</h2>
		</div>
	</div>
	<div class="row">
		<div class="col">
		<h5 class="text-main-blue">Galerie d'images</h5>
		</div>
	</div>
	<div class="alert alert-primary">
		Pour ajouter une des images de la galerie :<br>
		1- cliquer sur l'image que vous souhaitez ajouter<br>
		3- cliquez sur l'icône <i class="fas fa-file-image"></i><br>
		4- dans la boite de dialogue qui s'affiche, faites CTRL + V<br>
		Ajouter ici les images que vous souhaitez insérer dans votre texte.
	</div>
	<div class="row" id="galerie">


	</div>
		<div class="row">
			<div class="col">
				Vous pouvez ajouter des images à la galerie. Attention, ces images ne sont pas enregistrées de manière définitive, si vous quittez la page, vous ne les retrouverez pas
			</div>
		</div>
	<div class="row">

		<div class="col button-wrapper">
			<span class="label">
				Ajouter une image
			</span>
			<input type="file" name="image" id="img" >
		</div>
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
			<button onClick="execCommandWithArg('createLink',prompt('Copiez le lien','' ))"><i class="fas fa-link"></i></button>
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


			<button onClick="execCommandWithArg('insertImage',prompt('Entrer l adresse de l\'image',''))"><i class="fas fa-file-image"></i></button>
			<button onClick="execCmd('selectAll')">Select all</button>


		</div>
	</div>

	<div class="row">
		<div class="col">
			<iframe name="richText" style="width: 1000px; height:300px; font-family:Arial;"></iframe>
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
		document.richText.document.body.style.fontFamily = "Arial";

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
                            // var img = document.createElement("img");
                            // img.setAttribute("src", data.path);
                            var colStart="<div class='col-3 galerie-col'>"
                            var colEnd='</div>';
                            var img = colStart+"<img src='" + data.path + "'>"+ colEnd;


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