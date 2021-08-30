
function convertSize(size) {
	var sizes = ['Bytes', 'Ko', 'Mo', 'Go', 'To'];
	if (size == 0) return '0 Byte';
	var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
	return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
// function uploadData(formdata, ajaxFile, id, readablename, order){
// 	$.ajax({
// 		url: ajaxFile,
// 		type: 'post',
// 		data: formdata,
// 		contentType: false,
// 		processData: false,
// 		dataType: 'json',
// 		success: function(response){
// 			if(!readablename){
// 				if (!order) {
// 					addThumbnail(response, id);
// 				}else{
// 					addThumbnailAndOrder(response, id);
// 				}
// 			}else{
// 				if (!order) {
// 					console.log("order est faux");
// 					addThumbnailAndName(response, id);
// 				}else{
// 					addThumbnailAndNameAndOrder(response, id);

// 				}

// 			}
// 		}
// 	});

// }

function uploadData(formdata, ajaxFile, id, readablename, order){
	$.ajax({
		url: ajaxFile,
		type: 'post',
		data: formdata,
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(response){
			addThumbnail(response, id ,readablename, order);
		}
	});

}


function addThumbnail(data, id, readablename, order){

	$(id + " .uploadfile p").remove();
	var len = $(id + " .uploadfile div.thumbnail").length;
	var idName= id.replace("#", "");
	var num = Number(len);
	// num = num + 1;


	for (var i = 0; i < data.length; i++) {
		var name = data[i].name;
		var uploadFilename = data[i].upload_filename;
		var size = convertSize(data[i].size);
		var src = data[i].src;
		numThumbnail=num+i;
		$(id + " .uploadfile").append('<div id="thumbnail'+idName+'_'+numThumbnail+'" class="thumbnail col"></div>');
		$("#thumbnail"+idName+"_"+numThumbnail).append('<img src="'+src+'" width="80px" height="auto">');
		$("#thumbnail"+idName+"_"+numThumbnail).append('<div class="size">'+name+' ('+size+')</div>');

		$(id+ " .filename").append('<input type="hidden" name="file_'+idName+'['+numThumbnail+']" value="'+uploadFilename+'">');
		if (readablename && order) {
			console.log("nommage et ordre");
			var readable='<div class="row"><div class="col-8"><div class="form-group"><label>Nom pour le fichier '+name+'</label><input type="text" class="form-control"name="readable_'+idName+'['+numThumbnail+']"  required value="'+name+'" ></div></div>';
			var order='<div class="col"><div class="form-group"><label>Ordre :</label><input type="text" class="form-control" required name="ordre_'+idName+'['+numThumbnail+']" value="'+numThumbnail+'"></div></div></div>';

			$(id+ " .readablename").append(readable+order);
		}
		if (readablename && !order) {
			console.log("nommage");
			$(id+ " .readablename").append('<div class="form-group"><label for="model">Nom pour le fichier '+name+'</label><input type="text" class="form-control" name="readable_'+idName+'['+numThumbnail+']" value="'+name+'"></div>');

		}
		if (!readablename && order) {
			var order='<div class="row"><div class="col"><div class="form-group"><label>Ordre du fichier '+name+' :</label><input type="text" class="form-control" required name="ordre_'+idName+'['+numThumbnail+']" value="'+numThumbnail+'"></div></div></div>';

			$(id+ " .readablename").append(order);
			console.log("ordre");

		}

	}

}