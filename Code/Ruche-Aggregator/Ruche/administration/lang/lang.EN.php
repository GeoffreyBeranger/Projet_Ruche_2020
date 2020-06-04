<!-- /**
 * \file      lang.EN.php
 * \author    G.BÉRANGER
 * \version   1.0
 * \date       01 Juin 2020
 * \brief      Définition des termes anglais du site
 *
 * \details    Les champs sont des tableaux dans lesquels se trouvent chacunes des traductions
 *
 */ -->

<?php

$lang = array();


///////////////Reacts///////////////
$lang['dataTables'] = "lang/dataTable.English.json";

$lang['name'] ="Name";
$lang['channel'] = "Channel to Check";
$lang['field'] = "Field to Check";
$lang['condition'] = "Condition";
$lang['condition_value'] = "Condition Value";
$lang['action_name'] = "Actionable Name";

$lang['add'] = "Add";
$lang['edit_settings'] = "Edit Settings";
$lang['delete'] = "Delete";

///////////////support/Reacts///////////////
$lang['reacts_title1'] = "Overview of reacts (react to events)";
$lang['reacts_text1a'] = "Respond when a channel's data meets certain conditions";
$lang['reacts_text1b'] = "Reacts work with ThingHTTP applications, to perform actions when the data of a channel meets a certain condition.
                          You can configure a react to notify an event by SMS or Mail.
                          For example, when the weight of a hive suddenly drops, ask ThingHTTP to send an SMS containing a description of the event.";
$lang['reacts_title2'] = "Read archived reacts";
$lang['reacts_text2a'] = "Choose Reacts in the user menu to display the table of archived reacts.";
$lang['reacts_text2b'] = "For each of the reacts the following information is displayed.";
$lang['reacts_text2c'] = "The login of the owner user";
$lang['reacts_text2d'] = "The name of the react";
$lang['reacts_text2e'] = "The designation of the channel to be checked";
$lang['reacts_text2f'] = "The logical condition for triggering the action. This condition is made up of three parts :";
$lang['reacts_text2g'] = "A canal field";
$lang['reacts_text2h'] = "A digital comparison operator";
$lang['reacts_text2i'] = "A numerical value";
$lang['reacts_text2j'] = "The designation of the thingHtttp application (this application triggers the service requested via an http request)";
$lang['reacts_title3'] = "Delete Reacts";
$lang['reacts_text3a'] = "You can delete one or more archived Reacts. Check the Reacts to delete then click on the Delete button. A confirmation window opens.
                          Validate the action";
$lang['reacts_title4'] = "Edit a React";
$lang['reacts_text4a'] = "Check the react to modify then click on the setting button. A form opens.";


///////////////React///////////////
$lang['select_react_type'] = array('0'=>'Run action only the first time the condition is met','1'=>'Run action each time condition is met');

$lang['select_channel_id'] = "Choose a Channel";


$lang['select_actionable_type'] = array('' => 'Choose your action', 'thingHTTP' => "ThingHTTP",  'email' => "Send a email" );

$lang['select_interval'] = array('on_insertion' => "On data insertion",
                         '10' =>"Every 10 minutes",
                         '30' =>"Every 30 minutes",
                         '60' =>"Every 60 minutes" );


$lang['select_condition'] = array(	'>' => 'is greater than',
                                    '>=' => 'is greater or equal to',
                                    '<' => 'is less than',
                                    '<=' => 'is less than or equal',
                                    '=' =>  'is equal to',
                                    '≠' => 'is not equal' );

$lang['select_react_type'] = array('0'=>'Run action only the first time the condition is met',
                                  '1' =>'Run action each time condition is met');


//------------Text sur le coté du Formulaire---------------//

$lang['react_title1'] = "Reacts Settings";
$lang['react_text1a'] = "React Name : Enter a unique name for your React.";
$lang['react_text1b'] = "Test Frequency : Choose whether to test your condition every time data enters the channel or on a periodic basis.";
$lang['react_text1c'] = "Condition : Select a channel, a field and the condition for your React.";
$lang['react_text1d'] = "Action: Select ThingHTTP, Send a SMS, Send a email to run when the condition is met.";
$lang['react_text1e'] = "Option : Select when the React runs.";








 ?>
