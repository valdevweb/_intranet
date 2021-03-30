<?php


class ProspectusDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getComingProspectus(){

		$req=$this->pdo->prepare("SELECT * FROM prospectus WHERE date_end>= :date_end");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);
		return $req->fetchAll();
	}

	public function getComingProspectusFiles(){

		$req=$this->pdo->prepare("SELECT id_prosp, prospectus_files.* FROM prospectus_files LEFT JOIN prospectus ON prospectus_files.id_prosp=prospectus.id WHERE date_end>= :date_end");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}


	public function getComingProspectusLinks(){

		$req=$this->pdo->prepare("SELECT id_prosp, prospectus_links.* FROM prospectus_links LEFT JOIN prospectus ON prospectus_links.id_prosp=prospectus.id WHERE date_end>= :date_end");
		$req->execute([
			':date_end'	=>date("Y-m-d")

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
	public function getComingProspectusMag(){
		$dateStart=new DateTime();
		$dateEnd=new DateTime();
		$dateStart=$dateStart->modify('+ 15 day');
		$dateEnd=$dateEnd->modify('- 7 day');
		$req=$this->pdo->prepare("SELECT * FROM prospectus WHERE date_start<= :date_start AND date_end>= :date_end");
		$req->execute([
			':date_end'		=>$dateEnd->format("Y-m-d"),
			':date_start'	=>$dateStart->format("Y-m-d"),

		]);
		return $req->fetchAll();
	}

	public function getComingProspectusFilesMag(){
		$dateStart=new DateTime();
		$dateEnd=new DateTime();
		$dateStart=$dateStart->modify('+ 15 day');
		$dateEnd=$dateEnd->modify('- 7 day');
		$req=$this->pdo->prepare("SELECT id_prosp, prospectus_files.* FROM prospectus_files LEFT JOIN prospectus ON prospectus_files.id_prosp=prospectus.id WHERE date_start<= :date_start AND date_end>= :date_end");
		$req->execute([
			':date_end'		=>$dateEnd->format("Y-m-d"),
			':date_start'	=>$dateStart->format("Y-m-d"),

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}


	public function getComingProspectusLinksMag(){
		$dateStart=new DateTime();
		$dateEnd=new DateTime();
		$dateStart=$dateStart->modify('+ 15 day');
		$dateEnd=$dateEnd->modify('- 7 day');
		$req=$this->pdo->prepare("SELECT id_prosp, prospectus_links.* FROM prospectus_links LEFT JOIN prospectus ON prospectus_links.id_prosp=prospectus.id WHERE date_start<= :date_start AND date_end>= :date_end");
		$req->execute([
			':date_end'		=>$dateEnd->format("Y-m-d"),
			':date_start'	=>$dateStart->format("Y-m-d"),

		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getOneProspectusFiles($idProsp){
		$req=$this->pdo->prepare("SELECT id_prosp, prospectus_files.* FROM prospectus_files LEFT JOIN prospectus ON prospectus_files.id_prosp=prospectus.id WHERE id_prosp= :id_prosp");
		$req->execute([
			':id_prosp'		=>$idProsp
		]);
		return $req->fetchAll();
	}


	public function getOneProspectusLinks($idProsp){
		$req=$this->pdo->prepare("SELECT id_prosp, prospectus_links.* FROM prospectus_links LEFT JOIN prospectus ON prospectus_links.id_prosp=prospectus.id WHERE id_prosp>= :id_prosp");
		$req->execute([
			':id_prosp'		=>$idProsp
		]);
		return $req->fetchAll();
	}

	public function getProspectusById($idProspectus){
		$req=$this->pdo->prepare("SELECT * FROM prospectus WHERE id= :id");
		$req->execute([
			':id'		=>$idProspectus
		]);
		return $req->fetch();
	}
	public function getProspectusByProspectus($prospectus){
		$req=$this->pdo->prepare("SELECT * FROM prospectus WHERE prospectus= :prospectus");
		$req->execute([
			':prospectus'		=>$prospectus
		]);
		return $req->fetch();
	}
	public function addProspectus($dateStart, $dateEnd, $codeOp, $prosp, $file){
		$req=$this->pdo->prepare("INSERT INTO prospectus(date_start, date_end, code_op, prospectus, date_insert, by_insert, fic) VALUES (:date_start, :date_end, :code_op, :prospectus, :date_insert, :by_insert, :fic)");
		$req->execute([
			':date_start'		=>$dateStart,
			':date_end'		=>$dateEnd,
			':code_op'		=>$codeOp,
			':prospectus'		=>$prosp,
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],
			':fic'		=>$file,
		]);
		return $this->pdo->lastInsertId();
	}

	public function updateProspectusWithFic($idProsp){

		$req=$this->pdo->prepare("UPDATE prospectus SET date_start= :date_start, date_end= :date_end, code_op= :code_op, prospectus= :prospectus, fic= :fic, date_insert= :date_insert WHERE id= :id ");
		$req->execute([
			':date_start'		=>$_POST['date_start'],
			':date_end'		=>$_POST['date_end'],
			':code_op'			=>$_POST['code_op'],
			':prospectus'		=>strtoupper($_POST['prospectus']),
			':fic'		=>$_FILES['fic-mod']['name'],
			':id'		=>$idProsp,
			':date_insert'		=>date('Y-m-d H:i:s'),

		]);
		return $req->rowCount();

	}
	public function updateProspectusWithoutFic($idProsp){

		$req=$this->pdo->prepare("UPDATE prospectus SET date_start= :date_start, date_end= :date_end, code_op= :code_op, prospectus= :prospectus, date_insert= :date_insert  WHERE id= :id ");
		$req->execute([
			':date_start'		=>$_POST['date_start'],
			':date_end'		=>$_POST['date_end'],
			':code_op'			=>$_POST['code_op'],

			':prospectus'		=>strtoupper($_POST['prospectus']),
			':id'		=>$idProsp,
			':date_insert'		=>date('Y-m-d H:i:s'),

		]);
		return $req->rowCount();

	}
	public function deleteProsp($id){
		$req=$this->pdo->prepare("DELETE FROM prospectus WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);

	}


	public function insertFile($idProsp, $file){
		$req=$this->pdo->prepare("INSERT INTO prospectus_files (id_prosp, file) VALUES (:id_prosp, :file)");
		$req->execute([
			':id_prosp'		=>$idProsp,
			':file'			=>$file
		]);
		return $req->rowCount();
	}

	public function insertFileWithName($idProsp, $file, $filename, $ordre){
		$req=$this->pdo->prepare("INSERT INTO prospectus_files (id_prosp, file, filename, ordre) VALUES (:id_prosp, :file, :filename, :ordre)");
		$req->execute([
			':id_prosp'		=>$idProsp,
			':file'			=>$file,
			':filename'			=>$filename,
			':ordre'			=>$ordre,
		]);
		return $req->rowCount();
	}

	public function insertLink($idProsp, $link){
		$req=$this->pdo->prepare("INSERT INTO prospectus_links (id_prosp, link) VALUES (:id_prosp, :link)");
		$req->execute([
			':id_prosp'		=>$idProsp,
			':link'			=>$link
		]);
		return $req->rowCount();
	}

	public function deleteLinks($idProsp){
		$req=$this->pdo->prepare("DELETE FROM prospectus_links WHERE id_prosp= :id_prosp");
		$req->execute([
			':id_prosp'	=>$idProsp

		]);
		return $req->errorInfo();
	}

}


