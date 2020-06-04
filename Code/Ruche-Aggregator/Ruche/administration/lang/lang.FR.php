<!-- /**
 * \file      lang.FR.php
 * \author    G.BÉRANGER
 * \version   1.0
 * \date       01 Juin 2020
 * \brief      Définition des termes francais du site
 *
 * \details    Les champs sont des tableaux dans lesquels se trouvent chacunes des traductions
 *
 */ -->

<?php

$lang = array();


///////////////Reacts///////////////
$lang['dataTables'] = "lang/dataTable.French.json";

$lang['name'] ="Nom";
$lang['channel'] = "Canal à Vérifier";
$lang['field'] = "Champs à Vérifier";
$lang['condition'] = "Condition";
$lang['condition_value'] = "Valeur à Comparer";
$lang['action_name'] = "Nom de l'Action à Effectuer";

$lang['add'] = "Ajouter";
$lang['edit_settings'] = "Modifier";
$lang['delete'] = "Supprimer";


///////////////support/Reacts///////////////
$lang['reacts_title1'] = "Vue d'ensemble de reacts (réagir aux évènements)";
$lang['reacts_text1a'] = "Réagissez lorsque les données d'un canal remplissent certaines conditions";
$lang['reacts_text1b'] = "les Reacts fonctionnent avec les applications ThingHTTP, pour effectuer des actions lorsque les données d'un canal remplissent une certaine condition.
                          Vous pouvez configurer un react pour notifier un évenement par SMS ou Mail.
                          Par exemple lorsque le poids d'une ruche chute soudainement, demandez à ThingHTTP d'envoyer un SMS contenant une description de l'évènement.";
$lang['reacts_title2'] = "Lire les reacts archivés";
$lang['reacts_text2a'] = "Choisir Reacts dans le menu utilisateur pour afficher le tableau des reacts archivés.";
$lang['reacts_text2b'] = "Pour chacun des reacts les informations suivantes sont affichées.";
$lang['reacts_text2c'] = "Le login de l'utilisateur propriétaire";
$lang['reacts_text2d'] = "Le nom du react";
$lang['reacts_text2e'] = "La désignation du canal à vérifier";
$lang['reacts_text2f'] = "La condition logique permettant de déclencher l'action. Cette condition est composée de trois parties :";
$lang['reacts_text2g'] = "Un champs du canal";
$lang['reacts_text2h'] = "Un opérateur de comparaison numérique";
$lang['reacts_text2i'] = "Une valeur numérique";
$lang['reacts_text2j'] = "La désignation de l'application thingHtttp (cette application déclenche le service demandé via une requête http)";
$lang['reacts_title3'] = "Supprimer les Reacts";
$lang['reacts_text3a'] = "Vous pouvez supprimer un ou plusieurs Reacts archivés. Cocher les Reacts à supprimer puis cliquer sur le bouton Delete. Une fenêtre de confirmation s'ouvre.
                          Valider l'action";
$lang['reacts_title4'] = "Modifier un React";
$lang['reacts_text4a'] = "Cocher le react à modifier puis cliquer sur le bouton setting. Un formulaire s'ouvre.";



///////////////React///////////////

//Vue d'ensemble de reacts (réagir aux évènements)
$lang['select_react_type'] = array('0'=>"Exécuter l'action uniquement la première fois que la condition est remplie",'1'=>"Exécuter l'action chaque fois que la condition est remplie");

$lang['select_channel_id'] = "Sélectionner un Canal";

$lang['select_actionable_type'] = array('' => 'Choisissez une Action', 'thingHTTP' => "ThingHTTP",  'email' => "Envoyez un Email" );

$lang['select_interval'] = array('on_insertion' => "Lors de l'insertion de Valeur",
                         '10' =>"Toutes les 10 minutes",
                         '30' =>"Toutes les 30 minutes",
                         '60' =>"Toutes les 60 minutes" );

$lang['select_condition'] = array(	'>' => "est supérieur à",
                                    '=>' => "est supérieur ou égal à",
                                    '<' => "est inférieur à",
                                    '<=' => "est inférieur ou égal à",
                                    '=' =>  "est égal à",
                                    ' ≠ ' => "n'est pas égal à" );


$lang['select_react_type'] = array ('0' => "Exécuter l'action uniquement la première fois que la condition est remplie",
                                    '1' => "Exécuter une action chaque fois que la condition est remplie");


//------------Text sur le coté du Formulaire---------------//


$lang['react_title1'] = "Option du React";
$lang['react_text1a'] = "Nom du React : Saisissez un nom unique pour votre React.";
$lang['react_text1b'] = "Fréquence de test : Choisissez de tester votre condition à chaque fois que des données entrent dans le canal ou périodiquement.";
$lang['react_text1c'] = "Condition : Sélectionnez un canal, un champ et la condition de votre React.";
$lang['react_text1d'] = "Action : Sélectionnez ThingHTTP, Envoyer un SMS, Envoyer un e-mail à exécuter lorsque la condition est remplie.";
$lang['react_text1e'] = "Option : Sélectionnez le moment où React s'exécute.";















 ?>
