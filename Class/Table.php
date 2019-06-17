<?php
/**
 * création d'un tableau html avec parcours d'un tableau multi et possibilité de désigner une colonne avec des liens
 * voir bt-casse-dashboard pour exempe d'utilisation
 * bt-casse-dashboard , detail-palette
 */
class Table{

	private $th;
	private $results;
	private $fields;
	private $arrLink;
	private $tableId;
	private $tableClass;


	/**
	 * [__construct html code pour 1 table avec ou sans id]
	 * @param string $tableId [#]
	 */
	public function __construct($tableClass, $tableId=null)
	{
		$this->tableClass=$tableClass;
		$tableClass=implode(' ',$tableClass);
		if(!is_null($this->tableId=$tableId)){
				echo '<table class="'.$tableClass.'" id="'.$this->tableId=$tableId.'"><thead class="thead-dark"><tr>';
		}
		else{
		echo '<table class="'.$tableClass.'""><thead class="thead-dark"><tr>';
	}
	}

	/**
	 * permet d'ajouter un lien à une des colonnes du tableau
	 * @param array $arrLink href(lien),text(text du lien, si vide prend la valeur de la colonne,col(N° de colonne),param(nom du paramètre get) :
	 */
	public function addLink($arrLink)
	{
		return $this->arrLink=$arrLink;
	}

	/**
	 * crée le tableau en parcourant un tableau asso ($results)
	 * @param  array $th      libellé des th
	 * @param  array $results tableau asso à parcourir pour affichage
	 * @param  array $fields  nom des champs du tableau result à afficher
	 * @param  tableau $arrLink tableau asso avec info sur lien
	 * @return string          tableau html
	 */
	public function createBasicTable($th,$results,$fields,$arrLink=null)
	{
		$this->results=$results;
		$this->th=$th;
		$this->result=$results;
		$this->fields=$fields;

		$nbCol=count($th);

		for($i=0;$i<$nbCol;$i++)
		{
			echo '<th>'.$th[$i].'</th>';
		}
		echo '</tr></thead><tbody>';
		foreach ($results as $key => $result)
		{
			echo '<tr>';
			for($j=0; $j<count($fields);$j++)
			{
				if(isset($arrLink))
				{
					if($arrLink['col']==$j+1)
					{
						if(!empty($arrLink['text'])){

						echo '<td><a href="'.$arrLink['href'].'?'.$arrLink['param'].'='.$result[$fields[$j]].'">'.$arrLink['text'].'</td>';

						}
						else
						{
							echo '<td><a href="'.$arrLink['href'].'?'.$arrLink['param'].'='.$result[$fields[$j]].'">'.$result[$fields[$j]].'</td>';
						}
					}
					else
					{
						echo '<td>'.$result[$fields[$j]].'</td>';
					}

				}
				else
				{
					echo '<td>'.$result[$fields[$j]].'</td>';
				}
			}
			echo '</tr>';

		}
		echo '</tbody></table>';



	}


}
