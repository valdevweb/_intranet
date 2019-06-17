<?php

class Helpers{

	/**
	 * lien btn retour
	 * @var string
	 */
	private $page;
	/**
	 * couleur btn retour optionnel
	 * @var string
	 */
	private $color;

	/**
	 * création btn retour type
	 * @param  string $page
	 * @param  string $color optionnel
	 * @return string
	 */
	public static function returnBtn($page,$color=null)
	{

			return '<div class="row py-3"><div class="col"><p class="text-right"><a href="'.$page.'" class="btn btn-primary">Retour</a></p></div></div>';
	}

// exemple
	// Helpers::returnBtn('bt-casse-dashboard.php');

    public static function withZero($chiffre){
        if($chiffre < 10){
            return '0' . $chiffre;
        }else{
            return $chiffre;
        }
    }

}
