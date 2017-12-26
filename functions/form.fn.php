<?php
//-------------------------------------------------------------------------------------
//					function d'affichage utilisées sur la page contact mag
//-------------------------------------------------------------------------------------


function initForm($db){
	$req=$db->prepare('SELECT * FROM services WHERE slug = :gt');
	$req->execute(array(
		':gt' =>$_GET['gt']
	));
	return $row=$req->fetchAll(PDO::FETCH_ASSOC);
}


function getNames($db,$idgt)
{
	$req=$db->prepare('SELECT* FROM btlec WHERE id_service= :id_gt ORDER BY resp DESC');
	$req ->execute(array(
		':id_gt' =>$idgt
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

//-------------------------------------------------------------------------------------
//					ajout de demande mag - form page contact
//-------------------------------------------------------------------------------------

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
		':etat'			=> "en attente de réponse",
		':inc_file'		=>$inc_file,
		':who'			=>strip_tags($_POST['name']),
		':email'		=>strip_tags($_POST['email']),
	));
	$req->fetch(PDO::FETCH_ASSOC);
	return $db->lastInsertId();
}

//-------------------------------------------------------------------------------------
//					page histo mag - ? msg et replies
//-------------------------------------------------------------------------------------


//liste tous les messages d'un magasin (page histo, en affichage que la date de la dernière réponse)
function listAllMsg($pdoBt)
{
	$data=array();
	$req=$pdoBt->prepare("SELECT id FROM msg WHERE id_mag= :id_mag");
	$req->execute(array(
		':id_mag'	=>$_SESSION['id'],

	));

	if($idExist=$req->fetchAll(PDO::FETCH_COLUMN))
		{
			foreach ($idExist as $key => $value) {
				$req=$pdoBt->prepare("SELECT table_msg.id AS msg_id, objet, msg, id_service, date_msg, table_msg.etat, table_replies.replied_by, max(table_replies.date_reply), table_replies.id AS reply_id  FROM msg table_msg LEFT JOIN replies table_replies ON table_msg.id = table_replies.id_msg WHERE table_replies.id_msg= :idMsg");
				$req->execute(array(
					':idMsg'	=>$value

				));
				// $data=$req->fetch(PDO::FETCH_ASSOC)
				array_push($data,$req->fetch(PDO::FETCH_ASSOC));

			}
			return $data;

		}

	}

// tri un tableau multi type pdo => ut pour affichga histo dde mag
function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}
//affichage nom du service en clair (histo mag)

function service($pdoBt,$idService){
$req=$pdoBt->prepare("SELECT * FROM services WHERE id = :id");
	$req->execute(array(
		':id'	=>$idService,
	));

	return $req->fetch(PDO::FETCH_ASSOC);

}
//affichage nom personne qui a répondu en clair (histo mag)
function repliedByIntoName($pdoBt,$idUser)
{
	$req=$pdoBt->prepare("SELECT CONCAT( nom ,' ', prenom)AS fullname FROM btlec JOIN lk_user ON lk_user.id_btlec=btlec.id WHERE lk_user.iduser = :iduser");
	$req->execute(array(
		'iduser' =>$idUser
	));

	$fullName=$req->fetch();
	$fullName=$fullName['fullname'];
	return $fullName;
}



//-------------------------------------------------------------------------------------
//
//-------------------------------------------------------------------------------------



function showThisMsg($pdoBt, $idMag, $idMsg){
	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id_mag= :idMag AND id= :idMsg ");
	$req->execute(array(
		':idMag'	=>$idMag,
		':idMsg'	=>$idMsg
	));

	return $req->fetch();
}





function showReplies($pdoBt,$idMsg){
	$req=$pdoBt->prepare("SELECT * FROM replies WHERE id_msg= :idMsg ORDER BY date_reply DESC");
	$req->execute(array(
		':idMsg'	=>$idMsg
	));

	return $req->fetchAll(PDO::FETCH_ASSOC);
}

// function whoReplied($pdoBt,$iduser){
// 	$req=$pdoBt->prepare("SELECT nom, prenom FROM btlec WHERE id= :id");
// 	$req->execute(array(
// 		':id'	=>$iduser
// 	));
// 	return $req->fetch(PDO::FETCH_ASSOC);
// }

// function back($pdoBt, $idMag, $idMsg){
// 	$req=$pdoBt->prepare("SELECT * FROM msg WHERE id_mag= :idMag AND id= :idMsg ");
// 	$req->execute(array(
// 		':idMag'	=>$idMag,
// 		':idMsg'	=>$idMsg
// 	));

// 	return $req->fetch();
// }





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