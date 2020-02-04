



  <?php
include 'config.inc.php';




$importDir="D:\btlec\dumps\gessica\\";
// vérif si fichier à la date du jour

$arrFilename=["SBBCFMAG.txt","SBBCFPID.txt","SCEBFADH.txt"];

$row=0;
// if (($handle = fopen($importDir.$arrFilename[2], "r")) !== FALSE) {

//     while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
//     	if($row==0){
//     			$row++;
//     	}else{

//     		    	$req=$pdoQlik->prepare("INSERT INTO mag_gessica(id, galec, adherent, directeur, deno, ad1, ad2, cp, ville, pays, tel, fax, ADH_TLX, ADH_ADH, motdappel, gel, type_surface, surface, km, ADH_CNUD, GEC_COD, DIC_CONGAQ, adherent_payeur, banque, code_banque, code_guichet, compte, rib, prevelement, ADH_NBJECH, DIC_TRTECH, ADH_DATECH, date_ouverture, date_fermeture, observation, created_on, created_by, modified_by, MAJ_COD, modified_on, MAJ_HEURE, blocage_prelevement, ADH_DATDBL, id_backoffice, btlec_old, ean, ADH_AR, ADH_NOMUTIL, ADH_BLBSCO, ADH_DATSIT, ADH_DATFINEX, ADH_TELABR, ADH_TLCABR, DIC_CSITPAY, DIC_CSITENS, DIC_CSITNATS, ADH_CSITDPT, ADH_CSIRET, ADH_CCPT, ADH_CSITNUM, ADH_ENVFACMAG, ADH_ENVBLI, ADH_BOOL, ADH_VALCOD, BCG_ADH, ADH_ENVMAJC, ADH_ENVMAJCFEL, ADH_VITESSE, DOS_SUIVIENVOI, VIT_COD, BCT_TYPREL, ADH_SOUMICOT, ADH_RES, DIC_TYPDEST, POT_COD, ADH_ECH, ADH_TYPADH, ADH_PANBT, ADH_EXREV, ADH_GESAPPAN, ADH_ADHREMP, ADH_DATREMP, BAD_COD, DIC_ADHRIB, ADH_CENTSPE, DIC_TYPADHPYR, ADH_MODPRP, ADH_TPARAM, DIC_TYPMAG, ADH_EMB, DIC_GESADHPYR, ADH_EMBPYR, ADH_STAT, ADH_REPCDE, ADH_CA, ADH_ZONE1, ADH_BLOCC3SFDM, ADH_PANBO, ADH_DAT1LIV, ADH_ANTIC3S, GLM_COD, DIC_DEMAT, ADH_ANCPANBT, ADH_TRIMAGBO, ADH_ZONE2, ADH_ZONE3, ADH_EMAILADH, ADH_NOMCODE, ADH_DEMAT, ADH_LIENHYP, ADH_NUMACDL, ADH_NUMACT, AAC_COD, ADH_NUMORD, ADH_ZONE4, ADH_ZONE5, ADH_ZONE6, ADH_ZONE7, ADH_BIC, ADH_IBAN, ADH_QUOTAMAXCDE, ADH_CTRLREQ, ADH_CPTGALEC, ADH_RUM, ADH_DEBGESADHPYR, ADH_FINGESADHPYR, ADH_ANCEAN) VALUES
//     	($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],
// $data[10],$data[11],$data[12],$data[13],$data[14],$data[15],$data[16],$data[17],$data[18],$data[19],$data[20],$data[22],$data[23],$data[24],$data[25],$data[26],$data[27],$data[28],$data[29],$data[30],$data[31],$data[32],$data[33],$data[34],$data[35],$data[36],$data[37],$data[38],$data[39],$data[40],$data[41],$data[42],$data[43],$data[44],$data[45],$data[46],$data[47],$data[48],$data[49],$data[50],$data[51],$data[52],$data[53],$data[54],$data[55],$data[56],$data[57],$data[58],$data[59],$data[60],$data[61],$data[62],$data[63],$data[64],$data[65],$data[66],$data[67],$data[68],$data[69],$data[70],$data[71],$data[72],$data[73],$data[74],$data[75],$data[76],$data[77],$data[78],$data[79],$data[80],$data[81],$data[82],$data[83],$data[84],$data[85],$data[86],$data[87],$data[88],$data[89],$data[90],$data[91],$data[92],$data[93],$data[94],$data[95],$data[96],$data[97],$data[98],$data[99],
// $data[100],$data[101],$data[102],$data[103],$data[104],$data[105],$data[106],$data[107],$data[108],$data[109],$data[110],$data[111],$data[112],$data[113],$data[114],$data[115],$data[116],$data[117],$data[118],$data[119],$data[120],$data[121],$data[122],$data[123],$data[124],$data[125],$data[126])");
//     		    	$req->execute();
//     		    		echo "<pre>";
//     		    		print_r($req->errorInfo());
//     		    		echo '</pre>';

//     	}


//         // $colTotal = count($data);
//         // echo "<p> $colTotal champs à la ligne $row: <br /></p>\n";
//         $row++;
//         // for ($col=0; $col < $colTotal; $col++) {
//         // 	if($col==1 || $col==23){
//         //     echo $data[$col] . "<br />\n";

//         // 	}
//         // }
//     }
//     fclose($handle);
// }


if (($handle = fopen($importDir.$arrFilename[0], "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
    	if($row==0){
    			$row++;
    	}else{
  // 22/12/1999
$arDate=explode("/",$data[10]);
$dateCrea=$arDate[2]."-".$arDate[1]."-".$arDate[0];

$arDate=explode("/",$data[13]);
$dateMod=$arDate[2]."-".$arDate[1]."-".$arDate[0];


    		    	$req=$pdoQlik->prepare("INSERT INTO `mag_ctbt`(`id`, `galec`, `deno`, `deno_small`, `code_sav`, `code_centrale`, `id_bassin`, `id_backoffice`, `MAG_LIVSCA`, `MAG_PAN`, `MAG_ANCPAN`, `created_by`, `created_on`, `CRE_HEURE`, `modified_by`, `modified_on`, `MAJ_HEURE`, `MAJ_COD`, `date_import`) VALUES ($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],
$dateCrea,$data[11],$data[12],$dateMod,$data[14],$data[15],$data[16],$data[17], date('Y-m-d H:i:s'))");
    		    	$req->execute();
    		    		echo "<pre>";
    		    		print_r($req->errorInfo());
    		    		echo '</pre>';

    	}


        // $colTotal = count($data);
        // echo "<p> $colTotal champs à la ligne $row: <br /></p>\n";
        $row++;
        // for ($col=0; $col < $colTotal; $col++) {
        // 	if($col==1 || $col==23){
        //     echo $data[$col] . "<br />\n";

        // 	}
        // }
    }
    fclose($handle);
}

// $sFields = "'".implode("', '", $fields)."'";
// $sColumns = implode(", ", $columns);
// $sql = "INSERT INTO $table ($sColumns) VALUES ($sFields)";



// CREATE TABLE
// 'MAG-IDE','MAG-LIB','MAG-LIBR','MAG-MAI','MAG-PAN','MAG-ANCPAN','MAG-SCA','MAG-BAS','MAG-TYPINF','CRE-OPE','CRE-DATE','CRE-HEURE','MAJ-OPE','MAJ-DATE','MAJ-HEURE','MAJ-COD','MAG-LIVSCA','MAG-PANGAL',
//
//
//
// DROP TABLE `StatsVentesAdh`;
