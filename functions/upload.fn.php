<?php

//vérfie si on a des fichiers à uploader
// on peut avaoir mis un fichier dans le input file_2 et pas dans le file_1
// donc on ne peut pas se contenter de empty($_FILES['file_1']['name'][0]) pour vérfier si fichier à uploader ou non
function isFileToUpload()
{
	//si nbfile est à 0, on n'a pas de fichier, si on trouve un fichier, on incrémente
	$nbFile=0;
	// pour chaque input file, on vérfie si on a une valeur dans le name
	foreach ($_FILES as $file)
	{
		if(!empty($file['name']))
		{
			$nbFile++;
		}
	}
	if($nbFile!==0){
		return true;
	}
	else
	{
		return false;
	}

}


// formatage du tableau pour multi upload => plus utilisé avec la solution du multi input
function formatArray($file)
{
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);

    for($i=0;$i<$file_count;$i++)
    {
        foreach($file_key as $val)
        {
            $file_ary[$i][$val] = $file[$val][$i];
        }
    }
    return $file_ary;
}

// upload le fichier si mime ok
function checkUploadNew($location, $pdoBt)
{
	$filenameArray=array();
	foreach ($_FILES as $key => $file)
	{


		$filename=$file['name'];
		$tmp=explode('.',$filename);
		$ext=end($tmp);
		$filename= md5(time()).$key.'.'.$ext;
		$tmp=$file['tmp_name'];
		$error=$file['error'];
		$size=$file['size'];
		$type=$file['type'];

	// si le déplacement du fichier tmp vers le rep d'upload ok
		if(move_uploaded_file($tmp, $location.$filename))
		{
			array_push($filenameArray,$filename);
		}
	}
	return $filenameArray;
}


// upload le fichier si mime ok
function uploadFileNoHash($location)
{
	$filenameArray=array();
	foreach ($_FILES as $key => $file)
	{


		$filename=$file['name'];
		$tmp=explode('.',$filename);
		$ext=end($tmp);
		$tmp=$file['tmp_name'];
		$error=$file['error'];
		$size=$file['size'];
		$type=$file['type'];

	// si le déplacement du fichier tmp vers le rep d'upload ok
		if(move_uploaded_file($tmp, $location.$filename))
		{
			array_push($filenameArray,$filename);
		}
	}
	return $filenameArray;
}

function uploadFileAddDate($location)
{
	$filenameArray=array();
	foreach ($_FILES as $key => $file)
	{


		$filename=$file['name'];
		$tmp=explode('.',$filename);
		$ext=end($tmp);
		$tmp=$file['tmp_name'];
		$filenameDate=date("Y-m-d")." " .$filename;
		$error=$file['error'];
		$size=$file['size'];
		$type=$file['type'];

	// si le déplacement du fichier tmp vers le rep d'upload ok
		if(move_uploaded_file($tmp, $location.$filenameDate))
		{
			array_push($filenameArray,$filenameDate);
		}
	}
	return $filenameArray;
}



//upload le fichier si mime ok
// function checkUploadSameFilename($location)
// {
// 	$filenameArray=array();
// 	foreach ($_FILES as $key => $file)
// 	{


// 		$filename=$file['name'];
// 		$tmp=explode('.',$filename);
// 		$ext=end($tmp);
// 		$tmp=$file['tmp_name'];
// 		$error=$file['error'];
// 		$size=$file['size'];
// 		$type=$file['type'];
// 	// si le déplacement du fichier tmp vers le rep d'upload ok
// 		if(move_uploaded_file($tmp, $location.$filename))
// 		{
// 			array_push($filenameArray,$filename);
// 		}
// 	}
// 	return $filenameArray;
// }

