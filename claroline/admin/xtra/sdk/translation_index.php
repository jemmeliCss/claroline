<?php // $Id$
//----------------------------------------------------------------------
// CLAROLINE
//----------------------------------------------------------------------
// Copyright (c) 2001-2006 Universite catholique de Louvain (UCL)
//----------------------------------------------------------------------
// This program is under the terms of the GENERAL PUBLIC LICENSE (GPL)
// as published by the FREE SOFTWARE FOUNDATION. The GPL is available
// through the world-wide-web at http://www.gnu.org/copyleft/gpl.html
//----------------------------------------------------------------------
// Authors: see 'credits' file
//----------------------------------------------------------------------

$cidReset=true;
$gidReset=true;

require '../../../inc/claro_init_global.inc.php';
include $includePath.'/lib/debug.lib.inc.php';
include 'lang/language.conf.php';

$nameTools = get_lang('Translation Tools');
$urlSDK = $rootAdminWeb . 'xtra/sdk/';
$table_exists = TRUE;

// Security check
if ( ! $_uid ) claro_disp_auth_form();
if ( ! $is_platformAdmin ) claro_die(get_lang('Not allowed'));

// table

$tbl_used_lang = '`' . $mainDbName . '`.`' . $mainTblPrefix . TABLE_USED_LANG_VAR . '`';
$tbl_used_translation =  '`' . $mainDbName . '`.`' . $mainTblPrefix . TABLE_TRANSLATION . '`';

$sql1 = " select count(*) from " . $tbl_used_lang;
$sql2 = " select count(*) from " . $tbl_used_translation;

mysql_query($sql1);
if ( mysql_errno() == 1146 ) $table_exists = FALSE;

mysql_query($sql2);
if ( mysql_errno() == 1146 ) $table_exists = FALSE;

// DISPLAY

// Deal with interbredcrumps  and title variable

$interbredcrump[] = array ('url' => $rootAdminWeb, 'name' => get_lang('Administration'));
$interbredcrump[] = array ('url' => $urlSDK, 'name' => get_lang('SDK'));

include $includePath . '/claro_init_header.inc.php';

// echo claro_html::tool_title('<img src="lang/language.png" style="vertical-align: middle;" alt="" /> '.$nameTools);
echo claro_html::tool_title($nameTools);
?>
<h4><?php echo get_lang('Extract language variables')?></h4>
<ul>
<li><a href="lang/extract_var_from_lang_file.php"><?php echo get_lang('From language files')?></a></li>
<li><a href="lang/extract_var_from_script_file.php"><?php echo get_lang('From script files')?></a></li>
</ul>

<?php
if ( $table_exists == TRUE )
{
?>

<h4><?php echo get_lang('Build language files')?></h4>
<ul>
<li><a href="lang/build_devel_lang_file.php"><?php echo get_lang('Complete language files')?></a></li>
<li><a href="lang/build_prod_lang_file.php"><?php echo get_lang('Production language files')?></a></li>
<li><a href="lang/build_missing_lang_file.php"><?php echo get_lang('Missing language files')?></a></li>
<li><a href="lang/build_empty_lang_file.php"><?php echo get_lang('Empty language file')?></a></li>
</ul>

<h4><?php echo get_lang('Find doubled variables')?></h4>
<ul>
<li><a href="lang/display_var_diff.php"><?php echo get_lang('Variables with same name and different content')?></a></li>
<li><a href="lang/display_content_diff.php"><?php echo get_lang('Variables with same content and different name')?></a></li>
</ul>

<h4><?php echo get_lang('Translation Progression')?></h4>
<ul>
<li><a href="lang/progression_translation.php"><?php echo get_lang('Translation Progression')?></a></li>
</ul>

<h4><?php echo get_lang('Conversion')?></h4>
<ul>
<li><a href="lang/convert_lang_17_to_18.php"><?php echo get_lang('Conversion 1.7 to 1.8')?></a></li>
</ul>
<?php
}
?>

<?php
include $includePath . '/claro_init_footer.inc.php';
?>
