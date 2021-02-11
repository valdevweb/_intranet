<?php
$maxPaletteImport= $paletteDao->getMaxPaletteImport($_POST['id_palette']);

if($maxPaletteImport['max_palette']!=null && $maxPaletteImport['date_end_limite']> date('Y-m-d') ){



	$nbPaletteCdeByMagImport=$cdeDao->getNbPaletteCdeByMagImport($maxPaletteImport['import']);
	if(count($nbPaletteCdeByMagImport)==$maxPaletteImport['max_palette']){
		$errors[]="Vous avez déjà commandé ".count($nbPaletteCdeByMagImport). " palette(s) sur ce lot, or les commandes sont limitées à ". $maxPaletteImport['max_palette']. " jusqu'au ".date('d-m', strtotime($maxPaletteImport['date_end_limite']));
	}
	$nbPaletteCdeTempByMagImport=$cdeDao->getNbPaletteCdeTempByMagImport($maxPaletteImport['import']);


	if(count($nbPaletteCdeTempByMagImport)-count($nbPaletteCdeByMagImport)==$maxPaletteImport['max_palette']){
		$errors[]="Vous avez déjà ajouté ".count($nbPaletteCdeTempByMagImport). " palette(s) de ce lot, or les commandes sont limitées à ". $maxPaletteImport['max_palette']. " jusqu'au ".date('d-m', strtotime($maxPaletteImport['date_end_limite']));
	}
}
if(empty($errors)){
	$displayCart=$cdeDao->addToTemp($pdoOcc);
	if($displayCart){
		$successQ='?success=cart';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

	}else{
		$errors[]="erreur";
	}


}

