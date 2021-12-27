<?php

class MailDao{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getMailsByField($field, $value){
		$req=$this->pdo->prepare("SELECT * FROM listdiffu LEFT JOIN listdiffu_email ON listdiffu.id=listdiffu_email.id_listdiffu LEFT JOIN emails on listdiffu_email.id_email=emails.id WHERE $field= :value ");
		$req->execute([
			$field=>$value,
		]);
		return $req->fetchAll();
	}


	public function getMailsByFields($params){
		$paramStr = implode(', ', array_map(
			function ($v, $k) { return sprintf("%s=:%s ", $k, $k); },
			$params,
			array_keys($params)
		));
		$req=$this->pdo->prepare("SELECT * FROM listdiffu LEFT JOIN listdiffu_email ON listdiffu.id=listdiffu_email.id_listdiffu LEFT JOIN emails on listdiffu_email.id_email=emails.id WHERE $paramStr ");
		$req->execute([
			$params
		]);
		return $req->fetchAll();
	}

	public function searchLdByField($field, $value){
		$query="SELECT * FROM listdiffu WHERE $field like :$field ORDER BY $field";

		$req=$this->pdo->prepare($query);
		$req->execute([
			':'.$field			=>'%'.$value.'%',
		]);
		return $req->fetchAll();
	}

	public function searchEmailLike($email){
		$query="SELECT * FROM emails WHERE email like :email AND mask=0 ORDER BY email";
		// echo $query;
		$req=$this->pdo->prepare($query);
		$req->execute([
			':email'			=>'%'.$email.'%',
		]);
		return $req->fetchAll();
	}


}


