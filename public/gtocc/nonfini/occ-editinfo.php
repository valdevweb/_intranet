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
<div class="container-fluid bg-light">
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


	<div class="row justify-content-center ">
		<!-- <div class="col-md-1"></div> -->
		<div class="col-auto border rounded-lg px-5 py-5">
		<!-- 	<div class="row bartitle mb-3">
				<div class="col"><i class="fas fa-ellipsis-v pr-2"></i> Saisie libre</div>
			</div> -->
			<section >

				<div class="row">

					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('bold')"><i class="fas fa-bold"></i></button>
						<button class="btn btn-light" onClick="execCmd('italic')"><i class="fas fa-italic"></i></button>
						<button class="btn btn-light" onClick="execCmd('underline')"><i class="fas fa-underline"></i></button>
					</div>
					<div class="col-auto ">
						<select  class="form-control" onchange="execCommandWithArg('fontSize',this.value);">
							<option value="3">Taille par défaut</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
					</div>
					<div class="col-auto pr-2">

						<select class="form-control" onchange="execCommandWithArg('formatBlock',this.value);">
							<option value="">Titres</option>

							<option value="H1">Titre 1</option>
							<option value="H2">Titre 2</option>
							<option value="H3">Titre 3</option>
							<option value="H4">Titre 4</option>
							<option value="H5">Titre 5</option>
							<option value="H6">Titre 6</option>
						</select>
					</div>
					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('justifyLeft')"><i class="fas fa-align-left"></i></button>
						<button class="btn btn-light" onClick="execCmd('justifyCenter')"><i class="fas fa-align-center"></i></button>
						<button class="btn btn-light" onClick="execCmd('justifyRight')"><i class="fas fa-align-right"></i></button>
						<button class="btn btn-light" onClick="execCmd('justifyFull')"><i class="fas fa-align-justify"></i></button>

					</div>

					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
						<button class="btn btn-light" onClick="execCmd('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
					</div>


					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('indent')"><i class="fas fa-indent"></i></button>
						<button class="btn btn-light" onClick="execCmd('oudent')"><i class="fas fa-outdent"></i></button>
					</div>

					<div class="col-auto pr-2">
						<label class="btn btn-light">
							<i class="fas fa-file-image fa-lg"></i><input type="file" name="image" id="img" hidden>
						</label>
					</div>

					<div class="col-auto">

						<button class="btn btn-light" onClick="execCommandWithArg('createLink',prompt('Copiez le lien','lien' ))"><i class="fas fa-link"></i></button>
						<button class="btn btn-light" onClick="execCmd('unlink')"><i class="fas fa-unlink"></i></button>
					</div>
						<!-- <button class="btn btn-light" onClick="toggleSource()"><i class="fas fa-code"></i></button>
							<button class="btn btn-light" onClick="toggleEdit()">Tester</button> -->





						</div>

						<div class="row mt-3 mb-5">

							<div class="col">
								<div class="color-picker-text color-block">
									<i class="fas fa-font pl-1 pr-3"></i>
									<div class="circle" style="background : #f8f9fa; border: 2px solid #0d47a1" onclick="execCommandWithArg('foreColor','rgba(13,71,161,1)')"></div>
									<div class="circle" style="background : #f8f9fa; border: 2px solid #f18f0b" onclick="execCommandWithArg('foreColor','rgba(241,143,11,1)')"></div>
									<div class="circle" style="background : #f8f9fa; border: 2px solid black" onclick="execCommandWithArg('foreColor','rgba(0,0,0,1)')"></div>
									<div class="circle" style="background : #fff; border: 2px solid #fff;" onclick="execCommandWithArg('foreColor','rgba(255,255,255,1)')"></div>
									<div class="circle" style="background : #f8f9fa; border: 2px solid red" onclick="execCommandWithArg('foreColor','rgba(255,0,0,1)')"></div>
									<div class="circle" style="background : #f8f9fa; border: 2px solid green" onclick="execCommandWithArg('foreColor','rgba(0,128,0,1)')"></div>
									<div class="circle" style="background : #f8f9fa; border: 2px solid grey"  onclick="execCommandWithArg('foreColor','rgba(128,128,128,1)')"></div>

								</div>
								<!-- <div class="color-block"> -->
									<div class="color-block color-picker-bg">
										<i class="fas fa-fill pl-1 pr-3"></i>

										<div class="circle" style="background: #0d47a1" onclick="execCommandWithArg('hiliteColor','rgba(13,71,161,1)')"></div>
										<div class="circle" style="background: #f18f0b" onclick="execCommandWithArg('hiliteColor','rgba(241,143,11,1)')"></div>
										<div class="circle" style="background: black" onclick="execCommandWithArg('hiliteColor','rgba(0,0,0,1)')"></div>
										<div class="circle" style="background: white; border:1px solid #999" onclick="execCommandWithArg('hiliteColor','rgba(255,255,255,1)')"></div>
										<div class="circle" style="background: red" onclick="execCommandWithArg('hiliteColor','rgba(255,0,0,1)')"></div>
										<div class="circle" style="background: green" onclick="execCommandWithArg('hiliteColor','rgba(0,128,0,1)')"></div>
										<div class="circle" style="background: grey"  onclick="execCommandWithArg('hiliteColor','rgba(128,128,128,1)')"></div>
									</div>
									<!-- </div> -->
								</div>
								<div class="col">

									<label class="btn btn-upload btn-file text-center">
										<input name="upload" id="" type="file" multiple="" class="form-control-file">
										<i class="fas fa-file-image pr-3"></i>Ajouter des fichiers</label>
									<!-- <div class="upload-btn-wrapper">
										<button class="btn-upload">Ajouter un fichier</button>
										<input type="file" name="myfile" />
									</div> -->
								</div>

							</div>
							<div class="row">
								<div class="col">
									<iframe name="richText" style="width: 1200px; height:300px; font-family:Arial;" id="richText"></iframe>
								</div>
							</div>
							<div class="row justify-content-end">
								<div class="col-auto">
									<div id="preview"></div>
								</div>
								<div class="col-auto">
									<button class="btn btn-primary" name="save" id="save">Enregistrer</button>
								</div>
							</div>

						</div>
					</section>
				</div>
				<!-- <div class="col-md-1"></div> -->

			</div>
			<!-- div caché poour date parce que trop chiant en js -->
			<div class="hidden" id="phpdate" value="<?=date('YmdHis')?>"?></div>

			<!-- input caché pour nom fichier -->
			<div class="hidden" id="filename"></div>

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
                            var img = "<img src='" + data.path + "'>";



                            $('#richText').contents().find('body').append($("<img/>").attr("src", data.path).attr("title", "sometitle"));
        					// execCommandWithArg('insertImage',data.path );
							// myFrame.execCommand('insertImage', false, data.path);
                            // richText.document.getElementsByTagName('body')[0].append(img);

                        }
                    }
                });
					}
				});


				$('#save').on('click',function(){

					var iframeContent = richText.document.getElementsByTagName('body')[0].innerHTML;
					iframeContent=iframeContent.replace(/&nbsp;/gi,'');
					var filename="";
					var savedFilename=$('#filename').text();
					if(savedFilename==""){
						filename=$('#phpdate').text();
						$('#filename').text(filename)
					}else{
						filename=savedFilename;
					}
					console.log(filename);
					if(iframeContent){
						$.ajax({
							type:'POST',
							dataType : 'html',
							url:'ajax-saveas-html.php',
							data:'iframe='+iframeContent+'&filename='+filename,
							success:function(html){
								$('#preview').empty();
								$('#preview').append('<a class="btn btn-orange" href="preview.php?file='+html+'" target="_blank" id="previewlink">previsualiser</a>');
							}
						});
					}

				});

		// on fait disparaitre le btn preview dès que l'utilisateur retourne dans le iframe pour le forcer à enregistrer avant la previsualisation
		var frameBody = $("#richText").contents().find("body");
		frameBody.focus(function(e){
			$('#preview').empty();
		});

	});


</script>

<?php
require '../view/_footer-bt.php';
?>