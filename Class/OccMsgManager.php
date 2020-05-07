<?php


class OccMsgManager{

	private $pdo;

	public function __construct($pdo){
		$this->setPdo($pdo);
	}
		public function setPdo($pdo){
		$this->pdo=$pdo;
		return $pdo;
	}

	public function getListMsg($params=null){
		// exemple :
		// $piloteManager->getListPilotes(['authorized=0','console="ps4"']);
		$paramStr="";
		if(isset($params)){
			$paramStr='WHERE ' .join(' AND ',$params);
		}
		$req=$this->pdo->query("SELECT * FROM occ_msg {$paramStr} ORDER BY date_insert");

		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		if(empty($data)){
			return "";
		}
		return $data;
		$params="";
	}


	public function getMsg($id){

	}
	public function getListRep($pdoBt,$idMsg){

	}


	public function getListMsgByIdwebuser($pdoBt,$idwebuser){

	}






}



?>