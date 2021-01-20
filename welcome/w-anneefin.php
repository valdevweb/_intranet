<?php
$annee=$jMoinsUn->format('Y');
if($jMoinsUnLastYear!="NULL"){
	$anneeLastYear=$jMoinsUnLastYear->format('Y');
	$moisLastYear=$jMoinsUnLastYear->format('n');
}else{
	$fakeJMoinsUnLastYear=getJourLastYearFake(clone $jMoinsUn);
	$anneeLastYear=$fakeJMoinsUnLastYear->format('Y');
	$moisLastYear=$moisActuel;
}


			$anneeFinAll=caAnnee($pdoQlik,12, $annee);
			$anneeFinCa=$anneeFinAll['somme'];
			$anneeFinPalettes=$anneeFinAll['palettes'];
			$anneeFinColis=$anneeFinAll['colis'];


			$anneeFinLastYearAll=caAnnee($pdoQlik,12, $anneeLastYear);
			$anneeFinLastYearCa=$anneeFinLastYearAll['somme'];
			$anneeFinLastYearPalettes=$anneeFinLastYearAll['palettes'];
			$anneeFinLastYearColis=$anneeFinLastYearAll['colis'];
			$anneeFinDiff=$anneeFinCa-$anneeFinLastYearCa;
			$anneeFinPourcent=pourcentage($anneeFinCa,$anneeFinLastYearCa,$anneeFinDiff);