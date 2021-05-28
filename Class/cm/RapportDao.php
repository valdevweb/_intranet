<?php
/**
 *
 */
class RapportDao{

	private $pdo;

	function __construct($pdo){
		$this->setPdo($pdo);
	}
	public function setPdo(PDO $pdo){
		$this->pdo = $pdo;
		return $pdo;
	}
	public function getListRapport(){
		$req=$this->pdo->query("SELECT rayon_rapport.id as id_main, rayon_rapport.*, rdv.*, magasin.mag.*, DATE_FORMAT(rdv.date_start, '%d-%m-%Y') as dateStart FROM rayon_rapport
			LEFT JOIN rdv ON id_rdv=rdv.id
			LEFT JOIN magasin.mag ON rdv.galec=magasin.mag.galec
			order by rayon_rapport.date_crea");
		$data=$req->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

	public function getOneRapportById($id){
		$req=$this->pdo->prepare("SELECT rayon_rapport.id as id_main, rayon_rapport.*, rdv.*, magasin.mag.*, DATE_FORMAT(rdv.date_start, '%d-%m-%Y') as dateStart FROM rayon_rapport
			LEFT JOIN rdv ON id_rdv=rdv.id
			LEFT JOIN magasin.mag ON rdv.galec=magasin.mag.galec
			WHERE rayon_rapport.id= :id
			order by rayon_rapport.date_crea");
		$req->execute([
			':id'	=>$id
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		return $data;
	}


	public function getOneRapportByIdRdv($idRdv){
		$req=$this->pdo->prepare("SELECT rayon_rapport.id as id_main, rayon_rapport.*, rdv.*, magasin.mag.*, DATE_FORMAT(rdv.date_start, '%d-%m-%Y') as dateStart FROM rayon_rapport
			LEFT JOIN rdv ON id_rdv=rdv.id
			LEFT JOIN magasin.mag ON rdv.galec=magasin.mag.galec
			WHERE rayon_rapport.id_rdv= :id_rdv
			order by rayon_rapport.date_crea");
		$req->execute([
			':id_rdv'	=>$idRdv
		]);
		$data=$req->fetch(PDO::FETCH_ASSOC);
		return $data;
	}



	public function getListProdRepondu($idRdv){
		$req=$this->pdo->prepare("SELECT *  from rayon_rep WHERE id_rdv= :id_rdv GROUP BY id_prod ORDER BY id_prod");
		$req->execute([
			':id_rdv'	=>$idRdv
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getListTheme(){
		$req=$this->pdo->query("SELECT * FROM themes ORDER BY id");
		return $req->fetchAll(PDO::FETCH_ASSOC);

	}

	public function getReponseByProdAndTheme($idRdv,$prod, $theme){
		$newData=[];
		$reponses="";
		$req=$this->pdo->prepare("SELECT id_rep from rayon_rep WHERE id_rdv= :id_rdv AND id_prod= :id_prod AND id_theme= :id_theme ORDER BY id_rep");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':id_prod'	=>$prod,
			':id_theme'	=>$theme
		]);
		$datas=$req->fetchAll(PDO::FETCH_ASSOC);

		if(!empty($datas)){
			foreach ($datas as $key => $data) {
				$newData[]=$data['id_rep'];
			}
		}
		if(!empty($newData)){
			$reponses=implode('-',$newData);
		}

		return $reponses;

	}


	public function getCmtRayonByProdAndTheme($idRdv,$prod, $theme){
// on ordonne en dégcrémentant car il arrive que l'on recupère plusoeurs commentaires pour un seul couple prod/theme alors que l'on ne devraiit pâs
		$req=$this->pdo->prepare("SELECT * from rayon_cmts WHERE id_rdv= :id_rdv AND id_prod= :id_prod AND id_theme= :id_theme ORDER BY id DESC LIMIT 1");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':id_prod'	=>$prod,
			':id_theme'	=>$theme
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}


	public function getSentence($scenario){
		$req=$this->pdo->prepare("SELECT * FROM rayon_sentences WHERE scenario LIKE :scenario");
		$req->execute([
			':scenario'		=>"%".$scenario."%"

		]);

		return $req->fetch(PDO::FETCH_ASSOC);
	}

	public function getProdPhoto($idRdv,$idProd){
		$req=$this->pdo->prepare("SELECT * FROM rayon_photo where id_rdv= :id_rdv and id_prod= :id_prod");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':id_prod'	=>$idProd
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);



	}

	public function getSmiley($idRdv,$idProd,$idTheme){
		$req=$this->pdo->prepare("SELECT note FROM rayon_smiley WHERE id_rdv= :id_rdv AND id_prod= :id_prod AND id_theme= :id_theme");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':id_prod'=>$idProd,
			':id_theme' =>$idTheme
		]);
		return $req->fetch(PDO::FETCH_ASSOC);
	}



	public function getOriginalQuestionReponse($reponses){
		$listreponses=explode("-",$reponses);
		$result="";
		for ($i=0; $i <count($listreponses) ; $i++) {
			$req=$this->pdo->prepare("SELECT * FROM reponses LEFT JOIN questions ON id_question=questions.id WHERE reponses.id=:id");
			$req->execute([
				':id'	=>$listreponses[$i]
			]);
			$data=$req->fetch(PDO::FETCH_ASSOC);


			$result.='<div class="question">'.$data['question'].'</div>';
			$result.='<div class="reponse">'.$data['reponse'].'</div>';
		}
		return $result;

	}


	public function updateCmtRayon($id,$cmt){
		$req=$this->pdo->prepare("UPDATE rayon_cmts SET cmt= :cmt WHERE id=:id");
		$req->execute([
			':id'	=>$id,
			':cmt'	=>$cmt
		]);
		return $req->rowCount();
	}

	public function getOblRep($idRdv){

		$req=$this->pdo->prepare("SELECT obl_repmag.id_rep, obl_questions.question, obl_reponses.reponse FROM obl_repmag
			LEFT JOIN obl_questions on id_q=obl_questions.id
			LEFT JOIN obl_reponses on id_rep=obl_reponses.id
			WHERE id_rdv=:id_rdv");
		$req->execute([
			':id_rdv'	=>$idRdv,
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getFormationCmt($idRdv){

		$req=$this->pdo->prepare("SELECT * FROM formation_cmts WHERE id_rdv=:id_rdv ORDER BY id desc LIMIT 1 ");
		$req->execute([
			':id_rdv'	=>$idRdv,

		]);
		return $req->fetch();
	}


	public function getRemodelingCmt($idRdv){

		$req=$this->pdo->prepare("SELECT * FROM remodeling_cmts WHERE id_rdv=:id_rdv ORDER BY id desc LIMIT 1 ");
		$req->execute([
			':id_rdv'	=>$idRdv,

		]);
		return $req->fetch();
	}

	public function updateFormationCmt($id,$cmt){
		$req=$this->pdo->prepare("UPDATE formation_cmts SET cmt= :cmt WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':cmt'		=>$cmt
		]);
		return $req->rowCount();
	}


	public function updateRemodelingCmt($id,$cmt){
		$req=$this->pdo->prepare("UPDATE remodeling_cmts SET cmt= :cmt WHERE id= :id");
		$req->execute([
			':id'		=>$id,
			':cmt'		=>$cmt
		]);
		return $req->rowCount();
	}


	public function getDocjoin($idRdv){
		$req=$this->pdo->prepare("SELECT * FROM formation_docjoin WHERE id_rdv= :id_rdv");
		$req->execute([
			':id_rdv'	=>$idRdv,
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function getDocjoinName($idRdv){
		$req=$this->pdo->prepare("SELECT * FROM formation_docjoin
			LEFT JOIN formation_docnames ON id_doc=formation_docnames.id
			LEFT JOIN formation_docs ON formation_docnames.id=formation_docs.id_docname
			WHERE id_rdv= :id_rdv");
		$req->execute([
			':id_rdv'	=>$idRdv,
		]);
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}
	public function deleteDocjoin($idRdv){
		$req=$this->pdo->prepare("DELETE FROM formation_docjoin WHERE id_rdv= :id_rdv");
		$req->execute([
			':id_rdv'	=>$idRdv,

		]);
		return $req->errorInfo();
	}

	public function insertDocjoin($idRdv, $idDoc){
		$req=$this->pdo->prepare("INSERT INTO formation_docjoin (id_rdv, id_doc) VALUES (:id_rdv, :id_doc)");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':id_doc'	=>$idDoc
		]);
		return $req->rowCount();
	}

	public function insertEmail($idRdv, $email){
		$req=$this->pdo->prepare("INSERT INTO rayon_emails (id_rdv, email) VALUES (:id_rdv, :email)");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':email'	=>$email
		]);
		return $req->rowCount();
	}

	public function updateRapport($idRdv){
		$req=$this->pdo->prepare("UPDATE rayon_rapport SET date_sent= :date_sent, by_sent= :by_sent WHERE id_rdv= :id_rdv");
		$req->execute([
			':id_rdv'	=>$idRdv,
			':date_sent'		=>date('Y-m-d H:i:s'),
			':by_sent'		=>$_SESSION['id_web_user']
		]);
		return $req->rowCount();
	}


	public function magRapportSent($galec){

		$req=$this->pdo->prepare("SELECT rayon_rapport.*, date_start FROM rayon_rapport
			LEFT JOIN rdv ON id_rdv=rdv.id
			WHERE date_sent IS NOT null AND galec= :galec ORDER BY date_sent DESC");
		$req->execute([
			':galec'		=>$galec
		]);
		return $req->fetchAll();
	}


}