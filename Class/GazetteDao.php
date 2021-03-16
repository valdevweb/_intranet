<?php


class GazetteDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getGazetteEnCours(){
		$today=new DateTime();
		$monday=$today->modify("monday this week");
		$req=$this->pdo->prepare("SELECT * FROM gazette WHERE DATE_FORMAT(date_start, '%Y-%m-%d')>= :monday ORDER BY date_start DESC");
		$req->execute([
			':monday'		=>$monday->format('Y-m-d')
		]);
		return $req->fetchAll();

	}



	public function getFilesEncours(){
		$today=new DateTime();
		$monday=$today->modify("monday this week");
		$req=$this->pdo->prepare("SELECT id_gazette, file, filename, gazette_files.id as id FROM gazette_files LEFT JOIN gazette ON id_gazette= gazette.id WHERE DATE_FORMAT(date_start, '%Y-%m-%d')>= :monday ORDER BY date_start DESC");
		$req->execute([
			':monday'		=>$monday->format('Y-m-d')
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getLinkEncours(){
		$today=new DateTime();
		$monday=$today->modify("monday this week");
		$req=$this->pdo->prepare("SELECT id_gazette, link, linkname, gazette_links.id as id FROM gazette_links LEFT JOIN gazette ON id_gazette= gazette.id WHERE DATE_FORMAT(date_start, '%Y-%m-%d')>= :monday ORDER BY date_start DESC");
		$req->execute([
			':monday'		=>$monday->format('Y-m-d')
		]);
		return $req->fetchAll(PDO::FETCH_GROUP);
	}

	public function getGazettePeriode($dateStart,$dateEnd){
		$req=$this->pdo->prepare("SELECT * FROM gazette WHERE DATE_FORMAT(date_start, '%Y-%m-%d')>= :date_start AND DATE_FORMAT(date_start, '%Y-%m-%d')<= :date_end ORDER BY date_start DESC");
		$req->execute([
			':date_start'		=>$dateStart,
			':date_end'			=>$dateEnd
		]);
		return $req->fetchAll();

	}
	public function getGazetteString($string){
		$req=$this->pdo->prepare("SELECT * FROM gazette WHERE titre LIKE  :titre ORDER BY date_start DESC");
		$req->execute([
			':titre'		=>'%'.$string.'%',
		]);
		return $req->fetchAll();

	}
	public function getLinkPeriode($dateStart,$dateEnd){
		$req=$this->pdo->prepare("SELECT * FROM gazette_links LEFT JOIN gazette ON gazette_links.id_gazette= gazette.id WHERE
			DATE_FORMAT(date_start, '%Y-%m-%d')>= :date_start AND DATE_FORMAT(date_start, '%Y-%m-%d')<= :date_end ORDER BY date_start DESC");
		$req->execute([
			':date_start'		=>$dateStart,
			':date_end'			=>$dateEnd
		]);
		return $req->fetchAll();

	}
	public function getLinkString($string){
		$req=$this->pdo->prepare("SELECT * FROM gazette_links LEFT JOIN gazette ON gazette_links.id_gazette= gazette.id WHERE titre LIKE  :titre ORDER BY date_start DESC");
		$req->execute([
			':titre'		=>'%'.$string.'%',
		]);
		return $req->fetchAll();

	}
	public function getFilesPeriode($dateStart,$dateEnd){
		$req=$this->pdo->prepare("SELECT * FROM gazette_files LEFT JOIN gazette ON gazette_files.id_gazette= gazette.id WHERE DATE_FORMAT(date_start, '%Y-%m-%d')>= :date_start AND DATE_FORMAT(date_start, '%Y-%m-%d')<= :date_end ORDER BY date_start DESC");
		$req->execute([
			':date_start'		=>$dateStart,
			':date_end'			=>$dateEnd
		]);
		return $req->fetchAll();

	}
	public function getFilesString($string){
		$req=$this->pdo->prepare("SELECT * FROM gazette_files LEFT JOIN gazette ON gazette_files.id_gazette= gazette.id WHERE titre LIKE  :titre ORDER BY date_start DESC");
		$req->execute([
			':titre'		=>'%'.$string.'%',
		]);
		return $req->fetchAll();

	}

	public function getGazette($id){
		$req=$this->pdo->prepare("SELECT * FROM gazette WHERE id= :id");
		$req->execute([
			':id'			=>$id
		]);
		return $req->fetch();
	}

	public function addGazette(){
		$req=$this->pdo->prepare("INSERT INTO gazette (date_start, titre, description, main_cat, cat, date_insert, by_insert) VALUES (:date_start, :titre, :description, :main_cat, :cat, :date_insert, :by_insert)");
		$req->execute([
			':date_start'		=>$_POST['date_start'],
			':titre'		=>$_POST['titre'],
			':description'		=>$_POST['description'],
			':main_cat'	=>$_POST['main_cat'],
			':cat'	=>$_POST['cat'],
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],

		]);
		return $this->pdo->lastInsertId();
	}

	public function updateGazette($id){
		$req=$this->pdo->prepare("UPDATE gazette SET date_start= :date_start, titre= :titre, description= :description, main_cat= :main_cat, cat= :cat, date_insert= :date_insert, by_insert= :by_insert  WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':date_start'		=>$_POST['date_start'],
			':titre'		=>$_POST['titre'],
			':description'		=>$_POST['description'],
			':main_cat'	=>$_POST['main_cat'],
			':cat'	=>$_POST['cat'],
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],

		]);
		return $this->pdo->lastInsertId();
	}

	public function addFiles($idGazette, $file){
		$req=$this->pdo->prepare("INSERT INTO gazette_files (id_gazette, file) VALUES (:id_gazette, :file)");
		$req->execute([
			':id_gazette'		=>$idGazette,
			':file'		=>$file,
		]);
		return $req->errorInfo();
	}
	public function addLinks($idGazette, $link){
		$req=$this->pdo->prepare("INSERT INTO gazette_links (id_gazette, link) VALUES (:id_gazette, :link)");
		$req->execute([
			':id_gazette'		=>$idGazette,
			':link'		=>$link,
		]);
		return $req->errorInfo();
	}
	public function updateFiles($idFile, $filename){
		$req=$this->pdo->prepare("UPDATE gazette_files SET filename= :filename WHERE id= :id");
		$req->execute([
			':id'		=>$idFile,
			':filename'		=>$filename,
		]);
		return $req->errorInfo();
	}
	public function updateLinks($idLink, $linkname){
		$req=$this->pdo->prepare("UPDATE gazette_links SET linkname= :linkname WHERE id= :id");
		$req->execute([
			':id'		=>$idLink,
			':linkname'		=>$linkname,
		]);
		return $req->errorInfo();
	}
	public function getCatByMain($fieldId){
		$mainCat=[1 =>"btlec", 2 =>"galec"];
		$field=($mainCat[$fieldId])??"";
		if(!empty($field)){
			$req=$this->pdo->query("SELECT id, cat FROM gazette_cats WHERE $field=1 ORDER BY cat");
			return $req->fetchAll(PDO::FETCH_KEY_PAIR);
		}
		return "";
	}

	public function getCat(){
		$mainCat=[1 =>"btlec", 2 =>"galec"];
		$req=$this->pdo->query("SELECT id, cat FROM gazette_cats ORDER BY cat");
		return $req->fetchAll(PDO::FETCH_KEY_PAIR);
	}
	public function deleteGazette($id){
		$req=$this->pdo->prepare("DELETE FROM gazette WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
	}
	public function deleteFile($id){
		$req=$this->pdo->prepare("DELETE FROM gazette_files WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
	}
	public function deleteLink($id){
		$req=$this->pdo->prepare("DELETE FROM gazette_links WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);
	}
	public function deleteLinkByGazette($id){
		$req=$this->pdo->prepare("DELETE FROM gazette_links WHERE id_gazette= :id_gazette");
		$req->execute([
			':id_gazette'	=>$id
		]);
	}
	public function getFiles($idGazette){

		$req=$this->pdo->prepare("SELECT * FROM gazette_files WHERE id_gazette= :id_gazette");
		$req->execute([
			':id_gazette'	=> $idGazette

		]);
		return $req->fetchAll();
	}

	public function getLinks($idGazette){

		$req=$this->pdo->prepare("SELECT * FROM gazette_links WHERE id_gazette= :id_gazette");
		$req->execute([
			':id_gazette'	=> $idGazette

		]);
		return $req->fetchAll();
	}

	public function addCat($idMainCat, $cat){
		$mainCat=[1 =>"btlec", 2 =>"galec"];
		$field=$mainCat[$idMainCat];
		$req=$this->pdo->prepare("INSERT INTO gazette_cats (cat, $field) VALUES (:cat, :field)");
		$req->execute([
			':cat'		=>$cat,
			':field'		=>1
		]);
		return $req->errorInfo();
	}

	public function getGazetteByParam($param){
		$req=$this->pdo->query("SELECT * FROM gazette WHERE $param");
		return $req->fetchAll();
	}
		public function getFilesByParam($param){
		$req=$this->pdo->query("SELECT gazette.id, gazette_files.* FROM gazette_files LEFT JOIN gazette ON gazette_files.id_gazette= gazette.id WHERE $param");
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
		public function getLinksByParam($param){
		$req=$this->pdo->query("SELECT gazette.id, gazette_links.* FROM gazette_links LEFT JOIN gazette ON gazette_links.id_gazette= gazette.id WHERE $param");
		return $req->fetchAll(PDO::FETCH_GROUP);
	}
}