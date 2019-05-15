<?php

class Form{


	private $data;
	//on met tag en publc car on pourra ainsi accéder à la propriété de l'exterieur et changer la valeur de tag

	public $tag=['div class="col"','div'];
	public $string;

	//on déclarer date comme un tableau vide => ne plante pas si pas d'arg tableau
	public function __construct($data=array())
	{
		$this->data=$data;
	}
	private function surround($html)
	{
		return "<{$this->tag[0]}>{$html}</{$this->tag[1]}>";
	}

	private function getValue($index){
		return isset($this->data[$index]) ? $this->data[$index] : null;
	}

	public function submit(){
		return $this->surround('<button type="submit">Envoyer</button>');
	}
	public function input($name){
		return $this->surround('<input type="text" name="'.$name.'" id="'.$name.'" value="'.$this->getValue($name).'">');
	}

}