<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';
include 'batch-mag\utils.fn.php';



function getOldScaTrois($pdoBt){
	$req=$pdoBt->query("SELECT *, sca3.id as id_sca FROM sca3 LEFT JOIN infosnumbt ON sca3.btlec=infosnumbt.NumBT LEFT JOIN infospanonceau ON sca3.galec=infospanonceau.Panonceau WHERE sca3.btlec !=''");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function alreadyInNewSca($pdoMag,$id){
	$req=$pdoMag->query("SELECT id_sca FROM sca3 WHERE id_sca={$id}");
	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;
}


function insertNewSca($pdoMag, $data,$centraleSca,$centraleDoris,$sorti,$dateSortie,$dateResiliation,$dateAdhesion,$affilie,$dateFermeture,$dateOuverture,$centraleSmiley,$poleSavSca){
	$req=$pdoMag->prepare("INSERT INTO sca3 (id_sca, btlec_sca, galec_sca, deno_sca, centrale_sca, ad1_sca, ad2_sca, ad3, cp_sca, ville_sca, tel_sca, fax_sca, surface_sca, adherent_sca, nom_gesap, lotus_rbt, obs, galec_old, centrale_doris, sorti, date_sortie, raison_sociale, mandat, date_resiliation, date_adhesion, affilie, date_fermeture, date_ouverture, docubase_login, docubase_pwd, apple_id, mots_cles, pole_sav_sca, centrale_smiley, racine_list, date_insert) VALUES (:id_sca, :btlec_sca, :galec_sca, :deno_sca, :centrale_sca, :ad1_sca, :ad2_sca, :ad3, :cp_sca, :ville_sca, :tel_sca, :fax_sca, :surface_sca, :adherent_sca, :nom_gesap, :lotus_rbt, :obs, :galec_old, :centrale_doris, :sorti, :date_sortie, :raison_sociale, :mandat, :date_resiliation, :date_adhesion, :affilie, :date_fermeture, :date_ouverture, :docubase_login, :docubase_pwd, :apple_id, :mots_cles, :pole_sav_sca, :centrale_smiley, :racine_list, :date_insert)");

	$req->execute([
				':id_sca'		=> $data['id_sca'],
		':btlec_sca'		=> $data['btlec'],
		':galec_sca'		=> $data['galec'],
		':deno_sca'		=> trim($data['mag']),
		':centrale_sca'		=> $centraleSca,
		':ad1_sca'		=> $data['ad1'],
		':ad2_sca'		=> $data['ad2'],
		':ad3'		=> $data['ad3'],
		':cp_sca'		=> $data['cp'],
		':ville_sca'		=> $data['city'],
		':tel_sca'		=> $data['tel'],
		':fax_sca'		=> $data['fax'],
		':surface_sca'		=> $data['surface'],
		':adherent_sca'		=> $data['adherent'],
		':nom_gesap'		=> $data['nom_gesap'],
		':lotus_rbt'		=> $data['lotus_rbt'],
		':obs'		=> $data['obs'],
		':galec_old'		=> $data['galec_old'],
		':centrale_doris'		=> $centraleDoris,
		':sorti'		=> $sorti,
		':date_sortie'		=> $dateSortie,
		':raison_sociale'		=> $data['Raison sociale'],
		':mandat'		=> $data['MandatC3S'],
		':date_resiliation'		=> $dateResiliation,
		':date_adhesion'		=> $dateAdhesion,
		':affilie'		=> $affilie,
		':date_fermeture'		=> $dateFermeture,
		':date_ouverture'		=> $dateOuverture,
		':docubase_login'		=> $data['docubase_login'],
		':docubase_pwd'		=> $data['docubase_pwd'],
		':apple_id'		=> $data['apple_id'],
		':mots_cles'		=> $data['mots_cles'],
		':pole_sav_sca'		=> $poleSavSca,
		':centrale_smiley'		=> $centraleSmiley,
		':racine_list'		=> $data['RacineListe'],
		':date_insert'		=> date('Y-m-d H:i:s')

	]);

	$count=$req->rowCount();
	if($count!=1){
		return $req->errorInfo();
	}
	return $count;

}

function updateNewSca($pdoMag, $data,$centraleSca,$centraleDoris,$sorti,$dateSortie,$dateResiliation,$dateAdhesion,$affilie,$dateFermeture,$dateOuverture,$centraleSmiley,$poleSavSca){
	$req=$pdoMag->prepare("UPDATE sca3 SET btlec_sca= :btlec_sca, galec_sca= :galec_sca, deno_sca= :deno_sca, centrale_sca= :centrale_sca, ad1_sca= :ad1_sca, ad2_sca= :ad2_sca, ad3= :ad3, cp_sca= :cp_sca, ville_sca= :ville_sca, tel_sca= :tel_sca, fax_sca= :fax_sca, surface_sca= :surface_sca, adherent_sca= :adherent_sca, nom_gesap= :nom_gesap, lotus_rbt= :lotus_rbt, obs= :obs, galec_old= :galec_old, centrale_doris= :centrale_doris, sorti= :sorti, date_sortie= :date_sortie, raison_sociale= :raison_sociale, mandat= :mandat, date_resiliation= :date_resiliation, date_adhesion= :date_adhesion, affilie= :affilie, date_fermeture= :date_fermeture, date_ouverture= :date_ouverture, docubase_login= :docubase_login, docubase_pwd= :docubase_pwd, apple_id= :apple_id, mots_cles= :mots_cles, pole_sav_sca= :pole_sav_sca, centrale_smiley= :centrale_smiley, racine_list= :racine_list, date_update= :date_update WHERE id_sca= :id_sca");

	$req->execute([
		':id_sca'		=> $data['id_sca'],
		':btlec_sca'		=> $data['btlec'],
		':galec_sca'		=> $data['galec'],
		':deno_sca'		=> trim($data['mag']),
		':centrale_sca'		=> $centraleSca,
		':ad1_sca'		=> $data['ad1'],
		':ad2_sca'		=> $data['ad2'],
		':ad3'		=> $data['ad3'],
		':cp_sca'		=> $data['cp'],
		':ville_sca'		=> $data['city'],
		':tel_sca'		=> $data['tel'],
		':fax_sca'		=> $data['fax'],
		':surface_sca'		=> $data['surface'],
		':adherent_sca'		=> $data['adherent'],
		':nom_gesap'		=> $data['nom_gesap'],
		':lotus_rbt'		=> $data['lotus_rbt'],
		':obs'		=> $data['obs'],
		':galec_old'		=> $data['galec_old'],
		':centrale_doris'		=> $centraleDoris,
		':sorti'		=> $sorti,
		':date_sortie'		=> $dateSortie,
		':raison_sociale'		=> $data['Raison sociale'],
		':mandat'		=> $data['MandatC3S'],
		':date_resiliation'		=> $dateResiliation,
		':date_adhesion'		=> $dateAdhesion,
		':affilie'		=> $affilie,
		':date_fermeture'		=> $dateFermeture,
		':date_ouverture'		=> $dateOuverture,
		':docubase_login'		=> $data['docubase_login'],
		':docubase_pwd'		=> $data['docubase_pwd'],
		':apple_id'		=> $data['apple_id'],
		':mots_cles'		=> $data['mots_cles'],
		':pole_sav_sca'		=> $poleSavSca,
		':centrale_smiley'		=> $centraleSmiley,
		':racine_list'		=> $data['RacineListe'],
		':date_update'		=> date('Y-m-d H:i:s')

	]);
	$count=$req->rowCount();
	if($count!=1){
		return $error=$req->errorInfo();
	}
	return $count;

}




$oldScaTrois=getOldScaTrois($pdoBt);


$centraleList=getCentrales($pdoMag);
$updated=0;
$inserted=0;
$errArr=[];

foreach ($oldScaTrois as $key => $oldMag) {
	$centraleSca=convertCentrale($oldMag['centrale'], $centraleList);
	$centraleDoris=convertCentrale($oldMag['centrale_doris'], $centraleList);
	$sorti=convertTrueFalse($oldMag['sorti']);
	$dateSortie=convertToDate(trim($oldMag['date_sortie']));
	$dateResiliation=convertToDate($oldMag['DateResiliation']);
	$dateAdhesion=convertToDate($oldMag['DateAdhesion']);
	$affilie=($oldMag['Affilie']==2)? 1 :0;
	$dateFermeture=convertToDate($oldMag['DateFermeture']);
	$dateOuverture=convertToDate($oldMag['DateOuverture']);
	$centraleSmiley=convertCentrale($oldMag['CentraleSmiley'], $centraleList);
	$poleSavSca=empty($oldMag['PoleSAV'])? NULL:$oldMag['PoleSAV'];
	if(alreadyInNewSca($pdoMag,$oldMag['id_sca'])){
		$update=updateNewSca($pdoMag, $oldMag, $centraleSca, $centraleDoris, $sorti, $dateSortie, $dateResiliation, $dateAdhesion, $affilie, $dateFermeture, $dateOuverture, $centraleSmiley, $poleSavSca);
		if($update==1){
			$updated++;
		}else{
			$errArr[$row]['btlec']=$oldMag['btlec'];
			$errArr[$row]['deno']=$oldMag['mag'];
			$errArr[$row]['msg']="impossible de mettre à jour le magasin";
			$errArr[$row]['db']="sca3";

		}

	}else{
		$insert=insertNewSca($pdoMag, $oldMag, $centraleSca, $centraleDoris, $sorti, $dateSortie, $dateResiliation, $dateAdhesion, $affilie, $dateFermeture, $dateOuverture, $centraleSmiley, $poleSavSca);
		if($insert==1){
			$inserted++;
		}else{
		$errArr[$row]['btlec']=$oldMag['btlec'];
			$errArr[$row]['deno']=$oldMag['mag'];
			$errArr[$row]['msg']="impossible d'ajouter le magasin";
			$errArr[$row]['db']="sca3";
		}

	}
}

	echo "inserés " .$inserted;
	echo "<br>";

	echo "update " .$updated;


if(empty($errArr)){
	$logfile="";
	$idTask=28;
	$ko=0;
	insertTaskLog($pdoExploit,$idTask, $ko, $logfile);
}else{
	$logfileName=$idTask.'-'.date('YmdHis').'.csv';
	$logfile=DIR_LOGFILES.$logfileName;
	$idTask=28;
	$ko=1;
	$file = fopen($logfile, "w") or die("Unable to open file!");
	foreach ($errArr as $key => $value) {
		fputcsv($file, $value);
	}
	fclose($file);
	insertTaskLog($pdoExploit,$idTask, $ko, $logfileName);
}