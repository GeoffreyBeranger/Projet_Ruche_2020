<?php
    /** fichier		 : api/Api.php
	    description  : Class pour simplifier l'écriture des API http
	    author       : Philippe SIMIER Lycée Touchard Le Mans
		
	**/
	
namespace Aggregator\Support;

require_once('Str.php');   // La classe Api utilise des méthodes de la classe Str
use Aggregator\Support\Str;
	
class Api
{
    
	
  
    // Fonction pour renvoyer une réponse suite à une erreur 
	// Cette fonction tue (termine) l'éxécution du script
    public static function envoyerErreur($httpStatus, $message, $detail){
        $data = array(
                'status' => $httpStatus,
                'message' => $message,
                'detail' => $detail
        );
        header('HTTP/1.1 ' . $httpStatus . ' ' . $message);
		header('Access-Control-Allow-Origin: *');
        header('content-type:application/json');
		echo json_encode($data);  
        exit();		
    }
	
	// Fonction pour obtenir la connexion à la BD
	//¨Paramètre le nom de la base
	// Retourne un objet bdd
	// avec selection de la timeZone pour la session.
	// Si time_zone est vide alors UTC +00:00
	// Définition du mode d'erreur sur exception
	public static function connexionBD($base, $time_zone = '+00:00') {
		try 
		{
			$bdd = new \PDO('mysql:host=' . SERVEUR . ';dbname=' . $base, UTILISATEUR,PASSE );
			// définition du mode d'erreur sur exception
			$bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			// selection de la timezone UTC pour la session
			$sql = "SET @@session.time_zone = ". $bdd->quote($time_zone);
			$stmt = $bdd->exec($sql);
		} 
		catch (\PDOException $ex) 
		{
		   Api::envoyerErreur('503','Service Unavailable',$ex->getMessage());       	   
		}
		return $bdd;
	}
	
	// Fonction pour vérifier la présence d'un paramètre en GET ou POST
	// Si le paramètre n'est pas présent ou ne correspond pas au type demandé 
	// alors la valeur NULL est retournée
	// Le paramètre filter_flag (filtre de validation) est optionnel 
	// il peut prendre les valeurs suivantes :
	// FILTER_VALIDATE_EMAIL
	// FILTER_VALIDATE_FLOAT
	// FILTER_VALIDATE_INT
	
    public static function verifier($key, $filter_flag = FILTER_DEFAULT){
		$valeur = filter_input(INPUT_GET, $key, $filter_flag);
		if($valeur === NULL || $valeur === FALSE){
			$valeur = filter_input(INPUT_POST, $key, $filter_flag);
		}
		if($valeur === NULL || $valeur === FALSE ){
			$valeur = NULL; 
		}	
        return $valeur;		
	}	

    // Fonction pour contrôler la présence d'un paramètre OBLIGATOIRE en GET ou POST
	// Si le paramètre n'est pas présent une erreur 403 est envoyée 
	// Le paramètre filter_flag (filtre de validation) est optionnel 
	// il peut prendre les valeurs suivantes :
	// FILTER_VALIDATE_EMAIL
	// FILTER_VALIDATE_FLOAT
	// FILTER_VALIDATE_INT
	
    public static function obtenir($key, $filter_flag = FILTER_DEFAULT){
        $valeur = Api::verifier( $key, $filter_flag);
		if ($valeur === NULL)
		    Api::envoyerErreur(403, "Bad Request", "The request cannot be fulfilled due to bad syntax.");	
		else
			return $valeur;			
	}
	
    // Fonction pour obtenir la valeur d'un paramètre FACULTATIF en GET ou POST
	// Si le paramètre n'est pas présent la valeur par défaut est retournée
	// Le paramétre $default contient la valeur par défaut.
	// Le paramètre filter_flag (filtre de validation) est optionnel 
	// il peut prendre les valeurs suivantes :
	// FILTER_VALIDATE_EMAIL
	// FILTER_VALIDATE_FLOAT
	// FILTER_VALIDATE_INT 
	public static function facultatif($key, $default, $filter_flag = FILTER_DEFAULT){
		$valeur = Api::verifier( $key, $filter_flag);
		if ($valeur === NULL)
				return $default;
		else
				return $valeur;
		
	}	
	
	
	// Fonction pour contrôler la présence de la clé dans la table users
	// Retourne true si la clé appartient à un utilisateur présent dans la table users
	// Sinon tue le script et envoie un message d'erreur 405
    public static function controlerkey($bdd, $key){
		try{
			$sql = sprintf("SELECT COUNT(*) as nb FROM `users` WHERE `users`.`User_API_Key`=%s", $bdd->quote($key));
			$stmt = $bdd->query($sql);
			$res =  $stmt->fetchObject();
		
			// si aucune ligne ne correspond  à la clé reçue
			if ( $res->nb == 0) {
				Api::envoyerErreur(405, "Authorization Required", "Please provide proper authentication details." );
			}
			return true;
		}
		catch(\PDOException $ex)
		{
			Api::envoyerErreur(405, "Authorization Required", "Please provide proper write_api_key." );
		}	
    }
	
	// Fonction pour obtenir un objet channel à partir de sa write_api_key
	// Retourne l'objet channel si la write_api_key est présente dans la table channels
	// Sinon tue le script et envoie un message d'erreur 405
    public static function obtenirChannel($bdd, $write_api_key){
		try{	
			$sql = sprintf("SELECT * FROM `channels` WHERE `write_api_key` = %s", $bdd->quote($write_api_key));
			$stmt = $bdd->query($sql);
			$channel =  $stmt->fetchObject();
			
		
			// si aucune ligne ne correspond  à la clé reçue
			if ( $channel === false) {
				Api::envoyerErreur(405, "Authorization Required", "Please provide proper write_api_key." );
			}
			else {
				return $channel;
			}
		}
		catch(\PDOException $ex)
		{
			Api::envoyerErreur(405, "Authorization Required", "Please provide proper write_api_key." );
		}
    }
	
	
	// Fonction pour générer un clé API utilisateur
	// La fonction vérifie que la clé générée est unique
	public static function genererKey($bdd){
		try{	
			do{
				$key = Str::genererChaineAleatoire();
				$sql = sprintf("SELECT COUNT(*) as nb FROM `users` WHERE `users`.`User_API_Key`=%s", $bdd->quote($key));
				$stmt = $bdd->query($sql);
				$res =  $stmt->fetchObject();
			}while($res->nb != 0);
			return $key;
		}
		catch(\PDOException $ex)
		{
			return "";
		}
	}

    // Fonction pour convertir un dateTime UTC en localTime
	public static function ObtenirDateTimeLocal($datetime, $timeZone) {
		if ($datetime != ""){
			$date = new DateTime($datetime, new DateTimeZone('UTC'));
			$date->setTimezone(new \DateTimeZone($timeZone));
			return $date->format('Y-m-d H:i:s');
		}
		return "";
	}
	
	// Fonction pour remplacer les valeurs null par nan
    public static function nan($valeur){
	if($valeur == null)
		return "nan";
	else 
		return $valeur;
    }

    // Fonction pour mettre la date UTC au format yyyy-mm-ddThh-mm-ssZ
    public static function formatDate($date){
		$dt = new DateTime($date);
        return $dt->format('Y-m-d\TH-i-s\Z');
	}
	

}	