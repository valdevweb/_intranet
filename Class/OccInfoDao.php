<?php


class OccInfoDao{

	// pdoOcc
	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}


	public function getHtmlNews($htmlFile){
		$req=$this->pdo->prepare("SELECT html_file, id FROM news WHERE html_file LIKE :html_file");
		$req->execute([
			':html_file'	=>$htmlFile
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);

		if(!empty($data)){
			return $data;
		}
		return false;
	}

	public function getPj($idNews){
		$req=$this->pdo->prepare("SELECT * FROM news_file WHERE id_occ_news= :id_occ_news");
		$req->execute([
			':id_occ_news'	=>$idNews
		]);
		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($data)){
			return $data;
		}
		return '';
	}

	public function getUnpublished(){
		$req=$this->pdo->query("SELECT * FROM news WHERE date_start IS NULL order by date_insert");
		return $req->fetchAll(PDO::FETCH_ASSOC);


	}

	public function getUnpublishedFiles(){
		$req=$this->pdo->query("SELECT * FROM news_file LEFT JOIN news ON news_file.id_occ_news= news.id WHERE date_start IS NULL order by date_insert");
		return $req->fetchAll(PDO::FETCH_ASSOC);


	}

	public function delHtmlNews($htmlFile){
		$req=$this->pdo->prepare("DELETE FROM  news WHERE html_file LIKE :html_file");
		$req->execute([
			':html_file'	=>$htmlFile
		]);
		return $req->rowCount();
	}
}



?>