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
        $action = strip_tags($_POST['action']);
        $action = nl2br($action);
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
}
