<?php

class Uploader
{
	private $destinationPath;
	private $errorMessage;
	private $extensions;
	private $allowAll;
	private $maxSize;
	private $uploadName;
	private $nameWithoutExt;
	public $bool;
	private $seqnence;
	public $name;
	public $useTable    =false;
	protected $multiSize;
	protected $multiName;
	protected $multiExt;


	public function setDir($path){
		$this->destinationPath  =   $path;
		$this->allowAll =   false;
	}

	public function allowAllFormats(){
		$this->allowAll =   true;
	}

	public function setMaxSize($sizeMB){
		$this->maxSize  =   $sizeMB * (1024*1024);
	}

	public function setExtensions($options){
		$this->extensions   =   $options;
	}

	public function setSameFileName($bool=null){
			if($bool===null){
				$this->bool=false;
				return false;
			}
			else
			{
				$this->bool=$bool;
				return $bool;

			}
		}
	public function getExtension($string){
		$ext    =   "";
		try{
			$parts  =   explode(".",$string);
			$ext        =   strtolower($parts[count($parts)-1]);
		}catch(Exception $c){
			$ext    =   "";
		}
		return $ext;
	}

	public function setMessage($message){
		$this->errorMessage =   $message;
	}

	public function getMessage(){
		return $this->errorMessage;
	}

	public function getUploadName(){
		return $this->uploadName;
	}
	public function setSequence($seq){
		$this->imageSeq =   $seq;
	}

	public function getRandom(){
		return strtotime(date('Y-m-d H:i:s')).rand(1111,9999).rand(11,99).rand(111,999);
	}
	public function sameName($true){
		$this->sameName =   $true;
	}
	public function uploadFile($fileBrowse){
		$result =   false;
		$size   =   $_FILES[$fileBrowse]["size"];
		$name   =   $_FILES[$fileBrowse]["name"];
		$ext    =   $this->getExtension($name);
		$extPoint='.'.$ext;
		$nameWithoutExt=explode($extPoint,$name)[0];

		if(!is_dir($this->destinationPath)){
			$this->setMessage("Le répertoire de destination n'existe pas ");
		}
		else if(!is_writable($this->destinationPath)){
			$this->setMessage("Le répertoire de destination n'autorise pas l'upload de fichiers !");
		}
		else if(empty($name)){
			$this->setMessage("Vous devez sélectionner un fichier");
		}else if($size>$this->maxSize){
			$MoMaxSize=($this->maxSize)/1024/1024;
			$this->setMessage("Le fichier dépasse la taille autorisée de $MoMaxSize Mo !");
		}else if($this->allowAll || (!$this->allowAll && in_array($ext,$this->extensions)))
		{
			if(!$this->setSameFileName($this->bool))
			{
				$this->uploadName   =  $nameWithoutExt."-".date('YmdHis').".".$ext;
			}else{
				$this->uploadName=  $name;
			}
			// $this->uploadName=$name;
			if(move_uploaded_file($_FILES[$fileBrowse]["tmp_name"],$this->destinationPath.$this->uploadName)){
				$result =   true;
			}else{
				$this->setMessage("Erreur d'upload");
			}
		}else{
			$this->setMessage("Le format de fichier $ext n'est pas autorisé");
		}
		return $result;
	}

	public function deleteUploaded(){
		unlink($this->destinationPath.$this->uploadName);
	}


// if(isset($_POST['submit']))
// {
// 	$uploader   =   new Uploader();
// 	$uploader->setDir('..\..\..\upload\litiges\\');
// 	$uploader->allowAllFormats();
// 	$uploader->setMaxSize(.5);                          //set max file size to be allowed in MB//

// 	if($uploader->uploadFile('file')){   //txtFile is the filebrowse element name //
//     $success[]  =   $uploader->getUploadName(); //get uploaded file name, renames on upload//
// 	}
// 	else{//upload failed
//     $errors[]=$uploader->getMessage(); //get upload error message
// 	}






}


?>