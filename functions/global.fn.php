<?php

// vérifie si un champ a une valeur unique
if(!function_exists('not_unique'))
{
	function not_unique($pdo,$field,$value,$table)
	{
		$req=$pdo->prepare("SELECT * FROM $table WHERE $field = ?");
		$req->execute([$value]);
		$count=$req->rowCount();
		$req->closeCursor();
		//0 si n'exite pas 1 si exsite
		return $count;
	}
}

//champs vides ?
if(!function_exists('not_empty'))
{
	function not_empty($fields=[])
	{
		if(count($fields !=0))
		{
			foreach ($fields as $field)
			{
				if(empty($_POST[$field]) || trim($_POST[$field])=="")
				{
					return false;
				}
			}
			return true;
		}
	}

}


