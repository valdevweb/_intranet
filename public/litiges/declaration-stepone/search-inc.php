<?php

use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic;

$boxSelected = false;
$i = 1;

$dataBoxes = [];
$dataBoxesArticles = [];

// si on arrive sur cette page suite à une déclaration de vol, on connait déjà le ou les  numéros de palette qui postent pb
// on les récupère dans la varaible de session $_SESSION['palette']
if (!isset($_SESSION['palette'])) {
	$searchStr = $_POST['search_strg'];
	$dataSearch = $litigeDao->searchPaletteOrFacture($pdoQlik, $searchStr, $_SESSION['id_galec']);
} else {
	$dataSearch = $litigeDao->getPaletteForRobbery($pdoQlik, $_SESSION['palette']);
}


foreach ($dataSearch as $key => $data) {
	// si on est ssur un mag occasion, pour chaque article, on vérif si c'est un art occaz
	if ($magInfo['occasion'] == 1) {
		$listProdOcc = $occDao->getListArticleOccByArticlePalette($data['article']);
		if (!empty($listProdOcc)) {
			$dataSearch[$key]['occasion'] = 1;
			$dataSearch[$key]['occasion_detail'] = $listProdOcc;
		}
	}

// on vérifie aussi si on a des box
// une entete de box est caractérisée par un prix à 0
// les contenus de boxes, eux, n'ont pas de caractéristiques spéciaux
/**
 * PROCESS
 * 1- si tarif à zero => interroge table assortiment pour récup contenuu box indexe par code art de la tête de box => $detailBoxes
 * 2- on pousse la tête de box dans la var tableau $dataBoxes
 * 3- on retire la tete de box de $dataSearch
 * => suite hors boucle
 */
	if ($data['tarif'] == 0) {
		$detailBoxes = $litigeDao->getBoxHead($pdoQlik, $data['dossier'], $data['article']);
		if (!empty($detailBoxes)) {
			$dataBoxes[] = $dataSearch[$key];
			unset($dataSearch[$key]);

		}
	}
}

/**
 * PROCESS suite  box
 * 1- on reindexe dataSearch
 * 2- on parcourt les boxes 
 * 3- on  récup le contenu du box grace à detailBoxes et son index qui est égal à la tête de box
 * 4- on parcourt ce contenu : grace au code art_dossier on récupère l'emplacement du detail de box dans datasearch
 * 5- on mémorise cette emplacement pour pouvoir supprimer cette clé du tableau datasearch après le parcourt de detailBoxes
 * 6- on pousse le détail de box dans le tableau $dataBoxesArticles indexé par la tête de box
 * 7- on retire les détails de box de $dataSearch
 */
if (!empty($dataBoxes)) {
	$dataSearch = array_values($dataSearch);

	$idsOfBoxContent = [];
	foreach ($dataBoxes as $key => $detail) {

		if (isset($detailBoxes[$detail['article']])) {
			foreach ($detailBoxes[$detail['article']] as $head => $detailArt) {
				$artDos = $detailArt['SCEBFAST.ART-COD'] . '-' . $detailArt['SCEBFAST.DOS-COD'];

				$boxContentId = array_search($artDos, array_column($dataSearch, 'art_dossier'));

				echo $boxContentId;
				if ($boxContentId != "") {
					$idsOfBoxContent[] = $boxContentId;
					$dataBoxesArticles[$detail['article']][] = $dataSearch[$boxContentId];								// echo $found;
				}
			}
		}
	}
}
if (!empty($idsOfBoxContent)) {
	for ($i = 0; $i < count($idsOfBoxContent); $i++) {
		unset($dataSearch[$idsOfBoxContent[$i]]);
	}
}
// on réindex
$dataSearch = array_values($dataSearch);

