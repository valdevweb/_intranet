<?php
function checkUpload($upload, $location,$category,$date, $pdoBt)
{
	$msg=array();
	$name=$upload['name'];
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
	if(move_uploaded_file($tmp, $location.$name))
	{
		insertIntoDb($pdoBt,$name, $category, $date);
		// reset form pour éviter multi renvoi :
		unset($_FILE, $_POST);

		$msg=array('success' =>$name);
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

//ajout dans table gazette
function insertIntoDb($pdoBt,$name, $category, $date)
{
		$req=$pdoBt->prepare('INSERT INTO gazette (file, date, category)
		VALUE(:file, :date, :category)');
		$req->execute(array(
		':file'		=> $name,
		':date'			=> $date,
		':category'		=> $category
	));
}


//affichage de l'histo des gazettes si besoin supression sur page upload
function histoGazetteUpload($pdoBt,$category)
{
	$req=$pdoBt->prepare("SELECT date, id,file,category, week(date) as week, year(date) as year FROM gazette WHERE category= :category ORDER BY date DESC LIMIT 10 ");
	$req->execute(array(
		':category' =>$category
	));
return $req->fetchAll(PDO::FETCH_ASSOC);
}

function deleteGaz($pdoBt,$id){
	$where = ['id' => $id];
	$pdoBt->prepare("DELETE FROM gazette WHERE id=:id")->execute($where);
}