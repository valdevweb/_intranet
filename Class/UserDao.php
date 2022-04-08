<?php

class UserDao{


	private $pdo;


	public function __construct($pdo){
		$this->setPdo($pdo);
	}

	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getNbPwd(){
		$req=$this->pdo->prepare("SELECT count(id) as recup FROM  users WHERE date_maj_nohash IS NOT NULL");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getNbCompte(){
		$req=$this->pdo->prepare("SELECT count(id) as compte FROM  users");
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function userHasThisRight($idwebuser,$right){
		$req=$this->pdo->prepare("SELECT * FROM attributions WHERE id_user= :id_user AND id_droit= :id_droit");
		$req->execute([
			':id_user'	=>$idwebuser,
			':id_droit'=>$right
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function addRight($idwebuser, $right){
		$req=$this->pdo->prepare("INSERT INTO attributions (id_user, id_droit) VALUES (:id_user, :id_droit)");
		$req->execute([
			':id_user'	=>$idwebuser,
			':id_droit'=>$right
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function removeRight($idwebuser, $right){
		$req=$this->pdo->prepare("DELETE FROM attributions WHERE id_user= :id_user AND id_droit= :id_droit");
		$req->execute([
			':id_user'	=>$idwebuser,
			':id_droit'=>$right
		]);
		// return $req->errorInfo();
	}


	public function isUserAllowed($params){
		$session=$_SESSION['id'];
		$placeholders=implode(',', array_fill(0, count($params), '?'));
		$req=$this->pdo->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
		$req->execute($params);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($datas)){
			return false;
		}
		return true;

	}
	public function getUserAttributions(){
		$req=$this->pdo->prepare("SELECT id_droit FROM attributions WHERE id_user= :id_user order by id_droit");
		$req->execute(array(
			':id_user'		=>$_SESSION['id_web_user']
		));
		return $req->fetchAll(PDO::FETCH_COLUMN);
	}


	public function getUserAttributionsByService($idService){

		$req=$this->pdo->prepare("SELECT * FROM intern_users
			LEFT JOIN attributions ON intern_users.id_web_user = attributions.id_user
			LEFT JOIN droits ON attributions.id_droit= droits.id
			WHERE id_service= :id_service ORDER BY intern_users.id_web_user, id_droit");
		$req->execute([
			':id_service'	=>$idService

		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUserGts($idwebuser){

		$req=$this->pdo->prepare("SELECT gt FROM gts_users WHERE id_web_user=:id_web_user");
		$req->execute([
			':id_web_user'		=>$idwebuser
		]);
		return $req->fetchAll(PDO::FETCH_COLUMN);

	}
	public function getUserParam($param){

		$req=$this->pdo->query("SELECT * FROM intern_users WHERE $param");

		return $req->fetchAll();
	}

	public function getServicesMailing(){

		$req=$this->pdo->prepare("SELECT * FROM services WHERE mailing is not null order by service");
		$req->execute([

		]);
		return $req->fetchAll();
	}
	public function getBtlecUserEvo(){
		$req=$this->pdo->prepare("SELECT * FROM intern_users WHERE ld_evo=1 AND email !='' order by fullname");
		$req->execute([

		]);
		return $req->fetchAll();
	}
	public function getUserById($id){
		$req=$this->pdo->prepare("SELECT intern_users.*, services.mailing FROM intern_users LEFT JOIN services ON intern_users.id_service =services.id WHERE intern_users.id=:id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->fetch();
	}

	public function getServiceById($id){
		$req=$this->pdo->prepare("SELECT * FROM services WHERE id=:id");
		$req->execute([
			':id'	=>$id
		]);
		return $req->fetch();
	}
	public function getUsersByServiceById($idService, $withMail=null){
		$param="";
		if($withMail){
			$param= " AND email IS NOT NULL";
		}
		$req=$this->pdo->prepare("SELECT * FROM intern_users WHERE id_service=:id_service {$param} ORDER BY fullname");
		$req->execute([
			':id_service'	=>$idService
		]);
		return $req->fetchAll();
	}
	public function searchEmailInternByName($search){

		$req=$this->pdo->prepare("SELECT * FROM intern_users WHERE fullname like :fullname ");
		$req->execute([
			':fullname'	=>'%'.$search.'%',
		]);
		return $req->fetchAll();
	}
	public function searchEmailInternByEmail($search){

		$req=$this->pdo->prepare("SELECT * FROM intern_users WHERE email like :email ORDER BY prenom");
		$req->execute([
			':email'	=>'%'.$search.'%',
		]);
		return $req->fetchAll();
	}
}