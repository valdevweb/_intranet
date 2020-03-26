<?php

if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}

include 'config\config.inc.php';
include 'functions\tasklog.fn.php';



function getQlik($pdoQlik){
	$req=$pdoQlik->query("SELECT mag_gessica.id as id, mag_ctbt_param.id  as ctbtparam_id, mag_ctbt.id as ctbt_id, ADH_RS, ADH_PANBT, ADH_VALCOD, ADH_ADR1, ADH_ADR2, ADH_CP,ADH_ADR3, ADH_TEL, ADH_TLC,ADH_SURF,ADH_NOMADH, ADH_NOMCHEF,		DIC_GEL, ADH_DATOUV, ADH_DATFER, ADH_NUMACT, AAC_COD, ADH_NUMORD, ADH_EAN, ADH_CSIRET, ADH_IBAN, ADH_BIC, ADH_RUM, ADH_ADHPYR, BCG_ADH,
		PID_CRE, PID_RAD
		FROM mag_gessica LEFT JOIN mag_ctbt ON mag_gessica.id= mag_ctbt.id LEFT JOIN mag_ctbt_param ON mag_gessica.id= mag_ctbt_param.id  WHERE (mag_gessica.id >2 AND mag_gessica.id <1000) OR (mag_gessica.id >3000 AND mag_gessica.id <5000) OR  (mag_gessica.id >5999 AND mag_gessica.id <7999)");

	// return $req->errorInfo();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



function addMag($pdoMag,$dateOuv, $dateFerm, $mag){
	$closed=0;
	$galec = str_pad($mag['ADH_PANBT'], 4, '0', STR_PAD_LEFT);
	$valcod=explode(';',$mag['ADH_VALCOD']);
	$poleSav=$valcod[2];
	$idCentrale=$valcod[3];
	$firstLetter=substr($mag['ADH_RS'],0,1);
	if($firstLetter =="*" ||$firstLetter=="x"){
		$closed=1;
	}
	$centreRei=(empty($mag['PID_CRE'])) ? NULL : $mag['PID_CRE'];
	$rei=(empty($mag['PID_RAD'])) ? NULL : $mag['PID_RAD'];

	$req=$pdoMag->prepare("INSERT INTO mag(id,deno, galec, centrale, ad1, ad2, cp, ville, tel, fax, surface, adherent, directeur,pole_sav_gessica,closed,
		gel, date_ouv, date_ferm, acdlec_activite, acdlec_code, acdlec_numord, ean, siret , tva, centre_rei, rei, iban, bic, rum, adh_payeur,
		date_insert) VALUES (:id, :deno, :galec, :centrale, :ad1, :ad2, :cp, :ville, :tel, :fax, :surface, :adherent, :directeur, :pole_sav_gessica,:closed,
		:gel, :date_ouv, :date_ferm, :acdlec_activite, :acdlec_code, :acdlec_numord, :ean, :siret , :tva, :centre_rei, :rei, :iban, :bic, :rum, :adh_payeur,
		:date_insert)");
	$req->execute([
		':id'			=>$mag['id'] ,
		':deno'			=>$mag['ADH_RS'],
		':galec'			=>$galec,
		':centrale'			=>$idCentrale,
		':ad1'			=> $mag['ADH_ADR1'] ,
		':ad2'			=>$mag['ADH_ADR2'],
		':cp'			=>$mag['ADH_CP'],
		':ville'			=>$mag['ADH_ADR3'] ,
		':tel'			=>$mag['ADH_TEL'] ,
		':fax'			=>$mag['ADH_TLC'],
		':surface'			=>$mag['ADH_SURF'] ,
		':adherent'			=>$mag['ADH_NOMADH'],
		':directeur'			=>$mag['ADH_NOMCHEF'],
		':pole_sav_gessica'				=>$poleSav,
		':closed'				=>$closed,
		':gel'	=>$mag['DIC_GEL'],
		':date_ouv'	=>$dateOuv,
		':date_ferm'	=>$dateFerm,
		':acdlec_activite'	=>$mag['ADH_NUMACT'],
		':acdlec_code'	=>$mag['AAC_COD'],
		':acdlec_numord'	=>$mag['ADH_NUMORD'],
		':ean'	=>$mag['ADH_EAN'],
		':siret'	=>$mag['ADH_CSIRET'],
		':tva'		=>$mag['BCG_ADH'],
		':centre_rei'	=>$centreRei,
		':rei'	=>$rei,
		':iban'	=>$mag['ADH_IBAN'],
		':bic'	=>$mag['ADH_BIC'],
		':rum'	=>$mag['ADH_RUM'],
		':adh_payeur'	=>$mag['ADH_ADHPYR'],
		':date_insert'				=>date('Y-m-d H:i:s')

	]);
	// return $req->errorInfo();

	$err=$req->errorInfo();
	if(!empty($err[2])){
		return $err[2];
	}
	return $req->rowCount();
}
function updateMag($pdoMag,$dateOuv, $dateFerm, $mag){
	$closed=0;
	$galec = str_pad($mag['ADH_PANBT'], 4, '0', STR_PAD_LEFT);
	$valcod=explode(';',$mag['ADH_VALCOD']);
	$poleSav=$valcod[2];
	$idCentrale=$valcod[3];
	$firstLetter=substr($mag['ADH_RS'],0,1);
		$centreRei=(empty($mag['PID_CRE'])) ? NULL : $mag['PID_CRE'];
	$rei=(empty($mag['PID_RAD'])) ? NULL : $mag['PID_RAD'];

	if($firstLetter =="*" ||$firstLetter=="x"){
		$closed=1;
	}
	$req=$pdoMag->prepare("UPDATE mag SET deno= :deno, galec= :galec, centrale= :centrale, ad1= :ad1, ad2= :ad2, cp= :cp , ville= :ville, tel= :tel, fax= :fax, surface= :surface, adherent= :adherent, directeur= :directeur, pole_sav_gessica= :pole_sav_gessica,closed= :closed,
		gel= :gel, date_ouv= :date_ouv, date_ferm= :date_ferm, acdlec_activite= :acdlec_activite, acdlec_code= :acdlec_code, acdlec_numord= :acdlec_numord, ean= :ean, siret= :siret , tva= :tva, centre_rei= :centre_rei, rei= :rei, iban= :iban, bic= :bic, rum= :rum, adh_payeur= :adh_payeur, date_update= :date_update WHERE id= :id");
	$sql=$req->execute([
		':id'				=>$mag['id'] ,
		':deno'				=>$mag['ADH_RS'],
		':galec'			=>$galec,
		':centrale'			=>$idCentrale,
		':ad1'				=> $mag['ADH_ADR1'] ,
		':ad2'				=>$mag['ADH_ADR2'],
		':cp'				=>$mag['ADH_CP'],
		':ville'			=>$mag['ADH_ADR3'] ,
		':tel'				=>$mag['ADH_TEL'] ,
		':fax'				=>$mag['ADH_TLC'],
		':surface'			=>$mag['ADH_SURF'] ,
		':adherent'			=>$mag['ADH_NOMADH'],
		':directeur'		=>$mag['ADH_NOMCHEF'],
		':pole_sav_gessica'	=>$poleSav,
		':closed'			=>$closed,
		':gel'				=>$mag['DIC_GEL'],
		':date_ouv'			=>$dateOuv,
		':date_ferm'		=>$dateFerm,
		':acdlec_activite'	=>$mag['ADH_NUMACT'],
		':acdlec_code'		=>$mag['AAC_COD'],
		':acdlec_numord'	=>$mag['ADH_NUMORD'],
		':ean'				=>$mag['ADH_EAN'],
		':siret'			=>$mag['ADH_CSIRET'],
		':tva'				=>$mag['BCG_ADH'],
		':centre_rei'		=>$centreRei,
		':rei'				=>$rei,
		':iban'				=>$mag['ADH_IBAN'],
		':bic'				=>$mag['ADH_BIC'],
		':rum'				=>$mag['ADH_RUM'],
		':adh_payeur'		=>$mag['ADH_ADHPYR'],
		':date_update'		=>date('Y-m-d H:i:s')
	]);
	// return $req->errorInfo();

	$err=$req->errorInfo();
	if(!empty($err[2])){
			// echo "<pre>";
			// print_r($sql);
			// echo '</pre>';

		return $err[2];
	}
	return $req->rowCount();


}




function alreadyInMag($pdoMag,$id){
	$req=$pdoMag->query("SELECT id FROM mag WHERE id={$id}");
	$data=$req->fetch();
	if(!empty($data)){
		return true;
	}
	return false;

}


function convertToDate($data){
	$date=NULL;
	if(!empty($data)){
		$date=DateTime::createFromFormat('d/m/Y', $data);
		return $date->format("Y-m-d");
	}

	return $date;
}

// interroge table qlik
$listMag=getQlik($pdoQlik);
$magAdded=0;
$magUpdated=0;
$errArr=[];
$rowUp=0;
$rowIns=0;


// insertion ou update de qlik vers magasin mag
foreach ($listMag as $key => $mag) {
	if(alreadyInMag($pdoMag, $mag['id'])){
		$dateOuv=convertToDate($mag['ADH_DATOUV']);
		$dateFerm=convertToDate($mag['ADH_DATFER']);
		$updated=updateMag($pdoMag,$dateOuv, $dateFerm, $mag);
		if($updated!=1){
			echo 'impossible de mettre à jour le magasin '.$mag['id'].'<br>';
			$errArr[$rowUp]['btlec']=$mag['id'];
			$errArr[$rowUp]['deno']=$mag['ADH_RS'];
			$errArr[$rowUp]['msg']="updateMag " .$updated;
			$errArr[$rowUp]['db']="mag";
			$rowUp++;

		}else{
			$magUpdated++;
		}
	}else{
		// echo 'inser'. $mag['id'];
		// echo "<br>";

		$dateOuv=convertToDate($mag['ADH_DATOUV']);
		$dateFerm=convertToDate($mag['ADH_DATFER']);
		$added=addMag($pdoMag, $dateOuv, $dateFerm, $mag);
		if($added!=1){
			echo 'impossible d\'ajouter le magasin '.$mag['id'].'<br>';
			$errArr[$rowIns]['btlec']=$mag['id'];
			$errArr[$rowIns]['deno']=$mag['ADH_RS'];
			$errArr[$rowIns]['msg']="addMag " . $added;
			$errArr[$rowIns]['db']="mag";
			$rowIns++;


		}else{
			$magAdded++;
		}
	}

}
echo 'Nombre de mag ajouté '. $magAdded .'<br>';
echo 'Nombre de mag mis à jour '. $magUpdated;



if(empty($errArr)){
	$logfile="";
	$idTask=27;
	$ko=0;
	insertTaskLog($pdoExploit,$idTask, $ko, $logfile);
}else{
	$logfileName=$idTask.'-'.date('YmdHis').'.csv';
	$logfile=DIR_LOGFILES.$logfileName;
	$idTask=27;
	$ko=1;
	$file = fopen($logfile, "w") or die("Unable to open file!");
	foreach ($errArr as $key => $value) {
		fputcsv($file, $value);
	}
	fclose($file);
	insertTaskLog($pdoExploit,$idTask, $ko, $logfileName);
}





?>


