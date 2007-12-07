<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.0 $Revision$
      +----------------------------------------------------------------------
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or
      |   modify it under the terms of the GNU General Public License
      |   as published by the Free Software Foundation; either version 2
      |   of the License, or (at your option) any later version.
      +----------------------------------------------------------------------+
      | Authors: Thomas Depraetere <depraetere@ipm.ucl.ac.be>
      |          Hugues Peeters    <peeters@ipm.ucl.ac.be>
      |          Christophe Gesch� <gesche@ipm.ucl.ac.be>
      +----------------------------------------------------------------------+
 */

$englishLangName = "french";
$localLangName = "fran�ais";

$iso639_2_code = "fr";
$iso639_1_code = "fre";

$langNameOfLang['arabic'		] = "arabe";
$langNameOfLang['brazilian'		] = "br�silien";
$langNameOfLang['croatian'		] = "croate";
$langNameOfLang['catalan'		] = "catalan";
$langNameOfLang['dutch'			] = "n�erlandais";
$langNameOfLang['english'		] = "anglais";
$langNameOfLang['finnish'		] = "finlandais";
$langNameOfLang['french'		] = "fran�ais";
$langNameOfLang['german'		] = "allemand";
$langNameOfLang['greek'			] = "grec";
$langNameOfLang['italian'		] = "italien";
$langNameOfLang['japanese'		] = "japonais"; // JCC 
$langNameOfLang['polish'		] = "polonais";
$langNameOfLang['simpl_chinese'	] ="chinois simple";
$langNameOfLang['spanish'		] = "espagnol";
$langNameOfLang['swedish'		] = "su�dois";
$langNameOfLang['thai'			] = "tha�landais";
$langNameOfLang['turkish'		] = "turc";

$charset = 'iso-8859-1';
$text_dir = 'ltr';
$left_font_family = 'verdana, helvetica, arial, geneva, sans-serif';
$right_font_family = 'helvetica, arial, geneva, sans-serif';
$number_thousands_separator = ' ';
$number_decimal_separator = ',';
$byteUnits = array('Octets', 'Ko', 'Mo', 'Go');

$langDay_of_weekNames['init'] = array('D', 'L', 'M', 'M', 'J', 'V', 'S');
$langDay_of_weekNames['short'] = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'); // JCC 
$langDay_of_weekNames['long'] = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');

$langMonthNames['init']  = array('J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Jan', 'F�v', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Ao�t', 'Sep', 'Oct', 'Nov', 'D�c');
$langMonthNames['long'] = array('Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%a %d %b %y";
$dateFormatLong  = '%A %d %B %Y';
$dateTimeFormatLong  = '%A %d %B %Y � %H:%M';
$timeNoSecFormat = '%H:%M';
// GENERIC
$langYes="Oui";
$langNo="Non";
$langBack="Retour";
$langNext="Suivant";
$langAllowed="Autoris�";
$langDenied="Refus�";
$langBackHome="Retour � la page principale";
$langPropositions="Propositions d'am�lioration de";
$langMaj="Mise � jour";
$langModify="Modifier";
$langDelete="Effacer";
$langMove="D�placer";
$langTitle="Titre";
$langHelp="Aide";
$langOk="Valider";
$langAdd="Ajouter";
$langAddIntro="Ajouter un texte d'introduction";
$langBackList="Retour � la liste";
$langText="Texte";
$langEmpty="Vide";
$langConfirmYourChoice="Veuillez confirmer votre choix";
$langAnd="et";
$langChoice="Votre choix";
$langFinish="Terminer";
$langCancel="Annuler";
$langNotAllowed="Vous n'�tes pas autoris� � acc�der � cette section";
$langManager="Responsable";
$langPlatform="Utilise la plate-forme";
$langOptional="Facultatif";
$langNextPage="Page suivante";
$langPreviousPage="Page pr�c�dente";
$langUse="Utiliser";
$langTotal="Total";
$langTake="prendre";
$langOne="Une";
$langSeveral="Plusieurs";
$langNotice="Remarque";
$langDate="Date";
$langAmong="parmi";


// banner
$langMyCourses="Liste de mes cours";
$langModifyProfile="Modifier mon profil";
$langPlatformAdmin="Administration";
$langMyAgenda = "Mon agenda";
$langLogout="Quitter";

$lang_this_course_is_protected = 'Ce cours est prot�g�';
$lang_enter_your_user_name_and_password = "Tapez votre nom d'utilisateur et votre mot de passe"; // JCC
$lang_if_you_dont_have_a_user_account_on = "Si vous n'avez pas encore de compte utilisateur sur"; // JCC 
$lang_click_here = 'cliquez ici';
$lang_your_user_profile_doesnt_seem_to_be_enrolled_to_this_course = "Votre profil utilisateur ne semble pas �tre inscrit � ce cours";
$lang_if_you_wish_to_enroll_to_this_course = "Si vous souhaitez vous inscrire � ce cours,";


// TOOLS NAMES
$langCourseHome = "Accueil"; // JCC 

$langAgenda="Agenda";
$langLink="Liens";
$langDocument="Documents et liens";
$langWork="Travaux";
$langAnnouncement="Annonces";
$langUser="Utilisateurs";
$langForum="Forums";
$langExercise="Exercices";
$langGroups ="Groupes";
$langChat ="Discussion";
$langLearnPath="Parcours p�dagogique";
$langDescriptionCours  = "Description du cours";
$langCourseManagerview = "Responsable du cours";
$lang_footer_CourseManager = "Responsable(s) du cours";
$langPlatformAdministration = "Administration";
?>