<?php
include 'config.inc.php';

$fieldseparator = ",";
$lineseparator = "\n";


$importDir="D:\btlec\dumps\gessica\\";
// vérif si fichier à la date du jour

$arrFilename=["SBBCFMAG.txt","SBBCFPID.txt","SCEBFADH.txt"];
$ctbtFile=$importDir.$arrFilename[0];
$ctbtParamFile=$importDir.$arrFilename[1];
$gessicaFile=$importDir.$arrFilename[2];

$ctbtParamFieldsArr=
['id','PID_RAISOC','PID_ENSEIG','PID_ADR1','PID_ADR2','PID_PAY','PID_CPO','PID_TLP','PID_TLC','PID_TLX','PID_TVAINT','CRE_OPE','CRE_DATE','CRE_HEURE','MAJ_DATE','MAJ_HEURE','MAJ_COD','PID_TRFENT','PID_TRFBO','PID_TRFCOM','PID_TRFCAE','PID_IMPETI','PID_CODREF','PID_DEVGES','PID_DELRLQ','PID_INS','PID_FACSAV','PID_ECHPIC','PID_ECHMAS','PID_TVA','PID_FORLIV','PID_VTECDE','PID_TRFSAV','PID_EXTSAV','PID_LIVDIR','PID_ARCOPL','PID_ARMAPL','PID_FACTSA','PID_CALGUE','PID_POINT','PID_DATGUE','PID_TRFCAI','PID_MAGFAC','PID_FACFIN','PID_MAGSTO','PID_CRE','PID_RAD','PID_SENCAI','PID_TYPMRG','PID_HLE','PID_DISENG','PID_GESGT','PID_EAN','PID_PIEFAC','PID_GESCAE','PID_TEXTENT1','PID_TEXTENT2','PID_TEXTENT3','PID_ORDCHQ','PID_EDTGAR','PID_TXTREM','PID_TXTRPV','PID_TXTRPA','PID_TXTEXP','PID_TXTGAR','PID_DATED','PID_DATEP','PID_DATER','PID_NOFAC','PID_DEBFAC','PID_FINFAC','PID_DEBCOT','PID_FINCOT','PID_GUELIV','PID_CON','PID_TVACOM','PID_GTRLVS','PID_VILLE','PID_ENSSAV','PID_DEBSAV','PID_FINSAV','PID_IDEBB','PID_DEBAVS','PID_FINAVS','PID_ECHAVS','PID_GESRES','PID_TYPCEN','PID_IDIV1','PID_IDIV2','PID_CDIV1','PID_CDIV2','date_import'];
$ctbtParamArgs=join(',', array_map(function(){return '?';},$ctbtParamFieldsArr));
$ctbtParamFields=implode(', ',$ctbtParamFieldsArr);
$gessicaFieldsArr=['id', 'ADH_PAN', 'ADH_NOMADH', 'ADH_NOMCHEF', 'ADH_RS', 'ADH_ADR1', 'ADH_ADR2', 'ADH_CP', 'ADH_ADR3', 'PAY_COD', 'ADH_TEL', 'ADH_TLC', 'ADH_TLX', 'ADH_ADH', 'ADH_MOTAPL', 'DIC_GEL', 'DIC_HYPSUP', 'ADH_SURF', 'ADH_KM', 'ADH_CNUD', 'GEC_COD', 'DIC_CONGAQ', 'ADH_ADHPYR', 'ADH_NOMBAN', 'ADH_CODBAN', 'ADH_CODGUI', 'ADH_CPTBAN', 'ADH_CLERIB', 'ADH_PRL', 'ADH_NBJECH', 'DIC_TRTECH', 'ADH_DATECH', 'ADH_DATOUV', 'ADH_DATFER', 'ADH_OBS', 'MAJ_DATCRE', 'MAJ_OPECRE', 'MAJ_OPEMAJ', 'MAJ_COD', 'MAJ_DATE', 'MAJ_HEURE', 'ADH_BLCPRL', 'ADH_DATDBL', 'DIC_APLADH', 'ADH_ANCPAN', 'ADH_EAN', 'ADH_AR', 'ADH_NOMUTIL', 'ADH_BLBSCO', 'ADH_DATSIT', 'ADH_DATFINEX', 'ADH_TELABR', 'ADH_TLCABR', 'DIC_CSITPAY', 'DIC_CSITENS', 'DIC_CSITNATS', 'ADH_CSITDPT', 'ADH_CSIRET', 'ADH_CCPT', 'ADH_CSITNUM', 'ADH_ENVFACMAG', 'ADH_ENVBLI', 'ADH_BOOL', 'ADH_VALCOD', 'BCG_ADH', 'ADH_ENVMAJC', 'ADH_ENVMAJCFEL', 'ADH_VITESSE', 'DOS_SUIVIENVOI', 'VIT_COD', 'BCT_TYPREL', 'ADH_SOUMICOT', 'ADH_RES', 'DIC_TYPDEST', 'POT_COD', 'ADH_ECH', 'ADH_TYPADH', 'ADH_PANBT', 'ADH_EXREV', 'ADH_GESAPPAN', 'ADH_ADHREMP', 'ADH_DATREMP', 'BAD_COD', 'DIC_ADHRIB', 'ADH_CENTSPE', 'DIC_TYPADHPYR', 'ADH_MODPRP', 'ADH_TPARAM', 'DIC_TYPMAG', 'ADH_EMB', 'DIC_GESADHPYR', 'ADH_EMBPYR', 'ADH_STAT', 'ADH_REPCDE', 'ADH_CA', 'ADH_ZONE1', 'ADH_BLOCC3SFDM', 'ADH_PANBO', 'ADH_DAT1LIV', 'ADH_ANTIC3S', 'GLM_COD', 'DIC_DEMAT', 'ADH_ANCPANBT', 'ADH_TRIMAGBO', 'ADH_ZONE2', 'ADH_ZONE3', 'ADH_EMAILADH', 'ADH_NOMCODE', 'ADH_DEMAT', 'ADH_LIENHYP', 'ADH_NUMACDL', 'ADH_NUMACT', 'AAC_COD', 'ADH_NUMORD', 'ADH_ZONE4', 'ADH_ZONE5', 'ADH_ZONE6', 'ADH_ZONE7', 'ADH_BIC', 'ADH_IBAN', 'ADH_QUOTAMAXCDE', 'ADH_CTRLREQ', 'ADH_CPTGALEC', 'ADH_RUM', 'ADH_DEBGESADHPYR', 'ADH_FINGESADHPYR', 'ADH_ANCEAN', 'date_import'];
$gessicaArgs=join(',', array_map(function(){return '?';},$gessicaFieldsArr));
$gessicaFields=implode(",",$gessicaFieldsArr);

