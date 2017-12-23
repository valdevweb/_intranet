<?php



function upload($file,$uploadDirectory)
{
		$fileInfo = new SplFileInfo($_FILES[$file]['name']);
		$newFile = $_FILES[$file]['name'];
		if (move_uploaded_file($_FILES[$file]['tmp_name'], $uploadDirectory.$newFile))
			{
			 // header('Location: gazette.php?type=success');
			 // $_GET['type']="success";
			  return "REUSSI". $uploadDirectory .$_FILES[$file]['name'];
		  	}
		  	else
		  	{
		  		return "echec" . $uploadDirectory . $_FILES[$file]['name'];
		  	}

}



