<?php // $Id$
/**
 * CLAROLINE
 * Simplified Chinese Translation
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package LANG-ZH
 *
 * @author Claro team <cvs@claroline.net>
 */
$englishLangName = "Chinese";
$localLangName = "zho";

$iso639_1_code = "zh";
$iso639_2_code = "zho";

$langNameOfLang['brazilian']="brazilian";
$langNameOfLang['english']="english";
$langNameOfLang['finnish']="finnish";
$langNameOfLang['french']="french";
$langNameOfLang['german']="german";
$langNameOfLang['italian']="italian";
$langNameOfLang['japanese']="japanese";
$langNameOfLang['polish']="polish";
$langNameOfLang['simpl_chinese']="simplified chinese";
$langNameOfLang['spanish']="spanish";
$langNameOfLang['swedish']="swedish";
$langNameOfLang['thai']="thai";

$charset = 'gb2312';
$text_dir = 'ltr'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'simsun, ����';
$right_font_family = 'simsun';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
$byteUnits = array('�ֽ�', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');

$langDay_of_weekNames['init'] = array('S', 'M', 'T', 'W', 'T', 'F', 'S');
$langDay_of_weekNames['short'] = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
$langDay_of_weekNames['long'] = array('����', '��һ', '�ܶ�', '����', '����', '����', '����');

$langMonthNames['init']  = array('J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D');
$langMonthNames['short'] = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
$langMonthNames['long'] = array('һ��', '����', '����', '����', '����', '����', '����', '����', '����', 'ʮ��', 'ʮһ��', 'ʮ����');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%b %d, %y";
$dateFormatLong  = '%A %B %d, %Y';
$dateTimeFormatLong  = '%B %d, %Y at %I:%M %p';
$timeNoSecFormat = '%I:%M %p';

?>
