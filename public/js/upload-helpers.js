	function getReadableFileSizeString(fileSizeInBytes) {
		var i = -1;
		var byteUnits = [' ko', ' Mo', ' Go'];
		do {
			fileSizeInBytes = fileSizeInBytes / 1024;
			i++;
		} while (fileSizeInBytes > 1024);

		return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
	}

	function multipleWithName(htmlInputFile,warningZone, formZone){
		if (!window.FileReader) {
			console.log("The file API isn't supported on this browser yet.");
			return;
		}

		var inputFile = document.getElementById(htmlInputFile);
		var zoneAvertissement=document.getElementById(warningZone);
		var formNom = document.getElementById(formZone);
		zoneAvertissement.innerHTML="";
		formNom.innerHTML="";
		if (inputFile.files) {
			console.log("ici");
			var totalSize=0;
			var fileList='';
			var filearray = inputFile.files;
			var nbFiles=filearray.length;
			for (var i = 0; i < nbFiles; ++i) {
				file=filearray[i];
				console.log("File " + file.name + " is " + file.size + " bytes in size" );
				var fileSize=file.size;
				var fileName=file.name;
				totalSize = totalSize+fileSize;
				fileList += fileName +'<br>';
				var label=document.createElement("label");
				label.innerHTML="Nom du fichier " +fileName+ ' : ';

				var input=document.createElement("input");

				var name="filename[" +i +"]";
				input.setAttribute('type', 'text');
				input.setAttribute('name', name);
				input.setAttribute('value', fileName);
				input.classList.add('form-control');

    		// var input="<input type='text' class='form-control form-primary'  name='filename[" +i +"]'>";
    		// formNom.append(titreNommer+fileName+formGroup+input+endDiv);
    		formNom.append(label);

    		formNom.append(input);

    	}

    	if(totalSize <= 52428800){
    		totalFileSize=document.createElement("span");
    		totalFileSize.classList.add('text-success');
    		totalFileSize.innerHTML="Taille totale : "+ getReadableFileSizeString(totalSize);
    		zoneAvertissement.append(totalFileSize);
    	}

    	if(totalSize > 52428800){
    		totalFileSize=document.createElement("span");
    		totalFileSize.classList.add('text-danger');
    		totalFileSize.innerHTML="Taille totale : "+ getReadableFileSizeString(totalSize)+".<br>La taille est limitée à 50Mo, vos documents ne pourront pas être enregistrés";
    		zoneAvertissement.append(totalFileSize);
    	}
    	fileList="";
    }
}

function noRename(htmlInputFile,warningZone, formZone){
	if (!window.FileReader) {
		console.log("The file API isn't supported on this browser yet.");
		return;
	}
	var fileExtension = [ 'xls', 'xlsx'];
	var inputFile = document.getElementById(htmlInputFile);
	var zoneAvertissement=document.getElementById(warningZone);
	var formNom = document.getElementById(formZone);
	zoneAvertissement.innerHTML="";
	formNom.innerHTML="";
	if (inputFile.files) {
		console.log("ici");
		var totalSize=0;
		var fileList='';
		var filearray = inputFile.files;
		var nbFiles=filearray.length;
		for (var i = 0; i < nbFiles; ++i) {
			file=filearray[i];
			console.log("File " + file.name + " is " + file.size + " bytes in size" );
			var fileSize=file.size;
			var fileName=file.name;
			totalSize = totalSize+fileSize;
			fileList += fileName +'<br>';
			var label="Fichier : " +fileName;
			var extension=fileName.replace(/^.*\./, '');
			var span=document.createElement("span");
			if ($.inArray(extension, fileExtension)==-1) {
				span.innerHTML="<i class='fas fa-times pl-5 pr- 2 text-danger'></i> Fichier non autorisé";
				console.log("interdit");
			}else{
				span.innerHTML="<i class='fas fa-check pl-5 pr-2 text-success'></i>Fichier autorisé";
				console.log("autorisé");

			}
			formNom.append(label);
			formNom.append(span);

		}

		if(totalSize <= 52428800){
			totalFileSize=document.createElement("span");
			totalFileSize.classList.add('text-success');
			totalFileSize.innerHTML="Taille totale : "+ getReadableFileSizeString(totalSize);
			zoneAvertissement.append(totalFileSize);
		}

		if(totalSize > 52428800){
			totalFileSize=document.createElement("span");
			totalFileSize.classList.add('text-danger');
			totalFileSize.innerHTML="Taille totale : "+ getReadableFileSizeString(totalSize)+".<br>La taille est limitée à 50Mo, vos documents ne pourront pas être enregistrés";
			zoneAvertissement.append(totalFileSize);
		}
		fileList="";
	}
}