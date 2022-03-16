<?php


if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'config/db-connect.php';
include 'functions/tasklog.fn.php';
include 'batch-mag/utils.fn.php';



function getMagFromMag($pdoMag){
    $req=$pdoMag->query("SELECT * FROM mag");
    return $req->fetchAll();
}

function updateSca3($pdoMag, $btlec, $galec, $deno, $ad1, $ad2, $cp, $ville, $gel, $dateFermeture, $dateOuverture){
    // on met à jour les champs commun non déphasé
    // on vérife si pas de pole_sav et pas d'antenne sinon pas mise à jour
    $req=$pdoMag->prepare(" UPDATE sca3 SET galec_sca=:galec_sca, deno_sca=:deno_sca, ad1_sca=:ad1_sca, ad2_sca=:ad2_sca, cp_sca=:cp_sca, ville_sca=:ville_sca, sorti=:sorti,
     date_fermeture=:date_fermeture, date_ouverture=:date_ouverture, date_update=:date_update WHERE btlec_sca=:btlec_sca");
    $req->execute([
        ':btlec_sca'        =>$btlec,
        ':galec_sca'        =>$galec,
        ':deno_sca'     =>$deno,        
        ':ad1_sca'      =>$ad1,
        ':ad2_sca'        => $ad2,
        ':cp_sca'       =>$cp,
        ':ville_sca'        =>$ville,
        ':sorti'       =>$gel, 
        ':date_fermeture'       =>$dateFermeture,
        ':date_ouverture'       =>$dateOuverture,
        ':date_update'       =>date('Y-m-d H:i:s')
    ]);
    return $req->rowCount();


}
// date_sortie
// date_resiliation,
// date_adhesion,
// mandat? 
// apple_id

function insertSca3($pdoMag, $btlec, $galec, $deno, $centrale, $ad1, $ad2, $cp, $ville,  $gel, $dateFermeture, $dateOuverture, $sav, $backoffice){
    $req=$pdoMag->prepare("INSERT INTO 
        sca3 (btlec_sca, galec_sca, deno_sca, centrale_sca, ad1_sca, ad2_sca, cp_sca, ville_sca, sorti,
          date_fermeture, date_ouverture, pole_sav, backoffice_sca, id_cm, date_insert
         ) VALUES (:btlec_sca, :galec_sca, :deno_sca, :centrale_sca, :ad1_sca, :ad2_sca, :cp_sca, :ville_sca, :sorti, :date_fermeture,:date_ouverture,:pole_sav, :antenne_sav, :backoffice_sca, :date_insert)");
    
    $req->execute([
        ':btlec_sca'        =>$btlec,
        ':galec_sca'        =>$galec,
        ':deno_sca'     =>$deno,
        ':centrale_sca'     =>$centrale,
        ':ad1_sca'      =>$ad1,
        ':ad2_sca'        => $ad2,
        ':cp_sca'       =>$cp,
        ':ville_sca'        =>$ville,
        ':sorti'       =>$gel, 
        ':date_fermeture'       =>$dateFermeture,
        ':date_ouverture'       =>$dateOuverture,
        ':pole_sav'     =>$sav,
        ':antenne_sav'     =>$sav,
        ':backoffice_sca'       =>$backoffice,
        ':date_insert'       =>date('Y-m-d H:i:s')
    ]);
    return $pdoMag->lastInsertId();
}

function isMagInsca3($pdoMag, $btlec){
    $req=$pdoMag->prepare("SELECT * FROM sca3 WHERE btlec_sca= :btlec_sca");
    $req->execute([
        ':btlec_sca'        =>$btlec
    ]);
    return $req->fetch();


}
function updateOneField($pdoMag, $btlec, $field, $value){
    $req=$pdoMag->query("UPDATE sca3 SET {$field}={$value} WHERE btlec_sca={$btlec}");
    return $req->rowCount();     
}
$magsInMag=getMagFromMag($pdoMag);
foreach ($magsInMag as $key => $mag) {
    $magInsca3=isMagInsca3($pdoMag,$mag['id']);
    if(empty($magInsca3)){
        echo "insert".$mag['id'];
        insertSca3($pdoMag, $mag['id'], $mag['galec'], $mag['deno'], $mag['centrale'], $mag['ad1'], $mag['ad2'], $mag['cp'], $mag['ville'],  $mag['gel'], $mag['date_ferm'], $mag['date_ouv'], $mag['pole_sav_gessica'], $mag['backoffice']);
        echo "<br>";


    }else{
        echo "update ".$magInsca3['btlec_sca'];
        updateSca3($pdoMag, $magInsca3['btlec_sca'], $mag['galec'], $mag['deno'], $mag['ad1'], $mag['ad2'], $mag['cp'], $mag['ville'], $mag['gel'], $mag['date_ferm'], $mag['date_ouv']);
        // mise à jour des champs que l'on peut désynchroniser par rapport à gessica
        // si les champs ont des valeurs dans  la table sca3, on ne les mets pas à jour
         echo "<br>";

        if($mag['centrale']!=null && $magInsca3['centrale_sca']==null){
            updateOneField($pdoMag,$magInsca3['btlec_sca'], 'centrale_sca', $mag['centrale']);
            echo "update centrale_sca";
             echo "<br>";

        }
        if($mag['pole_sav_gessica']!=null && $magInsca3['pole_sav']==null){
            updateOneField($pdoMag,$magInsca3['btlec_sca'], 'pole_sav', $mag['pole_sav_gessica']);
            updateOneField($pdoMag,$magInsca3['btlec_sca'], 'antenne_sav', $mag['pole_sav_gessica']);
            echo "update pole sav";
            echo "<br>";
        
        }

        if($mag['backoffice']!=null && $magInsca3['backoffice_sca']==null){
            updateOneField($pdoMag,$magInsca3['btlec_sca'], 'backoffice_sca', $mag['backoffice']);
            echo "update backoffice";
            echo "<br>";

        }


// si videmaj ':centrale_sca'     =>$centrale,
// pole_sav et antenne_sav  sinon ne change pas
// ':backoffice_sca'       =>$backoffice,
// 
    }
     echo "<br>";
}

