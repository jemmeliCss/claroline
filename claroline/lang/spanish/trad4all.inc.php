<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.* $Revision: 
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   English Translation                                                |
      +----------------------------------------------------------------------+
      |   This program is free software; you can redistribute it and/or      |
      |   modify it under the terms of the GNU General Public License        |
      |   as published by the Free Software Foundation; either version 2     |
      |   of the License, or (at your option) any later version.             |
      +----------------------------------------------------------------------+

      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |          Thomas Depraetere <depraetere@ipm.ucl.ac.be>                |
      |          Andrew Lynn       <Andrew.Lynn@strath.ac.uk>                |
      |          Olivier Brouckaert <oli.brouckaert@skynet.be>               |
      +----------------------------------------------------------------------+
*/

$englishLangName = "spanish";
$localLangName = "espa�ol";

$iso639_2_code = "es";
$iso639_1_code = "esp";

$langNameOfLang['arabic'		]="�rabe";
$langNameOfLang['brazilian'		]="portugu�s";
$langNameOfLang['bulgarian'		]="bulgarian";
$langNameOfLang['croatian'		]="croato";
$langNameOfLang['dutch'			]="dutch";
$langNameOfLang['english'		]="ingl�s";
$langNameOfLang['finnish'		]="finland�s";
$langNameOfLang['french'		]="franc�s";
$langNameOfLang['german'		]="alem�n";
$langNameOfLang['greek'			]="griego";
$langNameOfLang['italian'		]="italiano";
$langNameOfLang['japanese'		]="japon�s";
$langNameOfLang['polish'		]="polaco";
$langNameOfLang['simpl_chinese'		]="chino";
$langNameOfLang['spanish'		]="espa�ol";
$langNameOfLang['spanish_latin'		]="espa�ol latin";
$langNameOfLang['swedish'		]="sueco";
$langNameOfLang['thai'			]="thailand�s";
$langNameOfLang['turkish'		]="turco";

$charset = 'iso-8859-1';
$text_dir = 'ltr'; // ('ltr' para izq a der, 'rtl' para der a izq)
$left_font_family = 'verdana, helvetica, arial, geneva, sans-serif';
$right_font_family = 'helvetica, arial, geneva, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
$byteUnits = array('Bytes', 'KB', 'MB', 'GB');

$langDay_of_weekNames['init'] = array('S', 'M', 'T', 'W', 'T', 'F', 'S');
$langDay_of_weekNames['short'] = array('Dom', 'Lun', 'Mar', 'Mier', 'Jue', 'Vie', 'Sab');
$langDay_of_weekNames['long'] = array('Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado');

$langMonthNames['init']  = array('E', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec');
$langMonthNames['long'] = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Dicembre');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%b %d, %y";
$dateFormatLong  = '%A %B %d, %Y';
$dateTimeFormatLong  = '%B %d, %Y at %I:%M %p';
$timeNoSecFormat = '%I:%M %p';

// GENERIC

$langYes="Si";
$langNo="No";
$langBack="Regresar";
$langNext="Siguiente";
$langAllowed="Abierta";
$langDenied="Cerrada";
$langPropositions="Proposals for an improvement of";
$langMaj="Actualizar";
$langModify="Modificar";
$langDelete="Borrar";
$langMove="Mover";
$langTitle="Titulo";
$langHelp="Ayuda";
$langOk="Aceptar";
$langAdd="A�adir";
$langAddIntro="A�adir texto introductorio";
$langBackList="Regresar a la lista";
$langText="Texto";
$langEmpty="Vac�o";
$langConfirmYourChoice="Por favor confirma t� elecci�n";
$langAnd="y";
$langChoice="T� elecci�n";
$langFinish="Terminar";
$langCancel="Cancelar";
$langNotAllowed="No tienes acceso";
$langManager="Coordinador"; // Think about what kind of manager...
$lang_footer_CourseManager = "Coordinador(es)";
$langPoweredBy="Generado con";
$langOptional="Opcional";
$langNextPage="Pagina siguiente";
$langPreviousPage="Pagina anterior";
$langUse="Use";
$langTotal="Total";
$langTake="tomar";
$langOne="Uno";
$langSeveral="Varios";
$langNotice="Noticia";
$langDate="Fecha";
$langAmong="Entre";

// banner

$langMyCourses="Mis cursos";
$langModifyProfile="Modificar mis datos";
$langMyAgenda = "Mi agenda";
$langLogout="Salir";


//needed for student view
$langCourseManagerview = "Ver como Coordinador";
$langStudentView = "Ver como estudiante";






$lang_this_course_is_protected = 'This course is protected';
$lang_enter_your_user_name_and_password = 'Enter your user name and password';
$lang_if_you_dont_have_a_user_account_profile_on = 'If you don\'t have a user account on';
$lang_click_here = 'click here';
$lang_your_user_profile_doesnt_seem_to_be_enrolled_to_this_course = "You're user profile doesn't seem to be enrolled to this course";
$lang_if_you_wish_to_enroll_to_this_course = "If you wish to enroll to this course";
$langUserName = "User name";
$lang_password = "Password";



// TOOLNAMES
$langCourseHome = "Descripci�n del curso";
$langAgenda = "Agenda";
$langLink="Ligas";
$langDocument="Documentos";
$langWork="Trabajos";
$langAnnouncement="Anuncios";
$langUsers="Usuarios";
$langForums = "Foros";
$langExercises ="Ejercicios";
$langGroups ="Grupos";
$langChat ="Charla";
$langLearnPath="Lecciones";
$langDescriptionCours  = "Descripci�n del curso";
$langPlatformAdministration = "Administraci�n de la plataforma";

?>
