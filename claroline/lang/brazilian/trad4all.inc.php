<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.*
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   Brazillian Translation (portugese)                                 |
      +----------------------------------------------------------------------+

      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |           Marcello R. Minholi, <minholi@unipar.be>                   |
	  |									from Universidade Paranaense         |
      +----------------------------------------------------------------------+
 */

$englishLangName = "Brazilian Portuguese";
$localLangName = "Portugu�s";

/*
Brazil (br or pt-br):
in english: "Brazilian Portuguese"
in "brazilian" portuguese: "Portugu�s"
in portuguese of Portugal: "Portugu�s do Brasil"

Portugal (pt):
in english: "Portuguese"
in "brazilian" portuguese: "Portugu�s de Portugal"
in portuguese of Portugal: "Portugu�s"
*/

$iso639_2_code = "br";
//$iso639_1_code = "bre";
//http://www.w3.org/WAI/ER/IG/ert/iso639.htm

$langNameOfLang['brazilian'		] = "portugu�s";
//$langNameOfLang[brazilian_portuguese]="portugu�s";
$langNameOfLang['croatia'		] = "brazilian";
$langNameOfLang['dutch'			] = "Nederlands";
$langNameOfLang['english'		] = "ingl�s";
$langNameOfLang['finnish'		] = "finland�s";
$langNameOfLang['french'		] = "franc�s";
$langNameOfLang['german'		] = "alem�o";
$langNameOfLang['greek'			] = "greek";
$langNameOfLang['italian'		] = "italiano";
$langNameOfLang['japanese'		] = "japon�s";
$langNameOfLang['polish'		] = "polon�s";
$langNameOfLang['simpl_chinese'	] = "chin�s simples";
$langNameOfLang['spanish'		] = "espanhol";
$langNameOfLang['swedish'		] = "sueco";
$langNameOfLang['thai'			] = "tailand�s";
$langNameOfLang['arabic'		] = "arabian";
$langNameOfLang['turkish'		] = "turkish";

$charset = 'iso-8859-1';
$text_dir = 'ltr'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'verdana, arial, helvetica, geneva, sans-serif';
$right_font_family = 'arial, helvetica, geneva, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
$byteUnits = array('Bytes', 'KB', 'MB', 'GB');

$langDay_of_weekNames['init'] = array('D', 'S', 'T', 'Q', 'Q', 'S', 'S');
$langDay_of_weekNames['short'] = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
$langDay_of_weekNames['long'] = array('Domingo', 'Segunda', 'Ter�a', 'Quarta', 'Quinta', 'Sexta', 'S�bado');
$langMonthNames['init']  = array('J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
$langMonthNames['long'] = array('Janeiro', 'Fevereiro', 'Mar�o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

// http://phedre.ipm.ucl.ac.be/phpBB/viewtopic.php?topic=224&forum=6&2
$dateFormatShort =  "%d de %b de %y";
$dateFormatLong  = '%A, %d de %B de %Y';
$dateTimeFormatLong  = '%A, %d de %B de %Y �s %H:%Mh';
$timeNoSecFormat = '%H:%Mh';



// GENERIC

$langModify="modificar";
$langDelete="apagar";
$langTitle="T�tulo";
$langHelp="ajuda";
$langOk="Ok";
$langAddIntro="Adicionar texto introdut�rio";
$langBackList="Voltar para a lista";
$langBack="Vontar para as informa��es do curso";
$langBackH="Home Page do Curso";
$langPropositions="Sugest�es";


// banner

$langMyCourses="Meus cursos";
$langModifyProfile="Modificar meu perf�l";
$langLogout="Logout";

$langAgenda="Agenda";
$langDocument="Documentos";
$langWork="Trabalhos dos Estudantes";
$langAnnouncement="An�ncios";
$langUser="Usu�rios";
$langForum="F�runs";
$langExercise="Exerc�cios";

?>