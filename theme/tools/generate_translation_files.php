<?php
/**
 * Created by PhpStorm.
 * User: marlon.jerez
 * Date: 13/02/2018
 * Time: 15:30
 */

require_once __DIR__.'/i18nMessages.php';
$messages = new i18nMessages();

//Sets the languages to generate.
i18nMessages::setlanguages(array('en_US','es_ES','de_DE','fr_FR','it_IT'));

//Generates Translation-Files scanning php files and subfolders.
$messages->compile();

//Generates Translation-Files scanning php files and subfolders, starting from the 'rootDir'
$messages->compile(__DIR__."/..");