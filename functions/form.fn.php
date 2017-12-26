<?php
/**
 * initForm interroge table services pour recup id gt, descr et nom complet
 * @param  obj $db bdd
 * @param  string $gt $_get nom du gt
 * @return array    id du gt
 */
function initForm($db){
	$req=$db->prepare('SELECT * FROM services WHERE slug = :gt');
	$req->execute(array(
		':gt' =>$_GET['gt']
	));
	return $row=$req->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * [getNames interroge db users pour recup nom prenom]
 * @param  obj $db    [description]
 * @param  int $id_gt [num gt]
 * @return [array]   [all]
 */
function getNames($db,$idgt)
{
	$req=$db->prepare('SELECT* FROM btlec WHERE id_service= :id_gt ORDER BY resp DESC');
	$req ->execute(array(
		':id_gt' =>$idgt
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

function addMsg($db,$id_service,$inc_file)
{
	$req=$db->prepare('INSERT INTO msg (objet, msg, id_mag, id_service, date_msg, etat,inc_file,who,email)
		VALUE(:objet, :msg, :id_mag, :id_service, :date_msg, :etat, :inc_file, :who, :email)');
	$req->execute(array(
		':objet'		=> strip_tags($_POST['objet']),
		':msg'			=> strip_tags($_POST['msg']),
		':id_mag'		=> strip_tags($_SESSION['id']),
		':id_service'	=> $id_service,
		':date_msg'		=>date('Y-m-d H:i:s'),
		':etat'			=> "nouveau",
		':inc_file'		=>$inc_file,
		':who'			=>strip_tags($_POST['name']),
		':email'		=>strip_tags($_POST['email']),
	));
	$req->fetch(PDO::FETCH_ASSOC);
	return $db->lastInsertId();
}

//ajout gestion multimessage => requete sur table msg et table replies
function showAllMsg($db){
	$req=$db->prepare("SELECT * FROM msg l LEFT JOIN replies r ON l.id = r.id_msg WHERE id_mag= :id_mag ORDER BY date_msg DESC ");
	$req->execute(array(
		':id_mag'	=>$_SESSION['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}


function showThisMsg($pdoBt, $idMag, $idMsg){
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id_mag= :idMag AND id= :idMsg ");
	$req->execute(array(
		':idMag'	=>$idMag,
		':idMsg'	=>$idMsg
	));

	return $req->fetch();
}

function showThisReply($pdoBt, $idMag, $idMsg){
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id_mag= :idMag AND id= :idMsg ");
	$req->execute(array(
		':idMag'	=>$idMag,
		':idMsg'	=>$idMsg
	));

	return $req->fetch();
}

function whoReplied($pdoBt,$iduser){
	$req=$pdoBt->prepare("SELECT nom, prenom FROM btlec WHERE id= :id");
	$req->execute(array(
		':id'	=>$iduser
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function back($pdoBt, $idMag, $idMsg){
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id_mag= :idMag AND id= :idMsg ");
	$req->execute(array(
		':idMag'	=>$idMag,
		':idMsg'	=>$idMsg
	));

	return $req->fetch();
}


function service($pdoBt,$idService){
$req=$pdoBt->prepare("SELECT * FROM services WHERE id = :id");
	$req->execute(array(
		':id'	=>$idService,
	));

	return $req->fetch(PDO::FETCH_ASSOC);

}


//$msg=array();

// upload le fichier si mime ok
function checkUpload($upload, $location, $pdoBt)
{
	// $renamed = md5($filename. time());        #rename of the file
// if (!@move_uploaded_file($_FILES[$uploadfile]['tmp_name'], $save_path.$renamed. $extension))
	$name=$upload['name'];
	$ext=end(explode('.',$name));
	$md5= md5(time()).'.'.$ext;
	$tmp=$upload['tmp_name'];
	$error=$upload['error'];
	$size=$upload['size'];
	$type=$upload['type'];
	//si type de fichier non autorisé
	if (!mime($tmp))
	{
		$msg=array('err'=>  "il est interdit d'envoyer ce type de fichier");
		return $msg;
		exit;
	}
	// si le déplacement du fichier tmp vers le rep d'upload ok
	if(move_uploaded_file($tmp, $location.$md5))
	{

		$msg=array('success' =>$md5);
	}
	else
	{
		$msg=array(	'err' =>'erreur pendant l\'envoi');
	}
return $msg;
}

function mime($tmp, $encoding=true)
{
    //$mime=false;

    if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);
    }
    else if (substr(PHP_OS, 0, 3) == 'WIN') {
        $mime = mime_content_type($tmp);
    }
    else {
        $file = escapeshellarg($tmp);
        $cmd = "file -iL $tmp";

        exec($cmd, $output, $r);

        if ($r == 0) {
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
	//	"application/pdf",
		"image/png"
	);
return (in_array($mime,$whiteList)) ? TRUE : FALSE ;



}




// $filename = $_FILES[$uploadfile]['name'];
// $save_path = '/var/domainame/uploads/'; # Outside of web root
// $extension = end(explode('.', $filename)); #extension of the file
// $renamed = md5($filename. time());        #rename of the file
// if (!@move_uploaded_file($_FILES[$uploadfile]['tmp_name'], $save_path.$renamed. $extension))
// {
//     echo 'File could not be saved.';
//     exit(0);
// }


// fonction qui récupère toutes les demandes mag par pour un service
//
//