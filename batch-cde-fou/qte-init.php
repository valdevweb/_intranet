<?php
if (preg_match('/_btlecest/', dirname(__FILE__))){
	set_include_path("D:\www\_intranet\_btlecest\\");
}
else{
	set_include_path("D:\www\intranet\btlecest\\");
}
include 'config/config.inc.php';
include 'config/db-connect.php';
function getCdes($pdoQlik, $yesterday){

	$req=$pdoQlik->prepare("INSERT INTO cdes_fou_qte_init (id_detail, qte_cde_init, qte_uv_cde_init, date_cde, date_liv_init)
		SELECT cdes_fou_details.id, cdes_fou_details.qte_cde, cdes_fou_details.qte_uv_cde, cdes_fou.date_cde, cdes_fou.date_liv
		FROM cdes_fou_details
		LEFT JOIN cdes_fou ON cdes_fou_details.id_cde=cdes_fou.num_cde
		WHERE cdes_fou.date_cde= :date_cde AND cdes_fou_details.qte_cde!=0");
	$req->execute([
		':date_cde'	=>$yesterday
	]);
	return $req->rowCount();
}


// INSERT INTO cdes_fou_qte_init (id_detail, qte_cde, qte_uv_cde, date_cde)
// 		SELECT cdes_fou_details.id, cdes_fou_details.qte_cde, cdes_fou_details.qte_uv_cde, cdes_fou.date_cde
// 		FROM cdes_fou_details
// 		LEFT JOIN cdes_fou ON cdes_fou_details.id_cde=cdes_fou.num_cde
// 		WHERE cdes_fou.date_cde !='2021-03-31' AND cdes_fou_details.qte_cde!=0

$yesterday=((new DateTime())->modify('- 1 day'))->format('Y-m-d');

$encours=getCdes($pdoQlik, $yesterday);

// foreach ($encours as $key => $enc) {
// 	$enc['id_cdesartdos']
// }
	echo "<pre>";
	print_r($encours);
	echo '</pre>';
