<?php
class ActionDao
{

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findActionsLitige($idLitige)
    {
        $req = $this->pdo->prepare("SELECT action_litiges.*, CONCAT(btlec.prenom, ' ', btlec.nom) as fullname 
        FROM action_litiges LEFT JOIN btlec.btlec ON action_litiges.id_web_user=btlec.btlec.id_webuser 
        WHERE action_litiges.id_dossier= :id ORDER BY date_action");
        $req->execute(array(
            ':id'        => $idLitige

        ));
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findAction($id)
    {
        $req = $this->pdo->prepare("SELECT * FROM actions WHERE id=:id");
        $req->execute([
            ':id'       => $id
        ]);
        return $req->fetch();
    }
    public function findActionLitige($id)
    {
        $req = $this->pdo->prepare("SELECT * FROM action_litiges WHERE id=:id");
        $req->execute([
            ':id'       => $id
        ]);
        return $req->fetch();
    }

    public function addActionLitige($idDossier, $action, $idContrainte, $fileList)
    {

        $req = $this->pdo->prepare("INSERT INTO action_litiges (id_dossier,libelle,id_contrainte,id_web_user,pj,date_action) VALUES (:id_dossier,:libelle,:id_contrainte,:id_web_user,:pj,:date_action)");
        $req->execute(array(
            ':id_dossier'        =>    $idDossier,
            ':libelle'        =>    $action,
            ':id_contrainte'    => $idContrainte,
            ':id_web_user'        => $_SESSION['id_web_user'],
            ':pj'                => $fileList,
            ':date_action'        => date('Y-m-d H:i:s'),
        ));
        return $this->pdo->lastInsertId();
    }

    public function addActionLitigeService($idDossier, $action, $idContrainte, $fileList, $service)
    {

        $req = $this->pdo->prepare("INSERT INTO action_litiges (id_dossier,libelle,id_contrainte,id_web_user,pj,date_action, {$service}) VALUES (:id_dossier,:libelle,:id_contrainte,:id_web_user,:pj,:date_action, 1)");
        $req->execute(array(
            ':id_dossier'        =>    $idDossier,
            ':libelle'        =>    $action,
            ':id_contrainte'    => $idContrainte,
            ':id_web_user'        => $_SESSION['id_web_user'],
            ':pj'                => $fileList,
            ':date_action'        => date('Y-m-d H:i:s'),
        ));
        return $this->pdo->lastInsertId();
    }


    public function getListDossierByContrainte($idContrainte){
        $req=$this->pdo->prepare("SELECT DISTINCT dossier,dossiers.id  FROM action_litiges 
        LEFT JOIN dossiers ON action_litiges.id_dossier=dossiers.id WHERE id_contrainte =:id_contrainte ORDER BY dossier DESC");
        $req->execute([
            ':id_contrainte'    =>$idContrainte
        ]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActionsLitigeFiltreContrainte($idDossier,$param)
    {
        $req=$this->pdo->prepare("SELECT * FROM action_litiges WHERE id_dossier= :id_dossier AND $param ORDER BY date_action");
        $req->execute(array(
            ':id_dossier'		=>$idDossier
    
        ));
        return $req->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getLitigeByContrainte($idContrainte)
    {
        $deadline=(new DateTime())->modify('-5 day');
        $tooOld=(new DateTime())->modify('-35 day');
        $query="SELECT * FROM action_litiges WHERE id_contrainte= :id_contrainte and (date_action BETWEEN {$tooOld->format('Y-m-d')} and {$deadline->format('Y-m-d')})";

        $req=$this->pdo->prepare("SELECT * FROM action_litiges WHERE id_contrainte= :id_contrainte and (date_action BETWEEN :too_old and :deadline)");
        $req->execute([
            ':id_contrainte'        =>$idContrainte,
            ':deadline'      =>$deadline->format('Y-m-d'),
            ':too_old'      =>$tooOld->format('Y-m-d'),
        ]);
        return $req->fetchAll();
    }

    public function isReponseContrainte($idDossier, $idContrainte, $dateAction){
        $req=$this->pdo->prepare("SELECT * FROM action_litiges WHERE id_dossier = :id_dossier AND  id_contrainte= :id_contrainte AND date_action > :date_action");
        $req->execute([
            ':id_dossier'       =>$idDossier,
            ':id_contrainte'    =>$idContrainte,
            ':date_action'      =>$dateAction
        ]);
        $datas=$req->fetchAll();
        if(empty($datas)){
            return false;
        }
        return true;
    }


}
