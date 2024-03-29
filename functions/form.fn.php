<?php

// réaffichage cases à coché formulaire
function checkChecked($value,$field){
    if(isset($_POST[$field])){
        if($_POST[$field]==$value){
            return "checked";
        }
    }

    return "";
}
// réffichage liste déroulante formulaire
function checkSelected($value,$field){
    if(isset($_POST[$field])){
        if($_POST[$field]==$value){
            return "selected";
        }
    }

    return "";
}


function repliedByIntoName($pdoUser,$idUser){
   $fullName="";
	$req=$pdoUser->prepare("SELECT CONCAT( prenom ,' ', nom)AS fullname FROM intern_users WHERE id_web_user = :iduser");
	$req->execute(array(
		'iduser' =>$idUser
	));

	$fullName=$req->fetch();
    if(!empty($fullName)){
    $fullName=$fullName['fullname'];

    }
	return $fullName;
}







// upload le fichier si mime ok
// function checkUpload($location, $pdoBt)
// {


// 	$filename=$_FILES['file']['name'];
// 	$tmp=explode('.',$filename);
// 	$ext=end($tmp);
// 	$filename= md5(time()).'.'.$ext;
// 	$tmp=$_FILES['file']['tmp_name'];
// 	$error=$_FILES['file']['error'];
// 	$size=$_FILES['file']['size'];
// 	$type=$_FILES['file']['type'];

// 	// si le déplacement du fichier tmp vers le rep d'upload ok
// 	if(move_uploaded_file($tmp, $location.$filename))
// 	{

// 		$msg=array('filename' =>$filename);
// 	}
// 	else
// 	{
// 		$msg=array(	'err' =>'erreur pendant l\'envoi');
// 	}
// return $msg;
// }

function test()
{
	return $_FILES['file'];
}


function mime($tmp, $encoding=true)
{
    //$mime=false;

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
   	if(!allowed($mime))
   	{
   		return false;

   	}
   	else
   	{
   		return true;
	}
}

function allowed($mime)
{
	$whiteList=array(
		'image/jpeg',
		"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		"application/vnd.ms-excel",
		"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		"application/vnd.ms-powerpoint" ,
		"application/vnd.openxmlformats-officedocument.presentationml.presentation",
		"application/pdf",
		"image/png"
	);
return (in_array($mime,$whiteList)) ? TRUE : FALSE ;



}


