<?php
class CdesCmtDao
{

    // pdoOcc
    private $pdo;

    public function __construct($pdo)
    {
        $this->setPdo($pdo);
    }
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
        return $pdo;
    }

    public function getCmt($id){
        $req=$this->pdo->prepare("SELECT * FROM cdes_cmts WHERE id=:id");
        $req->execute([
            ':id'        => $id,
        
        ]);
        return $req->fetchAll();
    }
    public function insertCmt($id, $idImport, $cmtBtlec, $cmtGalec)
    {
        $req = $this->pdo->prepare("INSERT INTO cdes_cmts (id, id_import, cmt_btlec, cmt_galec, id_web_user, date_insert, date_update) VALUES (:id, :id_import, :cmt_btlec, :cmt_galec, :id_web_user, :date_insert, :date_update)");
        $req->execute([
            ':id'        => $id,
            ':id_import'        => $idImport,
            ':cmt_btlec'       => $cmtBtlec,
            ':cmt_galec'       => $cmtGalec,
            ':id_web_user'     => $_SESSION['id_web_user'],
            ':date_insert'     => date('Y-m-d H:i:s'),
            ':date_update'      =>null

        ]);
        return $this->pdo->lastInsertId();
    }
    public function insertCmtGalec($id, $cmtGalec)
    {
        $req = $this->pdo->prepare("INSERT INTO cdes_cmts (id, cmt_galec, id_web_user, date_insert, date_update) VALUES (:id, :cmt_galec, :id_web_user, :date_insert, :date_update)");
        $req->execute([
            ':id'        => $id,
            ':cmt_galec'       => $cmtGalec,
            ':id_web_user'     => $_SESSION['id_web_user'],
            ':date_insert'     => date('Y-m-d H:i:s'),
            ':date_update'      =>null

        ]);
        return $this->pdo->lastInsertId();
    }


    public function insertCmtMig($id, $idImport, $cmtBtlec, $cmtGalec, $idWebUser, $date)
    {
        $req = $this->pdo->prepare("INSERT INTO cdes_cmts (id, id_import, cmt_btlec, cmt_galec, id_web_user, date_insert, date_update) VALUES (:id, :id_import, :cmt_btlec, :cmt_galec, :id_web_user, :date_insert, :date_update)");
        $req->execute([
            ':id'               =>$id,
            ':id_import'        => $idImport,
            ':cmt_btlec'       => $cmtBtlec,
            ':cmt_galec'       => $cmtGalec,
            ':id_web_user'     => $idWebUser,
            ':date_insert'     => $date,
            ':date_update'      =>null
        ]);
        return $this->pdo->lastInsertId();
    }
    public function updateCmt($id, $idImport, $cmtBtlec, $cmtGalec)
    {
        $req = $this->pdo->prepare("UPDATE cdes_cmts SET id_import= :id_import, cmt_btlec= :cmt_btlec, cmt_galec= :cmt_galec, id_web_user= :id_web_user, date_update= :date_update WHERE id=:id");
        $req->execute([
            ':id'               =>$id,
            ':id_import'        => $idImport,
            ':cmt_btlec'       => $cmtBtlec,
            ':cmt_galec'       => $cmtGalec,
            ':id_web_user'     => $_SESSION['id_web_user'],
            ':date_update'     => date('Y-m-d H:i:s')
        ]);
        return $this->pdo->lastInsertId();
    }
    public function updateCmtGalec($id, $cmtGalec)
    {
        $req = $this->pdo->prepare("UPDATE cdes_cmts SET  cmt_galec= :cmt_galec, id_web_user= :id_web_user, date_update= :date_update WHERE id=:id");
        $req->execute([
            ':id'               =>$id,

            ':cmt_galec'       => $cmtGalec,
            ':id_web_user'     => $_SESSION['id_web_user'],
            ':date_update'     => date('Y-m-d H:i:s')
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateCmtMig($id, $idImport, $cmtBtlec, $cmtGalec, $idWebUser, $date)
    {
        $query="UPDATE cdes_cmts SET id_import= :id_import, cmt_btlec= :cmt_btlec, cmt_galec= :cmt_galec, id_web_user= :id_web_user, date_update= :date_update WHERE id= :id";
        // echo $query;
        $req = $this->pdo->prepare($query);
        $req->execute([
            ':id'               =>$id,
            ':id_import'        => $idImport,
            ':cmt_btlec'       => $cmtBtlec,
            ':cmt_galec'       => $cmtGalec,
            ':id_web_user'     => $idWebUser,
            ':date_update'     => $date
        ]);
        return $this->pdo->lastInsertId();
    }
    public function maskCmt($id)
    {
        $req = $this->pdo->prepare("UPDATE cdes_cmts SET del=1, id_web_user= :id_web_user, date_update= :date_update WHERE id= :id");
        $req->execute([
            ':id'        => $id,
            ':id_web_user'    => $_SESSION['id_web_user'],
            ':date_update'    => date('Y-m-d H:i:s')
        ]);
        return $req->errorInfo();
    }



}
