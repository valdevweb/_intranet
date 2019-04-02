<?php


//----------------------------------------------------------------
//		INCLUDES
//----------------------------------------------------------------
require_once  '../vendor/autoload.php';
//----------------------------------------------
//  		excel
//----------------------------------------------
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
			$reader->setReadDataOnly(TRUE);


			$path='D:\scapsav\relance-mag\\';
			$fxls=$path.$filename;
			$spreadsheet = $reader->load($fxls);
			$worksheet = $spreadsheet->getActiveSheet();
			$highestRow = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn = 'E'; // e.g 'F'

			$totalRep=0;
			$nberrors=0;

			for ($row = 2; $row <= $highestRow; ++$row)
			{
				$num_elitec=$worksheet->getCell('C' . $row)->getValue();
				$rep=$worksheet->getCell('E' . $row)->getValue();
				//si la cellule n'est pas vide, on met à jour la db et on ajoute au nombre de réponses
				if(!empty($rep))
				{
					$nbRep=recordRep($pdoSav,$num_elitec,$rep);
					if($nbRep>0)
					{
						$totalRep++;
					}
					else
					{
						$nberrors++;
					}
				}
			}



 ?>