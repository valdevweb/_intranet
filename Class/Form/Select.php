<?php
class Select{

	public $name;			//select name=$name
	private $label;
	private $value;			//value du 1er option, par défaut vide
	private $text;			//text du 1er option
	public $id='id';			//par défaut value= les valeurs de id du tableau de résultat arrData
	// private $texts;
	private $arrayData;		//tableau de résultat a passer dans le foreach
	private $field;			//nom du champ du tableau de résultat à utiliser en texte d'affichage

	public function __construct($name,$label=null)
	{

		echo '<div class="form-group">';
		if($label!=null)
		{
			echo '<label for="'.$this->name=$name.'">'.$this->label=$label.'</label>';
		}
		echo '<select class="form-control" name="'.$this->name=$name.'" id="'.$this->name=$name.'">';

		return	$this->name=$name;

	}

	public function createFirstOption($text, $value=null)
	{

		if($value==null)
		{
			echo '<option value="">'.$this->text=$text.'</option>';
		}
		else{
			echo '<option value="'.$this->text=$text.'">'.$this->text=$text.'</option>';

		}
	}



	public function createOption($arrayData,$field)
	{
		$selected='';

		foreach ($arrayData as $data)
		{
			if(!empty($_POST[$this->field=$field]))
			{
				if($_POST[$this->field=$field]==$data[$this->id])
				{
					$selected='selected';
				}
				else{
					$selected='';
				}
			}
			else
			{
					$selected='';

			}

				// echo '<option value="'.$data['id'].'" '.$selected.'>'.$data[$this->field=$field].'</option>';
			echo '<option value="'.$data[$this->id].'" '.$selected.'>'.$data[$this->field=$field].'</option>';

				// echo '<option value="'.$this->id=$data['id'].'">'.$this->texts=$data[$this->field=$field].'</option>';
		}








		echo '</select>';
		echo '</div>';
	}


}



?>