<?php

// formatage du tableau pour multi upload
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
function checkUploadNew($location,$newFileArray, $pdoBt)
{
	// $renamed = md5($filename. time());        #rename of the file
// if (!@move_uploaded_file($_FILES[$uploadfile]['tmp_name'], $save_path.$renamed. $extension))

$filenameArray=array();
foreach ($newFileArray as $key => $file)
{


	$filename=$file['name'];
	$tmp=explode('.',$filename);
	$ext=end($tmp);
	$filename= md5(time()).'.'.$ext;
	$tmp=$file['tmp_name'];
	$error=$file['error'];
	$size=$file['size'];
	$type=$file['type'];

	// si le déplacement du fichier tmp vers le rep d'upload ok
	if(move_uploaded_file($tmp, $location.$filename))
	{
		array_push($filenameArray,$filename);
		// $msg=array('filename' =>$filename);
	}
	// else
	// {
	// 	$msg=array(	'err' =>'erreur pendant l\'envoi');
	// }
}
return $filenameArray;
}



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
		"application/vnd.ms-office"
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