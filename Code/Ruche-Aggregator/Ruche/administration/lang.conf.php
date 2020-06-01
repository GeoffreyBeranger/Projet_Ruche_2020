/**
 * \file      lang.conf.php
 * \author    G.BÉRANGER
 * \version   1.0
 * \date       01 Juin 2020
 * \brief       Définit le fichier langue à utiliser
 *
 * \details     Ce fichier récupère la langue de l'utilisateur depuis la variable de session
 *              puis instancie le fichier langue en fonction de cette variable
 */
<?php

include "authentification/authcheck.php" ;

$Lang= $_SESSION["language"]; // Récupération de la variable de session qui est stockée dans la BDD

/////////////////////////////////////////////////////
if(isset($Lang) and $Lang!==''){
  $lang_file = 'lang.'.$Lang.'.php';
}else{
  $lang_file = 'lang.ch.php';
}

  include_once 'lang/'.$lang_file;
/////////////////////////////////////////////////////
?>
