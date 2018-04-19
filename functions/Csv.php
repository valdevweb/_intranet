<?php


class CSV
{

	static function export($datas,$filename)
	{
		header('Content-type: text/csv;');
		header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
		$i=0;
		foreach ($datas as $v)
		{
			// soit on récu^père les valeur des clé de notre tableu lors de la 1ere boucle
			// ces clés seront les intitulés de nos colonnes dans le tableau xls
			//
			// soit on écrit directement l'initulé des colonnes

			if($i==0)
			{

				// echo '"'.implode('";"',array_keys($v)) .'"'."\n";
				echo "id; code galec; code bt;magasin;centrale;ville;nom;prenom;fonction;12-06-2018;13-06-2018;visite;repas" ."\n";

			}

				$v2 = array_map(function($value) { return str_replace('<br />', '', $value); }, $v);
					// echo "<pre>";
					// var_dump($v2);
					// echo '</pre>';

				echo '"'.implode('";"',array_map('utf8_decode',$v2)) .'"'."\n";

			$i++;
		}
	}


}
