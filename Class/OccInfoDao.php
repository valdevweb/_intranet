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
		$req=$this->pdo->prepare("SELECT * FROM news WHERE html_file LIKE :html_file");
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

	public function updateDateNews($idNews, $start, $end){
		$req=$this->pdo->prepare("UPDATE news SET date_start= :date_start, date_end= :date_end WHERE id= :id");
		$req->execute([
			':date_start'	=>$start,
			':date_end'		=>$end,
			':id'			=>$idNews
		]);
		return $req->rowCount();
	}

	public function getUnpublished(){
		$req=$this->pdo->query("SELECT * FROM news WHERE date_start IS NULL order by date_insert");
		return $req->fetchAll(PDO::FETCH_ASSOC);


	}

	public function getActiveNews(){
		$req=$this->pdo->prepare("SELECT * FROM news WHERE date_start <= :today AND date_end >= :today order by date_start desc");
		$req->execute([
			':today'		=>date('Y-m-d')

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);


	}

	public function getUnpublishedFiles(){
		$req=$this->pdo->query("SELECT *, news_file.id as id_file FROM news_file LEFT JOIN news ON news_file.id_occ_news= news.id WHERE date_start IS NULL order by date_insert");
		return $req->fetchAll(PDO::FETCH_ASSOC);


	}

	public function delHtmlNews($htmlFile){
		$req=$this->pdo->prepare("DELETE FROM  news WHERE html_file LIKE :html_file");
		$req->execute([
			':html_file'	=>$htmlFile
		]);
		return $req->rowCount();
	}

	public function delFile($idFile){
		$req=$this->pdo->prepare("DELETE FROM news_file WHERE id= :id");
		$req->execute([
			':id'	=>$idFile
		]);
		return $req->rowCount();
	}
}



?>