<?php // $Id$
/*
      +----------------------------------------------------------------------+
      | CLAROLINE version 1.5.* $Revision$                             |
      +----------------------------------------------------------------------+
      | Copyright (c) 2001, 2004 Universite catholique de Louvain (UCL)      |
      +----------------------------------------------------------------------+
      |   Greek Translation                                                  |
      +----------------------------------------------------------------------+

      +----------------------------------------------------------------------+
      | Translator :                                                         |
      |          Costas Tsibanis		<costas@noc.uoa.gr>                      |
      |          Yannis Exidaridis 	<jexi@noc.uoa.gr>                        |
      +----------------------------------------------------------------------+
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
$dateTimeFormatLong  = '%B %d, %Y at %I:%M %p';
$timeNoSecFormat = '%I:%M %p';


?>
