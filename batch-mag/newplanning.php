<?php
if (preg_match('/_btlecest/', dirname(__FILE__))) {
    set_include_path("D:\www\_intranet\_btlecest\\");
} else {
    set_include_path("D:\www\intranet\btlecest\\");
}


include 'config/config.inc.php';
include 'vendor/autoload.php';
include 'Class/Db.php';
include 'Class/CrudDao.php';



$db = new Db();

$pdoMag = $db->getPdo('magasin');
$pdoPilotage = $db->getPdo('pilotage');

$crudDao = new CrudDao($pdoMag);
$crudPilotage = new CrudDao($pdoPilotage);


$today = new DateTime();
$today->modify('- 1 day');
$todayStr = $today->format('Y-m-d');




$newMags = $crudDao->selectManyParams('mag', ['date_ouv' => $todayStr]);
foreach ($newMags as $newMag) {
    $oldMag = $crudDao->getOneByField('mag', 'adh_payeur', $newMag['id']);
    // normalement le nouveau mag a maintenant son code galec et l'ancien mag un code fictif
    // on niveau du planning on doit copier les infos de l'ancien mag en lui mettant le nouveau code bt
    // sur l'ancien planning, ondoit changer le code galec : mettre le fictif dans galec et galec_unique
    $req = $pdoPilotage->prepare("INSERT INTO planning_liv_normal 
    (`btlec`, `galec`, `galec_unique`, `id_centrale`, `id_type_liv`, `cde`, `prepa`, `chg`, `liv`, `id_fret`, `id_affreteur`, `inverse`, `blueprint`, `donotdelete`, `exclu_integration`, `id_info_builder`) 
    SELECT btlec, `galec`, `galec_unique`, `id_centrale`, `id_type_liv`, `cde`, `prepa`, `chg`, `liv`, `id_fret`, `id_affreteur`, `inverse`, `blueprint`, `donotdelete`, `exclu_integration`, `id_info_builder` from planning_liv_normal WHERE btlec= :btlec");
    $req->execute([
        ':btlec'       => $oldMag['id'],
    ]);
    $newPlanningId = $pdoPilotage->lastInsertId();
    $crudPilotage->updateOneField('planning_liv_normal', 'btlec', $newMag['id'], $newPlanningId);


    $oldPlanning=$crudPilotage->getOneByField('planning_liv_normal', 'btlec', $oldMag['id']);
    

    $galecFictif=$oldMag['galec'].substr($oldPlanning['galec_unique'], -1);
    $crudPilotage->updateOneField('planning_liv_normal', 'galec', $oldMag['galec'], $oldPlanning['id']);
    $crudPilotage->updateOneField('planning_liv_normal', 'galec_unique', $galecFictif, $oldPlanning['id']);


}