// renvoi un array : 0=ok ou interdit 1=type de fichier
function isAllowed($tmp, $encoding=true)
{
    //$mime=false;
    $returnArray=array();
		if (function_exists('finfo_file'))
		{
			$finfo = finfo_open(FILEINFO_MIME);
			$mime = finfo_file($finfo, $tmp);
			finfo_close($finfo);
		}
		else if (substr(PHP_OS, 0, 3) == 'WIN')
		{
			$mime = mime_content_type($tmp);
		}
		else
		{
			$file = escapeshellarg($tmp);
			$cmd = "file -iL $tmp";

			exec($cmd, $output, $r);

			if ($r == 0)
			{
				$mime = substr($output[0], strpos($output[0], ': ')+2);
			}
		}

		if (!$mime)
		{
			return false;
		}
		elseif ($encoding)
		{
			$mime= substr($mime, 0, strpos($mime, '; '));
    	//$mime=$mime;
		}
		else
		{
			$mime=$mime;
		}
//$mime récupéré, on interroge la fonction allowed et renvoi true ou false
		if(!fileList($mime))
		{
			array_push($returnArray, 'interdit');
			array_push($returnArray, $mime);
			return $returnArray;
			// return false;
		}
		else
		{
			array_push($returnArray, 'ok');
			array_push($returnArray, $mime);
			return $returnArray;
		}

}

function fileList($mime)
{
	$whiteList=array(
		'image/jpeg',
		"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		"application/vnd.ms-excel",
		"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		"application/vnd.ms-powerpoint" ,
		"application/vnd.openxmlformats-officedocument.presentationml.presentation",
		"application/pdf",
		"image/png",
		"application/vnd.ms-office",
		"application/msword",
		"application/xml"
	);
	return (in_array($mime,$whiteList)) ? TRUE : FALSE ;
}

//non utilisé
$mimeArray=array(
'.aac'=>'audio/aac',
'.abw'=>'application/x-abiword',
'.arc'=>'application/octet-stream',
'.avi'=>'video/x-msvideo',
'.azw'=>'application/vnd.amazon.ebook',
'.bin'=>'application/octet-stream',
'.bz'=>'application/x-bzip',
'.bz2'=>'application/x-bzip2',
'.csh'=>'application/x-csh',
'.css'=>'text/css',
'.csv'=>'text/csv',
'.doc'=>'application/msword',
'.epub'=>'application/epub+zip',
'.gif'=>'image/gif',
'.htm'=>'text/html',
'.html'=>'text/html',
'.ico'=>'image/x-icon',
'.ics'=>'text/calendar',
'.jar'=>'application/java-archive',
'.jpeg'=>'image/jpeg',
'.jpg'=>'image/jpeg',
'.js'=>'application/js',
'.json'=>'application/json',
'.mid'=>'audio/midi',
'.midi'=>'audio/midi',
'.mpeg'=>'video/mpeg',
'.mpkg'=>'application/vnd.apple.installer+xml',
'.odp'=>'application/vnd.oasis.opendocument.presentation',
'.ods'=>'application/vnd.oasis.opendocument.spreadsheet',
'.odt'=>'application/vnd.oasis.opendocument.text',
'.oga'=>'audio/ogg',
'.ogv'=>'video/ogg',
'.ogx'=>'application/ogg',
'.pdf'=>'application/pdf',
'.ppt'=>'application/vnd.ms-powerpoint',
'.rar'=>'application/x-rar-compressed',
'.rtf'=>'application/rtf',
'.sh'=>'application/x-sh',
'.svg'=>'image/svg+xml',
'.swf'=>'application/x-shockwave-flash',
'.tar'=>'application/x-tar',
'.tif'=>'image/tiff',
'.tiff'=>'image/tiff',
'.ttf'=>'application/x-font-ttf',
'.vsd'=>'application/vnd.visio',
'.wav'=>'audio/x-wav',
'.weba'=>'audio/webm',
'.webm'=>'video/webm',
'.webp'=>'image/webp',
'.woff'=>'application/x-font-woff',
'.xhtml'=>'application/xhtml+xml',
'.xls'=>'application/vnd.ms-excel',
'.xml'=>'application/xml',
'.xul'=>'application/vnd.mozilla.xul+xml',
'.zip'=>'application/zip',
'.7z'=>'application/x-7z-compressed'


);