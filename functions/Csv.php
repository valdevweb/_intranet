<?php


class CSV
{

	static function export($datas,$filename)
	{
		header('Content-type: text/csv;');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		$i=0;
		foreach ($datas as $v)
		{
			// on récu^père les valeur des clé de notre tableu lors de la 1ere boucle
			// ces clés seront les intitulés de nos colonnes dans le tableau xls
			// on pense à ajouter les guillemets et les ; autour de nos valeurs (séparateur csv)
			if($i==0)
			{
				echo '"'.implode('";"',array_keys($v)) .'"'."\n";
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
