<?php

foreach ($oldInfoNumBt as $key => $oldInfo) {
	$resiliation=convertToDate($oldInfo[0]['DateResiliation']);
	$adhesion=convertToDate($oldInfo[0]['DateAdhesion']);
	$affilie=($oldInfo[0]['Affilie']==2)? 1 :0;
	$fermeture=convertToDate($oldInfo[0]['DateFermeture']);
	$ouverture=convertToDate($oldInfo[0]['DateOuverture']);

	// update mag
	$updateDocubase=updateMagDocubase($pdoMag, $oldInfo[0]['NumBT'], $oldInfo[0]['docubase_login'],$oldInfo[0]['docubase_pwd']);
	if($updateDocubase==1){
		$newDocubase++;
	}else{
		echo "error";
	}

	// update numbt
	if(alreadyInNewNumBt($pdoMag, $oldInfo[0]['NumBT'])){
		$updateInfo=updateNumBt($pdoMag,$oldInfo[0], $resiliation, $adhesion, $affilie, $fermeture, $ouverture);


		if($updateInfo==1){
			$newInfoUpdated++;
		}else{
			echo "error update";
		}

	}else{
		$insertInfo=insertNumBt($pdoMag,$oldInfo[0], $resiliation, $adhesion, $affilie, $fermeture, $ouverture);

		if($insertInfo==1){
			$newInfoAdded++;
		}else{
			echo "error  insert";

		}
	}
}

echo "ajouté ".$newInfoAdded;
echo "<br>";

echo "updated " . $newInfoUpdated;
echo "<br>";

echo "updated docubase" . $newDocubase;