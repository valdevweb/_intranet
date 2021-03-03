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

	public function getComingProspectus($today){
		$req=$this->pdo->prepare("SELECT * FROM prospectus WHERE date_end>= :date_end");
		$req->execute([
			':date_end'		=>$today
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
	public function addProspectus(){
		$req=$this->pdo->prepare("INSERT INTO prospectus(date_start, date_end, prospectus, date_insert, by_insert, fic) VALUES (:date_start, :date_end, :prospectus, :date_insert, :by_insert, :fic)");
		$req->execute([
			':date_start'		=>$_POST['date_start'],
			':date_end'		=>$_POST['date_end'],
			':prospectus'		=>strtoupper($_POST['prospectus']),
			':date_insert'		=>date('Y-m-d H:i:s'),
			':by_insert'		=>$_SESSION['id_web_user'],
			':fic'		=>isset($_FILES['fic']['name'])?$_FILES['fic']['name']:"",
		]);
		return $req->rowCount();
	}

	public function updateProspectus($idProsp){
		$fic="";
		if(isset($_FILES['fic-mod']['name']) && !empty($_FILES['fic-mod']['name'])){
			$fic=$_FILES['fic-mod']['name'];

		}else{
			if(!empty($_POST['previous_fic'])){
				$fic=$_POST['previous_fic'];
			}
		}
		$req=$this->pdo->prepare("UPDATE prospectus SET date_start= :date_start, date_end= :date_end, prospectus= :prospectus, fic= :fic WHERE id= :id ");
		$req->execute([
			':date_start'		=>$_POST['date_start'],
			':date_end'		=>$_POST['date_end'],
			':prospectus'		=>strtoupper($_POST['prospectus']),
			':fic'		=>$fic,
			':id'		=>$idProsp,
		]);
		return $req->rowCount();

	}

		public function deleteProsp($id){
		$req=$this->pdo->prepare("DELETE FROM prospectus WHERE id= :id");
		$req->execute([
			':id'	=>$id
		]);

	}

}


