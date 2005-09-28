<?php // $Id$
/**
 * CLAROLINE 
 *
 * @version 1.7 $Revision$
 *
 * @copyright (c) 2001-2005 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package LANG-EN
 *
 * @author Claro team <cvs@claroline.net>
 */

$iso639_1_code = "en";
$iso639_2_code = "eng";

unset($langNameOfLang);
unset($langDay_of_weekNames);
unset($langMonthNames);
unset($byteUnits);

$langNameOfLang['arabic']        = "arab";
$langNameOfLang['brazilian']     = "brazil";
$langNameOfLang['bulgarian']     = "bolg�r";
$langNameOfLang['catalan']       = "katal�n";
$langNameOfLang['croatian']      = "horv�t";
$langNameOfLang['danish']        = "d�n";
$langNameOfLang['dutch']         = "holland";
$langNameOfLang['english']       = "angol";
$langNameOfLang['finnish']       = "finn";
$langNameOfLang['french']        = "francia";
$langNameOfLang['galician']      = "gal�ciai";
$langNameOfLang['hungarian']      = "magyar";
$langNameOfLang['german']        = "n�met";
$langNameOfLang['greek']         = "g�r�g";
$langNameOfLang['italian']       = "olasz";
$langNameOfLang['indonesian']    = "indon�ziai";
$langNameOfLang['japanese']      = "jap�n";
$langNameOfLang['malay']         = "mal�j"; 
$langNameOfLang['polish']        = "lengyel";
$langNameOfLang['portuguese']    = "portug�l";
$langNameOfLang['russian']       = "orosz";
$langNameOfLang['simpl_chinese'] = "egyszer�s�tett k�nai";
$langNameOfLang['slovenian']     = "szlov�n";
$langNameOfLang['spanish']       = "spanyol";
$langNameOfLang['swedish']       = "sv�d";
$langNameOfLang['thai']          = "thai";
$langNameOfLang['turkish']       = "t�r�k";
$langNameOfLang['vietnamese']    = "vietn�mi";

$charset = 'iso-8859-2';
$text_dir = 'ltr'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'verdana, helvetica, arial, geneva, sans-serif';
$right_font_family = 'helvetica, arial, geneva, sans-serif';
$number_thousands_separator = '.';
$number_decimal_separator = ',';
$byteUnits = array('Byte', 'KB', 'MB', 'GB');

$langDay_of_weekNames['init'] = array('V', 'H', 'K', 'S', 'C', 'P', 'S');
$langDay_of_weekNames['short'] = array('Vas', 'H�t', 'Kedd', 'Sze', 'Cs�', 'P�n', 'Szo');
$langDay_of_weekNames['long'] = array('Vas�rnap', 'H�tf�', 'Kedd', 'Szerda', 'Cs�t�rt�k', 'P�ntek', 'Szombat');

$langMonthNames['init']  = array('J', 'F', 'M', '�', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Jan', 'Feb', 'M�r', '�pr', 'M�j', 'J�n', 'J�l', 'Aug', 'Sze', 'Okt', 'Nov', 'Dec');
$langMonthNames['long'] = array('Janu�r', 'Febru�r', 'M�rcius', '�prilis', 'M�jus', 'J�nius', 'J�lius', 'Augusztus', 'Szeptember', 'Okt�ber', 'November', 'December');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%b. %d, %y";
$dateFormatLong  = '%A %B %d, %Y';
$dateTimeFormatLong  = '%B %d, %Y at %I:%M %p';
$dateTimeFormatShort = "%b. %d, %y %I:%M %p";
$timeNoSecFormat = '%I:%M %p';

?>