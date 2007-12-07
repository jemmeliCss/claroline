<?php // $Id$
/**
 * CLAROLINE 
 *
 * Greek Translation
 *
 * @version 1.8 $Revision$
 *
 * @copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
 *
 * @license http://www.gnu.org/copyleft/gpl.html (GPL) GENERAL PUBLIC LICENSE 
 *
 * @package LANG-EL
 *
 * @author Costas Tsibanis		<costas@noc.uoa.gr>
 * @author Yannis Exidaridis 	<jexi@noc.uoa.gr>
 * @author Christophe Gesch� <moosh@claroline.net>
 */
$englishLangName = "greek";

$iso639_1_code = "el";
$iso639_2_code = "gre";

$charset = 'iso-8859-7';
$text_dir = 'ltr'; // ('ltr' for left to right, 'rtl' for right to left)
$left_font_family = 'verdana, helvetica, arial, geneva, sans-serif';
$right_font_family = 'helvetica, arial, geneva, sans-serif';
$number_thousands_separator = ',';
$number_decimal_separator = '.';
$byteUnits = array('Bytes', 'KB', 'MB', 'GB');

$langDay_of_weekNames['init'] = array('�', '�', '�', '�', '�', '�', '�');
$langDay_of_weekNames['short'] = array('���', '���', '���', '���', '���', '���', '���');
$langDay_of_weekNames['long'] = array('�������', '�������', '�����', '�������', '������', '���������', '�������');

$langMonthNames['init']  = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
$langMonthNames['short'] = array('���', '���', '���', '���', '���', '����', '����', '���', '���', '���', '���', '���');
$langMonthNames['long'] = array('����������', '�����������', '�������', '��������', '�����', '�������', '�������', '���������', '�����������', '���������', '���������', '����������');

// Voir http://www.php.net/manual/en/function.strftime.php pour la variable
// ci-dessous

$dateFormatShort =  "%b %d, %y";
$dateFormatLong  = '%A %B %d, %Y';
$dateTimeFormatLong  = '%d %B %Y, ���� %I:%M %p';
$timeNoSecFormat = '%I:%M %p';


?>
