<?php

class OpportuniteDAO{

	private $pdoBt;



	public function __construct($pdoBt){
		$this->setPdo($pdoBt);
	}

	public function setPdo($pdoBt){
		$this->pdoBt=$pdoBt;
		return $pdoBt;
	}

	public function addOpportunite(){
		$req=$this->pdoBt->prepare("INSERT INTO opp (date_start, date_end, id_web_user, title, descr, salon, cata, dispo, gt) VALUES (:date_start, :date_end, :id_web_user, :title, :descr, :salon, :cata, :dispo, :gt)");
		$req->execute([
			':date_start'	=>$_POST['date_start'],
			':date_end'	=>$_POST['date_end'],
			':id_web_user'	=>$_SESSION['id_web_user'],
			':title'	=>$_POST['title'],
			':descr'	=>isset($_POST['descr']) ? $_POST['descr'] : '',
			':salon'	=>$_POST['salon'],
			':cata'	=>$_POST['cata'],
			':dispo'	=>isset($_POST['dispo']) ? $_POST['dispo'] : '',
			':gt'		=>$_POST['gt'],

		]);

		return $this->pdoBt->lastInsertId();
	}

	public function addMainFile($idOpp, $filename, $image, $ordre){
		$req=$this->pdoBt->prepare("INSERT INTO opp_files_main (id_opp, filename, image, ordre) VALUES (:id_opp, :filename, :image, :ordre)");
		$req->execute([
			':id_opp'		=>$idOpp,
			':filename'	=>$filename,
			':image'		=>$image,
			':ordre'	 =>$ordre
		]);

		return $req->errorInfo();
	}

	public function addAddonsFile($idOpp, $filename){
		$req=$this->pdoBt->prepare("INSERT INTO opp_files_addons (id_opp, filename) VALUES (:id_opp, :filename)");
		$req->execute([
			':id_opp'		=>$idOpp,
			':filename'	=>$filename,
		]);

		return $req->errorInfo();
	}

	public function addIcons($idOpp,$icons){
		for ($i=0; $i < count($_POST['icons']) ; $i++) {
			$req=$this->pdoBt->prepare("INSERT INTO opp_icons (id_opp, icon) VALUES (:id_opp, :icon)");
			$req->execute([
				':id_opp'	=>$idOpp,
				':icon'		=>$_POST['icons'][$i]
			]);
		}

	}
	public function getOpp($idOpp){
		$req=$this->pdoBt->prepare("SELECT * FROM opp WHERE id= :id");
		$req->execute([
			':id'		=>$idOpp
		]);
		// on fait un fecth all pour pouvoir utiliser le même template d'afficha quelque soit la requete
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListMainFiles($OppIds){
		$arFiles=[];
		$listIds=join(' OR ', array_map(function($value){return 'id_opp='.$value;},$OppIds));

		$i=0;
		$req=$this->pdoBt->query("SELECT * FROM opp_files_main WHERE {$listIds} ORDER BY id_opp, ordre");
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		foreach ($datas as $key => $file) {
			$arFiles[$file['id_opp']][$i]['id']=$file['id'];
			$arFiles[$file['id_opp']][$i]['filename']=$file['filename'];
			$arFiles[$file['id_opp']][$i]['image']=$file['image'];
			$arFiles[$file['id_opp']][$i]['ordre']=$file['ordre'];
			$i++;
		}

		return $arFiles;
	}

	public function getListAddonsFiles($OppIds){
		$arFiles=[];
		$listIds=join(' OR ', array_map(function($value){return 'id_opp='.$value;},$OppIds));

		$i=0;
		$req=$this->pdoBt->query("SELECT * FROM opp_files_addons WHERE {$listIds} ORDER BY id_opp");
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		foreach ($datas as $key => $file) {
			$arFiles[$file['id_opp']][$i]['id']=$file['id'];
			$arFiles[$file['id_opp']][$i]['filename']=$file['filename'];
			$i++;
		}

		return $arFiles;
	}


	public function getListIcons($OppIds){
		$arIcons=[];
		$listIds=join(' OR ', array_map(function($value){return 'id_opp='.$value;},$OppIds));

		// $i=0;
		$req=$this->pdoBt->query("SELECT * FROM opp_icons WHERE {$listIds} ORDER BY id_opp");
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);
		foreach ($datas as $key => $icon) {
			$arIcons[$icon['id_opp']][]=$icon['icon'];
			// $i++;
		}

		return $arIcons;
	}


	public function getActiveOpp(){
		$req=$this->pdoBt->query("SELECT * FROM opp WHERE date_start <= NOW() AND date_end>=NOW() ORDER BY date_start DESC");
		return $req->fetchAll(PDO::FETCH_ASSOC);

	}

	public function updateOpportunite($idOpp){
		$req=$this->pdoBt->prepare("UPDATE opp SET date_start = :date_start, date_end= :date_end, id_web_user= :id_web_user, title= :title, descr= :descr, salon= :salon, cata= :cata, dispo= :dispo, gt= :gt WHERE id= :id");
		$req->execute([
			':id'			=>$idOpp,
			':date_start'	=>$_POST['date_start'],
			':date_end'	=>$_POST['date_end'],
			':id_web_user'	=>$_SESSION['id_web_user'],
			':title'	=>$_POST['title'],
			':descr'	=>isset($_POST['descr']) ? $_POST['descr'] : '',
			':salon'	=>$_POST['salon'],
			':cata'	=>$_POST['cata'],
			':dispo'	=>isset($_POST['dispo']) ? $_POST['dispo'] : '',
			':gt'		=>$_POST['gt'],

		]);

		return $this->pdoBt->lastInsertId();
	}

	public function deleteOppIcons($idOpp){
		$req=$this->pdoBt->prepare("DELETE FROM opp_icons WHERE id_opp= :id_opp");
		$req->execute([
			':id_opp'		=>$idOpp
		]);
		return $req->errorInfo();
	}


}