$row=0;
// if (($handle = fopen($ctbtFile, "r")) !== FALSE) {
//     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//         if($row==0){
//             $row++;
//         }else{
//             $req=$pdoQlik->prepare("INSERT INTO mag_ctbt(id, MAG_LIB, MAG_LIBR, MAG_MAI, MAG_PAN, MAG_ANCPAN, MAG_SCA, MAG_BAS, MAG_TYPINF, CRE_OPE, CRE_DATE, CRE_HEURE, MAJ_OPE, MAJ_DATE, MAJ_HEURE, MAJ_COD, MAG_LIVSCA, MAG_PANGAL, date_import) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
//             if($req->execute([
//                 $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16], $data[17],date('Y-m-d H:i:s')
//             ])){

//             }else{

//                 $err=$req->errorInfo();
//                 $errArr[$row]['id']=$data[0];
//                 $errArr[$row]['code']=$err[1];
//                 $errArr[$row]['message']=$err[2];
//             }
//         }
//         $row++;
//     }
//     $row=0;
//     fclose($handle)
// }
// if (($handle = fopen($ctbtParamFile, "r")) !== FALSE) {
//     while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
//         if($row==0){
//             $row++;
//         }else{
//             if(!empty($data[0])){
//                 array_push($data,date("Y-m-d H:i:s"));
//                 $req=$pdoQlik->prepare("INSERT INTO mag_ctbt_param($ctbtParamFields) VALUES ($ctbtParamArgs)");
//                 if($req->execute($data)){

//                 }else{

//                     $err=$req->errorInfo();
//                     $errArr[$row]['id']=$data[0];
//                     $errArr[$row]['code']=$err[1];
//                     $errArr[$row]['message']=$err[2];

//                 }
//             }

//         }
//         $row++;
//     }
//     $row=0;
//     fclose($handle);
// }



if (($handle = fopen($gessicaFile, "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
		if($row==0){
			$row++;
		}else{

				array_push($data,date("Y-m-d H:i:s"));
				$req=$pdoQlik->prepare("INSERT INTO mag_gessica($gessicaFields) VALUES ($gessicaArgs)");
				if($req->execute($data)){

				}else{

					$err=$req->errorInfo();
					$errArr[$row]['id']=$data[0];
					$errArr[$row]['code']=$err[1];
					$errArr[$row]['message']=$err[2];
						echo "<pre>";
						print_r($errArr);
						echo '</pre>';


				}


		}
		$row++;
	}
	$row=0;
	fclose($handle);

// while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
// 		  $colTotal = count($data);
//         echo "<p> $colTotal champs à la ligne $row: <br /></p>\n";
//         $row++;
//         for ($col=0; $col < $colTotal; $col++) {
//         	if($col==1 || $col==63){
//             echo $data[$col] . "<br />\n";

//         	}
//         }
//     }
//     fclose($handle);


}
