<?php
include 'config.inc.php';

$fieldseparator = ",";
$lineseparator = "\n";


$importDir="D:\btlec\dumps\gessica\\";
// vérif si fichier à la date du jour

$arrFilename=["SBBCFMAG.txt","SBBCFPID.txt","SCEBFADH.txt"];
$csvfile=$importDir.$arrFilename[0];

$row=0;
if (($handle = fopen($csvfile, "r")) !== FALSE) {

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if($row==0){
            $row++;
        }else{
            $req=$pdoQlik->prepare("INSERT INTO mag_ctbt(id, MAG_LIB, MAG_LIBR, MAG_MAI, MAG_PAN, MAG_ANCPAN, MAG_SCA, MAG_BAS, MAG_TYPINF, CRE_OPE, CRE_DATE, CRE_HEURE, MAJ_OPE, MAJ_DATE, MAJ_HEURE, MAJ_COD, MAG_LIVSCA, MAG_PANGAL, date_import) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            if($req->execute([
                $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16], $data[17],date('Y-m-d H:i:s')
            ])){

            }else{

                $err=$req->errorInfo();
                $errArr[$row]['id']=$data[0];
                $errArr[$row]['code']=$err[1];
                $errArr[$row]['message']=$err[2];
            }
        }
        $row++;
    }
    fclose($handle);
    echo "<pre>";
    print_r($errArr);
    echo '</pre>';

}
