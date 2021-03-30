<?php


class GesapDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getListGesap(){
		$today=new DateTime();
		$today->modify("-3 day");
		$req=$this->pdo->prepare("SELECT * FROM gesap WHERE date_remonte >= :date_remonte");
		$req->execute([
			':date_remonte'		=>$today->format("Y-m-d")
		]);
		return $req->fetchAll();

	}


	public function getListFiles(){
		$today=new DateTime();
		$today->modify("-3 day");
		$req=$this->pdo->prepare("SELECT gesap_files.id_gesap, file, filename FROM gesap_files LEFT JOIN gesap ON id_gesap= gesap.id WHERE date_remonte >= :date_remonte ORDER BY ordre");
		$req->execute([
			':date_remonte'		=>$today->format("Y-m-d")
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);

	}

	public function getGesap($id){

		$req=$this->pdo->prepare("SELECT * FROM gesap WHERE id >= :id");
		$req->execute([
			':id'		=>$id
		]);
		return $req->fetch();

	}

	public function getFiles($idGesap){

		$req=$this->pdo->prepare("SELECT * FROM gesap_files WHERE id_gesap >= :id_gesap");
		$req->execute([
			':id_gesap'		=>$idGesap
		]);
		return $req->fetchAll();

	}


	public function insertGesapWithGa($file){
		$req=$this->pdo->prepare("INSERT INTO gesap ( op, salon, cata, code_op, date_remonte, ga_file, ga_num, cmt, date_insert, by_insert) VALUES (:op, :salon, :cata, :code_op, :date_remonte, :ga_file, :ga_num, :cmt, :date_insert, :by_insert)");
		$req->execute([
			':op'		=>$_POST['op'],
			':salon'		=>$_POST['salon'],
			':cata'		=>$_POST['cata'],
			':code_op'		=>$_POST['code_op'],
			':date_remonte'		=>$_POST['date_remonte'],
			':ga_file'		=>$file,
			':ga_num'		=>$_POST['ga_name'],
			':cmt'		=>$_POST['cmt'],
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],

		]);
		return $this->pdo->lastInsertId();
	}
	public function insertGesapWithoutGa(){
		$req=$this->pdo->prepare("INSERT INTO gesap ( op, salon, cata, code_op, date_remonte, cmt, date_insert, by_insert) VALUES (:op, :salon, :cata, :code_op, :date_remonte, :cmt, :date_insert, :by_insert)");
		$req->execute([
			':op'		=>$_POST['op'],
			':salon'		=>$_POST['salon'],
			':cata'		=>$_POST['cata'],
			':code_op'		=>$_POST['code_op'],
			':date_remonte'		=>$_POST['date_remonte'],
			':cmt'		=>$_POST['cmt'],
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],

		]);
		return $this->pdo->lastInsertId();
	}
	public function insertFile($idGesap, $file, $filename){
		$req=$this->pdo->prepare("INSERT INTO gesap_files (id_gesap, file, filename) VALUES (:id_gesap, :file, :filename)");
		$req->execute([
			':id_gesap'		=>$idGesap,
			':file'			=>$file,
			':filename'		=>$filename
		]);
		return $req->rowCount();
	}

	public function insertFileWithOrdre($idGesap, $file, $filename, $ordre){
		$req=$this->pdo->prepare("INSERT INTO gesap_files (id_gesap, file, filename, ordre) VALUES (:id_gesap, :file, :filename, :ordre)");
		$req->execute([
			':id_gesap'		=>$idGesap,
			':file'			=>$file,
			':filename'		=>$filename,
			':ordre'		=>$ordre
		]);
		return $req->rowCount();
	}


	public function deleteGesap($id){
		$req=$this->pdo->prepare("DELETE FROM gesap WHERE id= :id");
		$req->execute([
			':id'			=>$id,

		]);
		return $req->errorInfo();
	}



	public function updateGesapWithGa($file){
		$req=$this->pdo->prepare("UPDATE gesap SET op= :op, salon= :salon, cata= :cata, code_op= :code_op, date_remonte= :date_remonte, ga_file= :ga_file, ga_num= :ga_num, cmt= :cmt, date_insert= :date_insert, by_insert= :by_insert WHERE id= :id");
		$req->execute([
			':id'		=>$_GET['id'],
			':op'		=>$_POST['op'],
			':salon'		=>$_POST['salon'],
			':cata'		=>$_POST['cata'],
			':code_op'		=>$_POST['code_op'],
			':date_remonte'		=>$_POST['date_remonte'],
			':ga_file'		=>$file,
			':ga_num'		=>$_POST['ga_num'],
			':cmt'		=>$_POST['cmt'],
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],

		]);
		return $this->pdo->lastInsertId();
	}
	public function updateGesapWithoutGa(){
		$req=$this->pdo->prepare("UPDATE gesap SET op= :op, salon= :salon, cata= :cata, code_op= :code_op, date_remonte= :date_remonte, cmt= :cmt, date_insert= :date_insert, by_insert= :by_insert WHERE id= :id");
		$req->execute([
			':id'		=>$_GET['id'],
			':op'		=>$_POST['op'],
			':salon'		=>$_POST['salon'],
			':cata'		=>$_POST['cata'],
			':code_op'		=>$_POST['code_op'],
			':date_remonte'		=>$_POST['date_remonte'],
			':cmt'		=>$_POST['cmt'],
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],

		]);
		return $this->pdo->lastInsertId();
	}

	public function updateNameGesapFile($idFile,$name){
		$req=$this->pdo->prepare("UPDATE gesap_files SET filename= :filename WHERE id= :id");
		$req->execute([
			':id'		=>$idFile,
			':filename'=>$name
		]);
		return $req->rowCount();
	}

	public function deleteFile($idFile){
		$req=$this->pdo->prepare("DELETE FROM gesap_files WHERE id=:id");
		$req->execute([
			':id'		=>$idFile
		]);
		return $req->errorInfo();
	}
}


