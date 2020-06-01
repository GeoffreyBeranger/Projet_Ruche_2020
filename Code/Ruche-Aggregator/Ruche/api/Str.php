<?php
    /** fichier		 : api/Str.php
	    description  : Class contenant des méthodes utiles pour les chaines de caratères
	    author       : Philippe SIMIER Lycée Touchard Le Mans
		
	**/

namespace Aggregator\Support;
	
class Str
{

	// Methode pour generer une chaine aléatoire
	// Retourne la chaine générée
	public static function genererChaineAleatoire($longueur = 0)
	{
		if( $longueur == 0 ){
			$longueur = rand ( 10 , 16 );
		}	
		$string = "";
		$chaine = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		srand((double)microtime()*1000000);
		for($i=0; $i<$longueur; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}
	
	/**
	 * Methode pour afficher le contenu d'une variable
	 */
	public static function afficherVar_dump($arg)
	{
		echo '<pre>';
		var_dump($arg);
		echo '</pre>';	
	}

	/**
	 * Méthode pour convertir tous les caractères éligibles en entités HTML
	 * Et limiter la longueur de la chaîne à 60 caractères
	 */
	public static function reduire( $chaine, $longueur = 60 ){	
		if ( strlen($chaine) > $longueur){	
			$retour = htmlentities(substr( $chaine, 0, $longueur) . '...');
		}else{
			$retour =  htmlentities($chaine);
		}	
		return $retour;	
	}

	/**
     * Get the portion of a string before a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    public static function before($subject, $search)
    {
        return $search === '' ? $subject : explode($search, $subject)[0];
    }

    /**
     * Return the remainder of a string after a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    public static function after($subject, $search)
    {
        return $search === '' ? $subject : array_reverse(explode($search, $subject, 2))[0];
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }	

}