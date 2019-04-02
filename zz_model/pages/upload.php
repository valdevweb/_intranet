<?php

$uploadDir='D:\scapsav\intersite\\';
$authorizedFiles=[
	'jpg',
	'jpeg',
	'tiff',
	'png',
	'gif',
	'pdf'
];

if(isset($_FILES['facture']['name']) && !empty($_FILES['facture']['name']))
		{
			$maxFileSize = 3 * 1024 * 1024;
			if($_FILES['facture']['size'] > $maxFileSize)
			{
				$errors[] = 'Attention un des fichiers dépasse la taille autorisée de 3 Mo';
			}
			else
			{
				$filename=$_FILES['facture']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if(!in_array($ext,$authorizedFiles))
				{
					$errors[]='Les fichiers de type "'.$ext .' ne sont pas autorisés. Veuillez joindre un fichier image ou un pdf';
				}
				else
				{
					$filename_without_ext = basename($filename, '.'.$ext);
					// Generate new filename => ajout d'un timestamp au nom du fichier
					$filename = str_replace(' ', '_', $filename_without_ext) . '_' . time() . '.' . $ext;
					$pj=$filename;

					$uploaded=move_uploaded_file($_FILES['facture']['tmp_name'],$uploadDir.$filename );
					if($uploaded==false)
					{
						$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée";
					}
				}



			}
		}

?>
<form method="post" enctype="multipart/form-data">

	<label for='incfile'>Ajouter une pièce jointe : </label><input type='file' class='form-control-file' id='incfile' name='incfile' >
	<p id="p-add-more"><a id="add_more" href="#file-upload"><i class="fa fa-plus-circle" aria-hidden="true"></i>Envoyer d'autres fichiers</a></p>

</form>



<script type="text/javascript">

	//selection du bouton pour ajouter des input type file
	$('#add_more').click(function(){
		//compte le nombre d'input file
		var current_count=$('input[type="file"]').length;
		var next_count=current_count+1;
		$('#p-add-more').prepend('<p><input type="file" name="file_' +next_count +' "></p>');
		$('input[type="file"]').val('hello');
	});



	$(document).ready(function (){
		$('form').submit(function()
		{
			$(":submit").text("Merci de patienter...")
			$("#submit").attr('disabled', true);

		});

	});
</script>



voir dans lititge declaration-detail  pour cas ou plusieurs champ d'upload de fichiers

pour un multiple :



<form method="post" enctype="multipart/form-data">

	<div id="file-upload">
		<fieldset>
			<p class="heavy pt-2">Pièces jointes :</p>
			<div class="form-group">
				<p><input type="file" name="form_file[]" class='form-control-file' multiple=""></p>
			</div>
		</fieldset>
	</div>
	<div id="filelist"></div>




	<script type="text/javascript">

		$(document).ready(function (){


			var fileName='';
			var fileList='';
			$('input[type="file"]').change(function(e){
				var nbFiles=e.target.files.length;
			// var inputFileId=e.target.id;
			// lastStrg=inputFileId.length;
			// inputFileId=inputFileId.substring(9,lastStrg);
			// inputFileId="#"+inputFileId;
			// console.log(inputFileId);
			for (var i = 0; i < nbFiles; i++)
			{
            // var fileName = e.target.files[0].name;
            fileName=e.target.files[i].name;
            console.log(fileList)
            fileList += fileName + ' - ';
        }
        // console.log(fileList);
        titre='<p><span class="heavy">Fichier(s) : </span>'
        end='</p>';
        all=titre+fileList+end;
        $('#filelist').append(all);
        fileList="";
    });


		});

	</script>

