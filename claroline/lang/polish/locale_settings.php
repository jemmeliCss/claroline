<?php // $Id$
/**
 * CLAROLINE
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @author: S�awomir Gurda�a <guslaw@uni.lodz.pl>                    |
 * @author: claro team <cvs@claroline.net>
 *
 * @package LANG-PL
*/
$englishLangName = "Polish";

$iso639_1_code = "pl";
$iso639_2_code = "pol";

$langNameOfLang['arabic']          = 'Arabski';
$langNameOfLang['armenian']        = 'Ormia�ski';
//$langNameOfLang['brazilian]      = "brazilian";
$langNameOfLang['ulgarian']        = 'Bu�garski';
//$langNameOfLang['catalan']       = "";
$langNameOfLang['croatian']        = 'Chorwacki';
$langNameOfLang['czech']           = 'Czeski';
$langNameOfLang['czechSlovak']     = 'Czesko-s�owacki';
$langNameOfLang['danish']          = 'Du�ski';
//$langNameOfLang['dutch']         = "";
//$langNameOfLang['dutch']         = "";
$langNameOfLang['english']         = 'Angielski';
$langNameOfLang['esperanto']       = 'Esperanto';
$langNameOfLang['estonian']        = 'Esto�ski';
//$langNameOfLang['finnish']       = "finnish";
//$langNameOfLang['french']        = "french";
$langNameOfLang['georgian']        = 'Gruzi�ski';
$langNameOfLang['german']          = 'Niemiecki';
$langNameOfLang['greek']           = 'Grecki';
//$langNameOfLang['italian']       = "italian";
//$langNameOfLang['japanese']      = "japanese";
$langNameOfLang['polish']          = 'Polski';
//$langNameOfLang['simpl_chinese'] = "simplified chinese";
$langNameOfLang['spanish']         = 'Hiszpa�ski';
$langNameOfLang['swedish']         = 'Szwedzki';
$langNameOfLang['thai']            = 'Tajski';
$langNameOfLang['turkish']         = 'Turecki';
$langNameOfLang['ukrainian']       = 'Ukrai�ski';;

$charset = 'iso-8859-2';
$text_dir = 'ltr'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'verdana, helvetica, arial, geneva, sans-serif';
$right_font_family = 'helvetica, arial, geneva, sans-serif';
$number_thousands_separator = ' ';
$number_decimal_separator = ',';
// shortcuts for Byte, Kilo, Mega, Giga, Tera, Peta, Exa
$byteUnits = array('bajt�w', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$langDay_of_weekNames['init'] = array('N', 'P', 'W', '�', 'C', 'Pt', 'S');
$langDay_of_weekNames['short'] = array('Nied', 'Pon', 'Wt', '�r', 'Czw', 'Pt', 'Sob');
$langDay_of_weekNames['long'] = array('Niedziela', 'Poniedzia�ek', 'Wtorek', '�roda', 'Czwartek', 'Pi�tek', 'Sobota');

$langMonthNames['init']  = array('S', 'L', 'M', 'K', 'M', 'C', 'L', 'S', 'W', 'P', 'L', 'G');
$langMonthNames['short'] = array('Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Cze', 'Lip', 'Sie', 'Wrz', 'Pa�', 'Lis', 'Gru');
$langMonthNames['long'] = array('Stycze�', 'Luty', 'Marzec', 'Kwiecie�', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpie�', 'Wrzesie�', 'Pa�dziernik', 'Listopad', 'Grudzie�');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%d %b %y";
$dateFormatLong  = '%A, %d %B %Y';
$dateTimeFormatLong  = '%d %B %Y, %H:%M';
$timeNoSecFormat = '%H:%M';
$timespanfmt = '%s dni, %s godzin, %s minut i %s sekund';

?>